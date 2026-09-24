using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Dashboard;
using GreenTIP.Application.DTOs.Queries;
using GreenTIP.Application.Interfaces;
using GreenTIP.Domain.Entities;
using GreenTIP.Domain.Repositories;
using Microsoft.EntityFrameworkCore;

namespace GreenTIP.Application.Services;

public class QueryService : IQueryService
{
    private readonly IUnitOfWork _unitOfWork;

    public QueryService(IUnitOfWork unitOfWork)
    {
        _unitOfWork = unitOfWork;
    }

    public async Task<ApiResponse<QueryDetailDto>> SubmitQueryAsync(int userId, SubmitQueryRequestDto request, CancellationToken ct = default)
    {
        var user = await _unitOfWork.Repository<User>().GetByIdAsync(userId, ct);
        if (user == null) return ApiResponse<QueryDetailDto>.Fail("User not found.");

        var queryRepo = _unitOfWork.Repository<Query>();
        var totalCount = await queryRepo.CountAsync(cancellationToken: ct);
        var queryCode = $"GT-{DateTime.UtcNow.Year}-{(totalCount + 1):D5}";

        var query = new Query
        {
            UserId = userId,
            CategoryId = request.CategoryId,
            QueryCode = queryCode,
            QueryTitle = request.Title,
            QueryText = request.Description,
            ProjectType = request.ProjectType,
            Location = request.Location,
            ProjectAreaSqm = request.ProjectAreaSqm,
            Status = 0, // Pending
            AddedOn = DateTime.UtcNow,
            ModifiedOn = DateTime.UtcNow
        };

        if (request.Attachments != null && request.Attachments.Any())
        {
            foreach (var att in request.Attachments)
            {
                query.Attachments.Add(new QueryAttachment
                {
                    UploadedBy = userId,
                    FileName = att.FileName,
                    FilePath = att.FilePath,
                    FileSizeBytes = att.FileSizeBytes,
                    MimeType = att.MimeType,
                    IsExpertResponse = false,
                    CreatedAt = DateTime.UtcNow
                });
            }
        }

        await queryRepo.AddAsync(query, ct);

        // Add in-app notification for admin
        var notifRepo = _unitOfWork.Repository<AppNotification>();
        await notifRepo.AddAsync(new AppNotification
        {
            UserId = 0, // Broadcast to admin
            Title = "New query received",
            Message = $"{user.Name ?? "User"} submitted a new query: {request.Title}",
            Type = "new_query",
            TargetId = query.QueryCode,
            CreatedAt = DateTime.UtcNow
        }, ct);

        await _unitOfWork.SaveChangesAsync(ct);

        return await GetQueryDetailAsync(query.Id, userId, ct);
    }

    public async Task<ApiResponse<PaginatedResult<RecentQueryItemDto>>> GetUserQueriesAsync(int userId, string? status, int page, int pageSize, CancellationToken ct = default)
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
                queryable = queryable.Where(q => q.Assignments.Any(a => a.RespondDate != null) && q.Review == null);
            else if (status.Equals("Closed", StringComparison.OrdinalIgnoreCase))
                queryable = queryable.Where(q => q.Status == 1 || q.Review != null);
        }

        var count = await queryable.CountAsync(ct);
        var items = await queryable
            .OrderByDescending(q => q.AddedOn)
            .Skip((page - 1) * pageSize)
            .Take(pageSize)
            .ToListAsync(ct);

        var dtos = items.Select(q =>
        {
            var isResponded = q.Assignments.Any(a => a.RespondDate != null);
            var isClosed = q.Status == 1 || q.Review != null;
            var statusText = isClosed ? "Closed" : (isResponded ? "Responded" : (q.Assignments.Any() ? "In Progress" : "Pending"));
            var badgeColor = isClosed ? "green" : (isResponded ? "purple" : (q.Assignments.Any() ? "blue" : "orange"));

            return new RecentQueryItemDto
            {
                Id = q.Id,
                QueryCode = q.QueryCode ?? $"GT-{q.Id:D5}",
                CategoryName = q.Category?.CatName ?? "General",
                Title = q.QueryTitle ?? q.QueryText?.Split('\n').FirstOrDefault() ?? "Query",
                DateFormatted = (q.AddedOn ?? DateTime.UtcNow).ToString("MMM dd, yyyy"),
                Status = statusText,
                StatusBadgeColor = badgeColor
            };
        }).ToList();

        return ApiResponse<PaginatedResult<RecentQueryItemDto>>.Ok(new PaginatedResult<RecentQueryItemDto>(dtos, count, page, pageSize));
    }

    public async Task<ApiResponse<PaginatedResult<AdminRecentQueryItemDto>>> GetAllQueriesAsync(string? status, int? categoryId, string? search, int page, int pageSize, CancellationToken ct = default)
    {
        var queryable = _unitOfWork.Repository<Query>().Query()
            .Include(q => q.User)
            .Include(q => q.Category)
            .Include(q => q.Assignments)
            .AsQueryable();

        if (categoryId.HasValue)
        {
            queryable = queryable.Where(q => q.CategoryId == categoryId.Value);
        }

        if (!string.IsNullOrEmpty(search))
        {
            queryable = queryable.Where(q => (q.QueryTitle != null && q.QueryTitle.Contains(search))
                || (q.QueryCode != null && q.QueryCode.Contains(search))
                || (q.User.Name != null && q.User.Name.Contains(search)));
        }

        if (!string.IsNullOrEmpty(status) && status != "All")
        {
            if (status.Equals("New", StringComparison.OrdinalIgnoreCase))
                queryable = queryable.Where(q => !q.Assignments.Any());
            else if (status.Equals("Respond", StringComparison.OrdinalIgnoreCase))
                queryable = queryable.Where(q => q.Assignments.Any(a => a.RespondDate == null));
            else if (status.Equals("Responded", StringComparison.OrdinalIgnoreCase))
                queryable = queryable.Where(q => q.Assignments.Any(a => a.RespondDate != null) || q.Status == 1);
        }

        var count = await queryable.CountAsync(ct);
        var items = await queryable
            .OrderByDescending(q => q.AddedOn)
            .Skip((page - 1) * pageSize)
            .Take(pageSize)
            .ToListAsync(ct);

        var dtos = items.Select(q =>
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

        return ApiResponse<PaginatedResult<AdminRecentQueryItemDto>>.Ok(new PaginatedResult<AdminRecentQueryItemDto>(dtos, count, page, pageSize));
    }

    public async Task<ApiResponse<QueryDetailDto>> GetQueryDetailAsync(int queryId, int currentUserId, CancellationToken ct = default)
    {
        var query = await _unitOfWork.Repository<Query>().Query()
            .Include(q => q.User)
            .Include(q => q.Category)
            .Include(q => q.Assignments)
                .ThenInclude(a => a.Expert)
            .Include(q => q.Attachments)
            .Include(q => q.Review)
            .FirstOrDefaultAsync(q => q.Id == queryId, ct);

        if (query == null) return ApiResponse<QueryDetailDto>.Fail("Query not found.");

        var assignment = query.Assignments.OrderByDescending(a => a.AddedOn).FirstOrDefault();
        var isAssigned = assignment != null;
        var hasResponded = assignment?.RespondDate != null;
        var isClosed = query.Status == 1 || query.Review != null;

        // Build 4-Step Timeline Stepper (Screen 14)
        var stepper = new List<TimelineStepDto>
        {
            new()
            {
                StepIndex = 1,
                Title = "Submitted",
                Subtitle = (query.AddedOn ?? DateTime.UtcNow).ToString("MMM dd, yyyy hh:mm tt"),
                IsCompleted = true,
                IsCurrent = !isAssigned
            },
            new()
            {
                StepIndex = 2,
                Title = "Assigned to Expert",
                Subtitle = isAssigned ? (assignment!.AddedOn ?? DateTime.UtcNow).ToString("MMM dd, yyyy hh:mm tt") : "Pending assignment",
                IsCompleted = isAssigned,
                IsCurrent = isAssigned && !hasResponded
            },
            new()
            {
                StepIndex = 3,
                Title = "Expert Responded",
                Subtitle = hasResponded ? assignment!.RespondDate!.Value.ToString("MMM dd, yyyy hh:mm tt") : "Awaiting response",
                IsCompleted = hasResponded,
                IsCurrent = hasResponded && !isClosed
            },
            new()
            {
                StepIndex = 4,
                Title = "Closed",
                Subtitle = isClosed ? (query.Review?.CreatedAt.ToString("MMM dd, yyyy") ?? "Completed") : "Pending your review",
                IsCompleted = isClosed,
                IsCurrent = isClosed
            }
        };

        // Attachments
        var attachments = query.Attachments.Select(a => new AttachmentDto
        {
            Id = a.Id,
            FileName = a.FileName,
            FilePath = a.FilePath,
            FileSizeBytes = a.FileSizeBytes,
            FormattedSize = $"{a.FileSizeBytes / (1024.0 * 1024.0):0.1} MB",
            MimeType = a.MimeType,
            IsExpertResponse = a.IsExpertResponse
        }).ToList();

        // Expert Response
        ExpertResponseDto? expertResponseDto = null;
        if (hasResponded && assignment != null)
        {
            var expertDoc = query.Attachments.FirstOrDefault(a => a.IsExpertResponse);
            AttachmentDto? docDto = expertDoc != null ? new AttachmentDto
            {
                Id = expertDoc.Id,
                FileName = expertDoc.FileName,
                FilePath = expertDoc.FilePath,
                FileSizeBytes = expertDoc.FileSizeBytes,
                FormattedSize = $"{expertDoc.FileSizeBytes / (1024.0 * 1024.0):0.1} MB",
                MimeType = expertDoc.MimeType,
                IsExpertResponse = true
            } : null;

            expertResponseDto = new ExpertResponseDto
            {
                ExpertName = assignment.Expert?.Name ?? "Environmental Expert",
                ExpertRole = assignment.Expert?.Occupation ?? "Environmental Expert",
                ExpertAvatarUrl = assignment.Expert?.Image,
                ResponseText = assignment.ExpertAnswer ?? assignment.Answer ?? string.Empty,
                RespondedAt = assignment.RespondDate!.Value,
                RespondedDateFormatted = assignment.RespondDate.Value.ToString("MMM dd, yyyy hh:mm tt"),
                Attachment = docDto
            };
        }

        // Review
        UserReviewDto? reviewDto = null;
        if (query.Review != null)
        {
            reviewDto = new UserReviewDto
            {
                Rating = query.Review.Rating,
                IsResolved = query.Review.IsResolved,
                FeedbackText = query.Review.FeedbackText,
                ReviewedAt = query.Review.CreatedAt
            };
        }

        var dto = new QueryDetailDto
        {
            Id = query.Id,
            QueryCode = query.QueryCode ?? $"GT-{query.Id:D5}",
            CategoryId = query.CategoryId ?? 0,
            CategoryName = query.Category?.CatName ?? "Environmental Clearance",
            Title = query.QueryTitle ?? "Environmental Query",
            Description = query.QueryText ?? string.Empty,
            ProjectType = query.ProjectType ?? "Residential",
            Location = query.Location ?? string.Empty,
            ProjectAreaSqm = query.ProjectAreaSqm ?? 0,
            SubmitterName = query.User?.Name ?? "User",
            SubmitterEmail = query.User?.Email ?? string.Empty,
            Status = isClosed ? "Closed" : (hasResponded ? "Responded" : (isAssigned ? "In Progress" : "Pending")),
            SubmittedDate = query.AddedOn ?? DateTime.UtcNow,
            SubmittedDateFormatted = (query.AddedOn ?? DateTime.UtcNow).ToString("MMM dd, yyyy hh:mm tt"),
            Stepper = stepper,
            Attachments = attachments.Where(a => !a.IsExpertResponse).ToList(),
            ExpertResponse = expertResponseDto,
            Review = reviewDto
        };

        return ApiResponse<QueryDetailDto>.Ok(dto);
    }

    public async Task<ApiResponse<bool>> AssignExpertAsync(int queryId, AssignExpertRequestDto request, CancellationToken ct = default)
    {
        var query = await _unitOfWork.Repository<Query>().GetByIdAsync(queryId, ct);
        if (query == null) return ApiResponse<bool>.Fail("Query not found.");

        var expert = await _unitOfWork.Repository<User>().GetByIdAsync(request.ExpertId, ct);
        if (expert == null) return ApiResponse<bool>.Fail("Expert not found.");

        var assignRepo = _unitOfWork.Repository<QueryAssign>();
        var assignment = new QueryAssign
        {
            QueryId = queryId,
            UserId = request.ExpertId,
            AddedOn = DateTime.UtcNow,
            Status = 0
        };

        await assignRepo.AddAsync(assignment, ct);

        // Notify expert
        await _unitOfWork.Repository<AppNotification>().AddAsync(new AppNotification
        {
            UserId = request.ExpertId,
            Title = "New query assigned",
            Message = $"You have been assigned query {query.QueryCode}: {query.QueryTitle}",
            Type = "query_assigned",
            TargetId = query.QueryCode,
            CreatedAt = DateTime.UtcNow
        }, ct);

        // Notify user
        await _unitOfWork.Repository<AppNotification>().AddAsync(new AppNotification
        {
            UserId = query.UserId,
            Title = "Your query has been assigned",
            Message = $"Your query '{query.QueryTitle}' has been assigned to an expert.",
            Type = "query_assigned",
            TargetId = query.QueryCode,
            CreatedAt = DateTime.UtcNow
        }, ct);

        await _unitOfWork.SaveChangesAsync(ct);
        return ApiResponse<bool>.Ok(true, "Expert assigned successfully.");
    }

    public async Task<ApiResponse<bool>> RespondToQueryAsync(int queryId, int expertId, ExpertResponseRequestDto request, CancellationToken ct = default)
    {
        var assignRepo = _unitOfWork.Repository<QueryAssign>();
        var assignment = await assignRepo.Query()
            .Include(a => a.Query)
            .FirstOrDefaultAsync(a => a.QueryId == queryId && a.UserId == expertId, ct);

        if (assignment == null)
        {
            // Allow admin to respond as fallback
            assignment = await assignRepo.Query()
                .Include(a => a.Query)
                .FirstOrDefaultAsync(a => a.QueryId == queryId, ct);

            if (assignment == null) return ApiResponse<bool>.Fail("Assignment not found.");
        }

        assignment.ExpertAnswer = request.ResponseText;
        assignment.Answer = request.ResponseText;
        assignment.RespondDate = DateTime.UtcNow;
        assignment.Status = 1; // Responded

        if (request.Attachment != null)
        {
            var attRepo = _unitOfWork.Repository<QueryAttachment>();
            await attRepo.AddAsync(new QueryAttachment
            {
                QueryId = queryId,
                UploadedBy = expertId,
                FileName = request.Attachment.FileName,
                FilePath = request.Attachment.FilePath,
                FileSizeBytes = request.Attachment.FileSizeBytes,
                MimeType = request.Attachment.MimeType,
                IsExpertResponse = true,
                CreatedAt = DateTime.UtcNow
            }, ct);
        }

        // Notify user
        if (assignment.Query != null)
        {
            await _unitOfWork.Repository<AppNotification>().AddAsync(new AppNotification
            {
                UserId = assignment.Query.UserId,
                Title = "Expert responded to your query",
                Message = $"An expert has responded to your query: {assignment.Query.QueryTitle}",
                Type = "query_response",
                TargetId = assignment.Query.QueryCode,
                CreatedAt = DateTime.UtcNow
            }, ct);
        }

        await _unitOfWork.SaveChangesAsync(ct);
        return ApiResponse<bool>.Ok(true, "Response submitted successfully.");
    }

    public async Task<ApiResponse<bool>> ReviewQueryAsync(int queryId, int userId, SubmitReviewRequestDto request, CancellationToken ct = default)
    {
        var query = await _unitOfWork.Repository<Query>().GetByIdAsync(queryId, ct);
        if (query == null) return ApiResponse<bool>.Fail("Query not found.");
        if (query.UserId != userId) return ApiResponse<bool>.Fail("Unauthorized.");

        var reviewRepo = _unitOfWork.Repository<QueryReview>();
        var review = new QueryReview
        {
            QueryId = queryId,
            UserId = userId,
            Rating = request.Rating,
            IsResolved = request.IsResolved,
            FeedbackText = request.FeedbackText,
            CreatedAt = DateTime.UtcNow
        };

        await reviewRepo.AddAsync(review, ct);

        // Mark query completed
        query.Status = 1;
        query.ModifiedOn = DateTime.UtcNow;

        await _unitOfWork.SaveChangesAsync(ct);
        return ApiResponse<bool>.Ok(true, "Review submitted successfully.");
    }
}
