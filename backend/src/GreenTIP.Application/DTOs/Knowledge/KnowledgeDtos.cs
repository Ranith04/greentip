namespace GreenTIP.Application.DTOs.Knowledge;

public class CategoryDto
{
    public int Id { get; set; }
    public string Name { get; set; } = string.Empty;
    public string Alias { get; set; } = string.Empty;
    public string IconKey { get; set; } = string.Empty;
}

public class ArticleSummaryDto
{
    public int Id { get; set; }
    public string Title { get; set; } = string.Empty;
    public string CategoryName { get; set; } = string.Empty;
    public string PublishedDateFormatted { get; set; } = string.Empty;
    public string? ThumbnailUrl { get; set; }
    public string ReadDuration { get; set; } = "5 min read";
}

public class ArticleDetailDto
{
    public int Id { get; set; }
    public string Title { get; set; } = string.Empty;
    public string CategoryName { get; set; } = string.Empty;
    public string PublishedDateFormatted { get; set; } = string.Empty;
    public string? HeroImageUrl { get; set; }
    public string ContentHtml { get; set; } = string.Empty;
    public List<ArticleSummaryDto> RelatedArticles { get; set; } = new();
}

public class AppNotificationDto
{
    public int Id { get; set; }
    public string Title { get; set; } = string.Empty;
    public string Message { get; set; } = string.Empty;
    public string Type { get; set; } = "info"; // "query_response", "query_assigned", "new_article", "status_update", "welcome"
    public string TimeAgo { get; set; } = string.Empty; // e.g. "10 minutes ago"
    public bool IsRead { get; set; }
    public string? TargetId { get; set; }
}
