namespace GreenTIP.Domain.Entities;

public class BulkEmailLog
{
    public int Id { get; set; }
    public string Subject { get; set; } = string.Empty;
    public string Message { get; set; } = string.Empty;
    public string RecipientGroup { get; set; } = "All Users"; // "All Users", "Experts", "Selected Users", "Custom"
    public int TotalRecipients { get; set; }
    public int SuccessfulDeliveries { get; set; }
    public int FailedDeliveries { get; set; }
    public string Status { get; set; } = "Sent"; // "Sent", "Failed", "Pending"
    public string? AttachmentFileName { get; set; }
    public string? AttachmentFilePath { get; set; }
    public DateTime SentAt { get; set; } = DateTime.UtcNow;
}
