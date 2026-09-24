using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Dashboard;
using GreenTIP.Application.DTOs.Users;
using GreenTIP.Application.Interfaces;
using GreenTIP.Domain.Entities;
using GreenTIP.Domain.Repositories;
using Microsoft.EntityFrameworkCore;

namespace GreenTIP.Application.Services;

public class UserService : IUserService
{
    private readonly IUnitOfWork _unitOfWork;
    private readonly IPasswordHasher _passwordHasher;

    public UserService(IUnitOfWork unitOfWork, IPasswordHasher passwordHasher)
    {
        _unitOfWork = unitOfWork;
        _passwordHasher = passwordHasher;
    }

    public async Task<ApiResponse<PaginatedResult<UserListDto>>> GetUsersAsync(string? search, int page, int pageSize, CancellationToken ct = default)
    {
        var queryable = _unitOfWork.Repository<User>().Query()
            .Include(u => u.SubmittedQueries)
            .Where(u => u.RoleType == "2");

        if (!string.IsNullOrEmpty(search))
        {
            queryable = queryable.Where(u => (u.Name != null && u.Name.Contains(search))
                || (u.Email != null && u.Email.Contains(search))
                || (u.CompanyName != null && u.CompanyName.Contains(search)));
        }

        var total = await queryable.CountAsync(ct);
        var users = await queryable
            .OrderByDescending(u => u.CreatedOn)
            .Skip((page - 1) * pageSize)
            .Take(pageSize)
            .ToListAsync(ct);

        var dtos = users.Select(u => new UserListDto
        {
            Id = u.Id,
            Name = u.Name ?? "User",
            Email = u.Email ?? string.Empty,
            TotalQueries = u.SubmittedQueries.Count,
            Status = u.Status == "1" ? "Active" : "Inactive"
        }).ToList();

        return ApiResponse<PaginatedResult<UserListDto>>.Ok(new PaginatedResult<UserListDto>(dtos, total, page, pageSize));
    }

    public async Task<ApiResponse<UserDetailDto>> GetUserDetailAsync(int userId, CancellationToken ct = default)
    {
        var user = await _unitOfWork.Repository<User>().Query()
            .Include(u => u.SubmittedQueries)
                .ThenInclude(q => q.Assignments)
            .FirstOrDefaultAsync(u => u.Id == userId, ct);

        if (user == null) return ApiResponse<UserDetailDto>.Fail("User not found.");

        var total = user.SubmittedQueries.Count;
        var pending = user.SubmittedQueries.Count(q => q.Status == 0);
        var answered = user.SubmittedQueries.Count(q => q.Status == 1 || q.Assignments.Any(a => a.RespondDate != null));

        var dto = new UserDetailDto
        {
            Id = user.Id,
            Name = user.Name ?? "User",
            Role = "End User",
            Email = user.Email ?? string.Empty,
            Mobile = user.ContactNo?.ToString() ?? "+91 98765 43210",
            Organization = string.IsNullOrEmpty(user.CompanyName) ? "Individual" : user.CompanyName,
            Status = user.Status == "1" ? "Active" : "Inactive",
            TotalQueries = total,
            PendingQueries = pending,
            AnsweredQueries = answered
        };

        return ApiResponse<UserDetailDto>.Ok(dto);
    }

    public async Task<ApiResponse<PaginatedResult<RecentQueryItemDto>>> GetUserQueryHistoryAsync(int userId, string? status, int page, int pageSize, CancellationToken ct = default)
    {
        var queryable = _unitOfWork.Repository<Query>().Query()
            .Include(q => q.Category)
            .Include(q => q.Assignments)
            .Where(q => q.UserId == userId);

        if (!string.IsNullOrEmpty(status) && status != "All")
        {
            if (status.Equals("Pending", StringComparison.OrdinalIgnoreCase))
                queryable = queryable.Where(q => q.Status == 0 && !q.Assignments.Any(a => a.RespondDate != null));
            else if (status.Equals("Responded", StringComparison.OrdinalIgnoreCase))
                queryable = queryable.Where(q => q.Assignments.Any(a => a.RespondDate != null));
            else if (status.Equals("Closed", StringComparison.OrdinalIgnoreCase))
                queryable = queryable.Where(q => q.Status == 1);
        }

        var total = await queryable.CountAsync(ct);
        var items = await queryable
            .OrderByDescending(q => q.AddedOn)
            .Skip((page - 1) * pageSize)
            .Take(pageSize)
            .ToListAsync(ct);

        var dtos = items.Select(q =>
        {
            var isResponded = q.Assignments.Any(a => a.RespondDate != null);
            var isClosed = q.Status == 1;
            return new RecentQueryItemDto
            {
                Id = q.Id,
                QueryCode = q.QueryCode ?? $"GT-{q.Id:D5}",
                CategoryName = q.Category?.CatName ?? "General",
                Title = q.QueryTitle ?? "Query",
                DateFormatted = (q.AddedOn ?? DateTime.UtcNow).ToString("MMM dd, yyyy"),
                Status = isClosed ? "Closed" : (isResponded ? "Responded" : "Pending"),
                StatusBadgeColor = isClosed ? "green" : (isResponded ? "purple" : "orange")
            };
        }).ToList();

        return ApiResponse<PaginatedResult<RecentQueryItemDto>>.Ok(new PaginatedResult<RecentQueryItemDto>(dtos, total, page, pageSize));
    }

    public async Task<ApiResponse<bool>> ToggleUserStatusAsync(int userId, CancellationToken ct = default)
    {
        var user = await _unitOfWork.Repository<User>().GetByIdAsync(userId, ct);
        if (user == null) return ApiResponse<bool>.Fail("User not found.");

        user.Status = user.Status == "1" ? "0" : "1";
        user.UpdatedOn = DateTime.UtcNow;
        await _unitOfWork.SaveChangesAsync(ct);

        return ApiResponse<bool>.Ok(true, $"User status changed to {(user.Status == "1" ? "Active" : "Inactive")}");
    }

    public async Task<ApiResponse<bool>> UpdateProfileAsync(int userId, UpdateProfileDto request, CancellationToken ct = default)
    {
        var user = await _unitOfWork.Repository<User>().GetByIdAsync(userId, ct);
        if (user == null) return ApiResponse<bool>.Fail("User not found.");

        if (!string.IsNullOrEmpty(request.FullName)) user.Name = request.FullName;
        if (!string.IsNullOrEmpty(request.Designation)) user.Occupation = request.Designation;
        if (!string.IsNullOrEmpty(request.Organization)) user.CompanyName = request.Organization;
        if (!string.IsNullOrEmpty(request.AvatarUrl)) user.Image = request.AvatarUrl;

        if (!string.IsNullOrEmpty(request.Mobile) && long.TryParse(new string(request.Mobile.Where(char.IsDigit).ToArray()), out var phone))
        {
            user.ContactNo = phone;
        }

        user.UpdatedOn = DateTime.UtcNow;
        await _unitOfWork.SaveChangesAsync(ct);

        return ApiResponse<bool>.Ok(true, "Profile updated successfully.");
    }

    public async Task<ApiResponse<bool>> ChangePasswordAsync(int userId, ChangePasswordDto request, CancellationToken ct = default)
    {
        var user = await _unitOfWork.Repository<User>().GetByIdAsync(userId, ct);
        if (user == null) return ApiResponse<bool>.Fail("User not found.");

        if (!_passwordHasher.VerifyPassword(request.CurrentPassword, user.Password ?? string.Empty))
        {
            return ApiResponse<bool>.Fail("Current password is incorrect.");
        }

        if (request.NewPassword != request.ConfirmNewPassword)
        {
            return ApiResponse<bool>.Fail("New passwords do not match.");
        }

        user.Password = _passwordHasher.HashPassword(request.NewPassword);
        user.UpdatedOn = DateTime.UtcNow;
        await _unitOfWork.SaveChangesAsync(ct);

        return ApiResponse<bool>.Ok(true, "Password updated successfully.");
    }
}

public class ExpertService : IExpertService
{
    private readonly IUnitOfWork _unitOfWork;
    private readonly IPasswordHasher _passwordHasher;

    public ExpertService(IUnitOfWork unitOfWork, IPasswordHasher passwordHasher)
    {
        _unitOfWork = unitOfWork;
        _passwordHasher = passwordHasher;
    }

    public async Task<ApiResponse<List<ExpertListDto>>> GetExpertsAsync(string? search, CancellationToken ct = default)
    {
        var queryable = _unitOfWork.Repository<User>().Query()
            .Include(u => u.AssignedQueries)
            .Where(u => u.RoleType == "1");

        if (!string.IsNullOrEmpty(search))
        {
            queryable = queryable.Where(u => (u.Name != null && u.Name.Contains(search))
                || (u.ExpertiseField != null && u.ExpertiseField.Contains(search))
                || (u.Occupation != null && u.Occupation.Contains(search)));
        }

        var experts = await queryable.ToListAsync(ct);

        var dtos = experts.Select(e => new ExpertListDto
        {
            Id = e.Id,
            Name = e.Name ?? "Expert",
            Designation = string.IsNullOrEmpty(e.Occupation) ? "Environmental Expert" : e.Occupation,
            CategorySpecialty = string.IsNullOrEmpty(e.ExpertiseField) ? "Environmental Clearance" : e.ExpertiseField,
            Status = e.Status == "1" ? "Active" : "Inactive",
            AvatarUrl = e.Image,
            AssignedQueriesCount = e.AssignedQueries.Count,
            AnsweredQueriesCount = e.AssignedQueries.Count(a => a.RespondDate != null),
            PendingQueriesCount = e.AssignedQueries.Count(a => a.RespondDate == null)
        }).ToList();

        return ApiResponse<List<ExpertListDto>>.Ok(dtos);
    }

    public async Task<ApiResponse<ExpertDetailDto>> GetExpertDetailAsync(int expertId, CancellationToken ct = default)
    {
        var expert = await _unitOfWork.Repository<User>().Query()
            .Include(u => u.AssignedQueries)
            .FirstOrDefaultAsync(u => u.Id == expertId && u.RoleType == "1", ct);

        if (expert == null) return ApiResponse<ExpertDetailDto>.Fail("Expert not found.");

        var categories = !string.IsNullOrEmpty(expert.ExpertiseField)
            ? expert.ExpertiseField.Split(new[] { ',', ';' }, StringSplitOptions.RemoveEmptyEntries).Select(s => s.Trim()).ToList()
            : new List<string> { "Environmental Clearance", "Enviro-Legal", "CRZ" };

        var dto = new ExpertDetailDto
        {
            Id = expert.Id,
            Name = expert.Name ?? "Expert",
            Designation = string.IsNullOrEmpty(expert.Occupation) ? "Environmental Expert" : expert.Occupation,
            Email = expert.Email ?? string.Empty,
            Mobile = expert.ContactNo?.ToString() ?? "+91 98765 43210",
            Status = expert.Status == "1" ? "Active" : "Inactive",
            AvatarUrl = expert.Image,
            ExpertiseCategories = categories,
            AssignedCategoryCount = 12,
            AnsweredQueriesCount = expert.AssignedQueries.Count(a => a.RespondDate != null),
            PendingQueriesCount = expert.AssignedQueries.Count(a => a.RespondDate == null)
        };

        return ApiResponse<ExpertDetailDto>.Ok(dto);
    }

    public async Task<ApiResponse<bool>> CreateExpertAsync(CreateExpertDto request, CancellationToken ct = default)
    {
        var userRepo = _unitOfWork.Repository<User>();
        var exists = await userRepo.Query().AnyAsync(u => u.Email == request.Email, ct);
        if (exists) return ApiResponse<bool>.Fail("An account with this email already exists.");

        var expert = new User
        {
            Name = request.FullName,
            Email = request.Email,
            Password = _passwordHasher.HashPassword("Expert@123"), // Default credentials
            RoleType = "1", // Expert
            Status = "1", // Active
            Occupation = request.Designation,
            ExpertiseField = request.ExpertiseCategory,
            Image = request.PhotoUrl,
            CreatedOn = DateTime.UtcNow,
            UpdatedOn = DateTime.UtcNow
        };

        if (long.TryParse(new string(request.Mobile.Where(char.IsDigit).ToArray()), out var phone))
        {
            expert.ContactNo = phone;
        }

        await userRepo.AddAsync(expert, ct);
        await _unitOfWork.SaveChangesAsync(ct);

        return ApiResponse<bool>.Ok(true, "Expert profile created successfully.");
    }

    public async Task<ApiResponse<bool>> ToggleExpertStatusAsync(int expertId, CancellationToken ct = default)
    {
        var expert = await _unitOfWork.Repository<User>().GetByIdAsync(expertId, ct);
        if (expert == null) return ApiResponse<bool>.Fail("Expert not found.");

        expert.Status = expert.Status == "1" ? "0" : "1";
        expert.UpdatedOn = DateTime.UtcNow;
        await _unitOfWork.SaveChangesAsync(ct);

        return ApiResponse<bool>.Ok(true, $"Expert status changed to {(expert.Status == "1" ? "Active" : "Inactive")}");
    }
}
