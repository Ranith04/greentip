using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Dashboard;
using GreenTIP.Application.Interfaces;
using GreenTIP.Domain.Entities;
using GreenTIP.Domain.Repositories;
using Microsoft.EntityFrameworkCore;

namespace GreenTIP.Application.Services;

public class DashboardService : IDashboardService
{
    private readonly IUnitOfWork _unitOfWork;

    public DashboardService(IUnitOfWork unitOfWork)
    {
        _unitOfWork = unitOfWork;
    }

    public async Task<ApiResponse<UserDashboardDto>> GetUserDashboardAsync(int userId, CancellationToken ct = default)
    {
        var user = await _unitOfWork.Repository<User>().GetByIdAsync(userId, ct);
        var queryRepo = _unitOfWork.Repository<Query>();

        var queries = await queryRepo.Query()
            .Include(q => q.Category)
            .Include(q => q.Assignments)
            .Where(q => q.UserId == userId)
            .OrderByDescending(q => q.AddedOn)
            .ToListAsync(ct);

        var total = queries.Count;
        var pending = queries.Count(q => q.Status == 0);
        var answered = queries.Count(q => q.Status == 1 || q.Assignments.Any(a => a.RespondDate != null));

        var unreadNotifications = await _unitOfWork.Repository<AppNotification>().Query()
            .CountAsync(n => n.UserId == userId && !n.IsRead, ct);

        var recentItems = queries.Take(5).Select(q =>
        {
            var isResponded = q.Status == 1 || q.Assignments.Any(a => a.RespondDate != null);
            var statusStr = isResponded ? "Responded" : (q.Assignments.Any() ? "In Progress" : "Pending");
            var badgeColor = isResponded ? "purple" : (q.Assignments.Any() ? "blue" : "orange");

            return new RecentQueryItemDto
            {
                Id = q.Id,
                QueryCode = q.QueryCode ?? $"GT-{q.Id:D5}",
                CategoryName = q.Category?.CatName ?? "Environmental Clearance",
                Title = q.QueryTitle ?? q.QueryText?.Split('\n').FirstOrDefault() ?? "Query",
                DateFormatted = (q.AddedOn ?? DateTime.UtcNow).ToString("MMM dd, yyyy"),
                Status = statusStr,
                StatusBadgeColor = badgeColor
            };
        }).ToList();

        var dashboard = new UserDashboardDto
        {
            GreetingName = user?.Name ?? "User",
            UnreadNotificationsCount = unreadNotifications,
            TotalQueries = total,
            PendingQueries = pending,
            AnsweredQueries = answered,
            RecentQueries = recentItems
        };

        return ApiResponse<UserDashboardDto>.Ok(dashboard);
    }

    public async Task<ApiResponse<AdminDashboardDto>> GetAdminDashboardAsync(int adminUserId, CancellationToken ct = default)
    {
        var admin = await _unitOfWork.Repository<User>().GetByIdAsync(adminUserId, ct);
        var queryRepo = _unitOfWork.Repository<Query>();
        var userRepo = _unitOfWork.Repository<User>();

        var allQueries = await queryRepo.Query()
            .Include(q => q.User)
            .Include(q => q.Category)
            .Include(q => q.Assignments)
            .OrderByDescending(q => q.AddedOn)
            .ToListAsync(ct);

        var newQueriesCount = allQueries.Count(q => !q.Assignments.Any());
        var respondQueriesCount = allQueries.Count(q => q.Assignments.Any(a => a.RespondDate == null));
        var respondedCount = allQueries.Count(q => q.Assignments.Any(a => a.RespondDate != null) || q.Status == 1);

        var totalUsers = await userRepo.Query().CountAsync(u => u.RoleType == "2", ct);
        var totalExperts = await userRepo.Query().CountAsync(u => u.RoleType == "1", ct);

        var unreadNotifications = await _unitOfWork.Repository<AppNotification>().Query()
            .CountAsync(n => (n.UserId == adminUserId || n.UserId == 0) && !n.IsRead, ct);

        var recentItems = allQueries.Take(5).Select(q =>
        {
            var isResponded = q.Assignments.Any(a => a.RespondDate != null) || q.Status == 1;
            var isRespond = !isResponded && q.Assignments.Any();
            var badge = isResponded ? "Responded" : (isRespond ? "Respond" : "New");

            return new AdminRecentQueryItemDto
            {
                Id = q.Id,
                QueryCode = q.QueryCode ?? $"GT-{q.Id:D5}",
                Title = q.QueryTitle ?? q.QueryText?.Split('\n').FirstOrDefault() ?? "Query",
                SubmitterName = q.User?.Name ?? "User",
                DateFormatted = (q.AddedOn ?? DateTime.UtcNow).ToString("MMM dd, yyyy"),
                CategoryName = q.Category?.CatName ?? "General",
                StatusBadge = badge
            };
        }).ToList();

        var dashboard = new AdminDashboardDto
        {
            GreetingName = admin?.Name ?? "Administrator",
            AdminRole = "Administrator",
            UnreadNotificationsCount = unreadNotifications,
            NewQueriesCount = newQueriesCount,
            RespondQueriesCount = respondQueriesCount,
            RespondedQueriesCount = respondedCount,
            TotalUsersCount = totalUsers,
            TotalExpertsCount = totalExperts,
            RecentQueries = recentItems
        };

        return ApiResponse<AdminDashboardDto>.Ok(dashboard);
    }
}
