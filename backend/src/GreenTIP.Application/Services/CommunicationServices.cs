using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Email;
using GreenTIP.Application.DTOs.Knowledge;
using GreenTIP.Application.Interfaces;
using GreenTIP.Domain.Entities;
using GreenTIP.Domain.Repositories;
using Microsoft.EntityFrameworkCore;

namespace GreenTIP.Application.Services;

public class BulkEmailService : IBulkEmailService
{
    private readonly IUnitOfWork _unitOfWork;
    private readonly IEmailService _emailService;

    public BulkEmailService(IUnitOfWork unitOfWork, IEmailService emailService)
    {
        _unitOfWork = unitOfWork;
        _emailService = emailService;
    }

    public async Task<ApiResponse<EmailPreviewDto>> PreviewEmailAsync(ComposeEmailDto request, CancellationToken ct = default)
    {
        int recipientCount = 0;
        if (request.RecipientGroup == "All Users")
        {
            recipientCount = await _unitOfWork.Repository<User>().Query().CountAsync(u => u.RoleType == "2", ct);
        }
        else if (request.RecipientGroup == "Experts")
        {
            recipientCount = await _unitOfWork.Repository<User>().Query().CountAsync(u => u.RoleType == "1", ct);
        }
        else if (request.RecipientGroup == "Custom" && request.CustomRecipients != null)
        {
            recipientCount = request.CustomRecipients.Count;
        }
        else
        {
            recipientCount = 125; // Default fallback
        }

        var preview = new EmailPreviewDto
        {
            RecipientGroupSummary = $"{request.RecipientGroup} ({recipientCount})",
            RecipientCount = recipientCount,
            Subject = request.Subject,
            Message = request.Message,
            AttachmentFileName = request.AttachmentFileName
        };

        return ApiResponse<EmailPreviewDto>.Ok(preview);
    }

    public async Task<ApiResponse<bool>> SendBulkEmailAsync(ComposeEmailDto request, CancellationToken ct = default)
    {
        var recipients = new List<string>();

        if (request.RecipientGroup == "All Users")
        {
            recipients = await _unitOfWork.Repository<User>().Query()
                .Where(u => u.RoleType == "2" && !string.IsNullOrEmpty(u.Email))
                .Select(u => u.Email!)
                .ToListAsync(ct);
        }
        else if (request.RecipientGroup == "Experts")
        {
            recipients = await _unitOfWork.Repository<User>().Query()
                .Where(u => u.RoleType == "1" && !string.IsNullOrEmpty(u.Email))
                .Select(u => u.Email!)
                .ToListAsync(ct);
        }
        else if (request.CustomRecipients != null)
        {
            recipients = request.CustomRecipients;
        }

        if (!recipients.Any())
        {
            recipients.Add("demo@greentip.com"); // Dev test
        }

        var (success, failed) = await _emailService.SendBulkEmailAsync(recipients, request.Subject, request.Message, request.AttachmentFilePath);

        var log = new BulkEmailLog
        {
            Subject = request.Subject,
            Message = request.Message,
            RecipientGroup = request.RecipientGroup,
            TotalRecipients = recipients.Count,
            SuccessfulDeliveries = success,
            FailedDeliveries = failed,
            Status = failed == 0 ? "Sent" : (success > 0 ? "Sent" : "Failed"),
            AttachmentFileName = request.AttachmentFileName,
            AttachmentFilePath = request.AttachmentFilePath,
            SentAt = DateTime.UtcNow
        };

        await _unitOfWork.Repository<BulkEmailLog>().AddAsync(log, ct);
        await _unitOfWork.SaveChangesAsync(ct);

        return ApiResponse<bool>.Ok(true, $"Dispatched email to {success} recipients ({failed} failed).");
    }

    public async Task<ApiResponse<PaginatedResult<BulkEmailLogDto>>> GetLogsAsync(string? search, int page, int pageSize, CancellationToken ct = default)
    {
        var queryable = _unitOfWork.Repository<BulkEmailLog>().Query();

        if (!string.IsNullOrEmpty(search))
        {
            queryable = queryable.Where(l => l.Subject.Contains(search) || l.RecipientGroup.Contains(search));
        }

        var total = await queryable.CountAsync(ct);
        var logs = await queryable
            .OrderByDescending(l => l.SentAt)
            .Skip((page - 1) * pageSize)
            .Take(pageSize)
            .ToListAsync(ct);

        var dtos = logs.Select(l => new BulkEmailLogDto
        {
            Id = l.Id,
            Subject = l.Subject,
            RecipientGroup = l.RecipientGroup,
            SentDateFormatted = l.SentAt.ToString("MMM dd, yyyy"),
            Status = l.Status,
            TotalRecipients = l.TotalRecipients,
            SuccessfulDeliveries = l.SuccessfulDeliveries,
            FailedDeliveries = l.FailedDeliveries,
            AttachmentFileName = l.AttachmentFileName
        }).ToList();

        return ApiResponse<PaginatedResult<BulkEmailLogDto>>.Ok(new PaginatedResult<BulkEmailLogDto>(dtos, total, page, pageSize));
    }

    public async Task<ApiResponse<BulkEmailLogDto>> GetLogDetailAsync(int id, CancellationToken ct = default)
    {
        var log = await _unitOfWork.Repository<BulkEmailLog>().GetByIdAsync(id, ct);
        if (log == null) return ApiResponse<BulkEmailLogDto>.Fail("Log not found.");

        var dto = new BulkEmailLogDto
        {
            Id = log.Id,
            Subject = log.Subject,
            RecipientGroup = log.RecipientGroup,
            SentDateFormatted = log.SentAt.ToString("MMM dd, yyyy hh:mm tt"),
            Status = log.Status,
            TotalRecipients = log.TotalRecipients,
            SuccessfulDeliveries = log.SuccessfulDeliveries,
            FailedDeliveries = log.FailedDeliveries,
            AttachmentFileName = log.AttachmentFileName
        };

        return ApiResponse<BulkEmailLogDto>.Ok(dto);
    }
}

public class KnowledgeService : IKnowledgeService
{
    private readonly IUnitOfWork _unitOfWork;

    public KnowledgeService(IUnitOfWork unitOfWork)
    {
        _unitOfWork = unitOfWork;
    }

    public async Task<ApiResponse<List<CategoryDto>>> GetCategoriesAsync(CancellationToken ct = default)
    {
        var categories = await _unitOfWork.Repository<Category>().Query()
            .Where(c => c.Status == "1")
            .ToListAsync(ct);

        var dtos = categories.Select(c => new CategoryDto
        {
            Id = c.Id,
            Name = c.CatName,
            Alias = c.Alias,
            IconKey = c.Alias
        }).ToList();

        return ApiResponse<List<CategoryDto>>.Ok(dtos);
    }

    public async Task<ApiResponse<List<ArticleSummaryDto>>> GetArticlesAsync(int? categoryId, string? search, CancellationToken ct = default)
    {
        var pages = await _unitOfWork.Repository<Page>().Query().ToListAsync(ct);

        var dtos = pages.Select(p => new ArticleSummaryDto
        {
            Id = p.Id,
            Title = p.Title ?? "Environmental Regulatory Guidelines",
            CategoryName = "Environmental Clearance",
            PublishedDateFormatted = (p.AddedOn ?? DateTime.UtcNow).ToString("MMM dd, yyyy"),
            ThumbnailUrl = "/assets/knowledge-center.png",
            ReadDuration = "5 min read"
        }).ToList();

        return ApiResponse<List<ArticleSummaryDto>>.Ok(dtos);
    }

    public async Task<ApiResponse<ArticleDetailDto>> GetArticleDetailAsync(int id, CancellationToken ct = default)
    {
        var page = await _unitOfWork.Repository<Page>().GetByIdAsync(id, ct);
        if (page == null) return ApiResponse<ArticleDetailDto>.Fail("Article not found.");

        var dto = new ArticleDetailDto
        {
            Id = page.Id,
            Title = page.Title ?? "Understanding Environmental Clearance Requirements",
            CategoryName = "Environmental Clearance",
            PublishedDateFormatted = (page.AddedOn ?? DateTime.UtcNow).ToString("MMM dd, yyyy"),
            HeroImageUrl = "/assets/knowledge-center.png",
            ContentHtml = page.Content ?? "<p>Detailed environmental guidelines...</p>",
            RelatedArticles = new List<ArticleSummaryDto>()
        };

        return ApiResponse<ArticleDetailDto>.Ok(dto);
    }
}

public class NotificationService : INotificationService
{
    private readonly IUnitOfWork _unitOfWork;

    public NotificationService(IUnitOfWork unitOfWork)
    {
        _unitOfWork = unitOfWork;
    }

    public async Task<ApiResponse<List<AppNotificationDto>>> GetNotificationsAsync(int userId, bool isAdmin, CancellationToken ct = default)
    {
        var queryable = _unitOfWork.Repository<AppNotification>().Query();

        if (isAdmin)
        {
            queryable = queryable.Where(n => n.UserId == 0 || n.UserId == userId);
        }
        else
        {
            queryable = queryable.Where(n => n.UserId == userId);
        }

        var notifications = await queryable
            .OrderByDescending(n => n.CreatedAt)
            .Take(20)
            .ToListAsync(ct);

        var dtos = notifications.Select(n =>
        {
            var diff = DateTime.UtcNow - n.CreatedAt;
            var timeAgo = diff.TotalMinutes < 60 ? $"{(int)diff.TotalMinutes} minutes ago"
                : (diff.TotalHours < 24 ? $"{(int)diff.TotalHours} hours ago" : $"{(int)diff.TotalDays} days ago");

            return new AppNotificationDto
            {
                Id = n.Id,
                Title = n.Title,
                Message = n.Message,
                Type = n.Type,
                TimeAgo = timeAgo,
                IsRead = n.IsRead,
                TargetId = n.TargetId
            };
        }).ToList();

        return ApiResponse<List<AppNotificationDto>>.Ok(dtos);
    }

    public async Task<ApiResponse<bool>> MarkAllAsReadAsync(int userId, bool isAdmin, CancellationToken ct = default)
    {
        var notifRepo = _unitOfWork.Repository<AppNotification>();
        var notifs = await notifRepo.Query()
            .Where(n => (isAdmin ? (n.UserId == 0 || n.UserId == userId) : n.UserId == userId) && !n.IsRead)
            .ToListAsync(ct);

        foreach (var n in notifs)
        {
            n.IsRead = true;
        }

        await _unitOfWork.SaveChangesAsync(ct);
        return ApiResponse<bool>.Ok(true, "Marked all as read.");
    }
}
