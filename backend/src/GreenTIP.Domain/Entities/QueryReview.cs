namespace GreenTIP.Domain.Entities;

public class QueryReview
{
    public int Id { get; set; }
    public int QueryId { get; set; }
    public int UserId { get; set; }
    public int Rating { get; set; } // 1 to 5 stars
    public string IsResolved { get; set; } = "Yes"; // "Yes", "Partially", "No"
    public string? FeedbackText { get; set; }
    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    // Navigation
    public Query Query { get; set; } = null!;
    public User User { get; set; } = null!;
}
