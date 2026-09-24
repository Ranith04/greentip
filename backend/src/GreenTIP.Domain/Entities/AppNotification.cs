namespace GreenTIP.Domain.Entities;

public class AppNotification
{
    public int Id { get; set; }
    public int UserId { get; set; } // Target user or 0 for all admins
    public string Title { get; set; } = string.Empty;
    public string Message { get; set; } = string.Empty;
    public string Type { get; set; } = "info"; // "query_response", "query_assigned", "new_query", "new_user", "system"
    public string? TargetId { get; set; } // e.g. Query ID or Article ID
    public bool IsRead { get; set; } = false;
    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    // Navigation
    public User? User { get; set; }
}
