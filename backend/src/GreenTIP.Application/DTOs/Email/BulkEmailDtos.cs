namespace GreenTIP.Application.DTOs.Email;

public class ComposeEmailDto
{
    public string RecipientGroup { get; set; } = "All Users"; // "All Users", "Experts", "Selected Users", "Custom"
    public List<string>? CustomRecipients { get; set; }
    public string Subject { get; set; } = string.Empty;
    public string Message { get; set; } = string.Empty;
    public string? AttachmentFileName { get; set; }
    public string? AttachmentFilePath { get; set; }
}

public class EmailPreviewDto
{
    public string RecipientGroupSummary { get; set; } = "All Users (125)";
    public int RecipientCount { get; set; } = 125;
    public string Subject { get; set; } = string.Empty;
    public string Message { get; set; } = string.Empty;
    public string? AttachmentFileName { get; set; }
}

public class BulkEmailLogDto
{
    public int Id { get; set; }
    public string Subject { get; set; } = string.Empty;
    public string RecipientGroup { get; set; } = "All Users";
    public string SentDateFormatted { get; set; } = string.Empty;
    public string Status { get; set; } = "Sent"; // "Sent", "Failed"
    public int TotalRecipients { get; set; }
    public int SuccessfulDeliveries { get; set; }
    public int FailedDeliveries { get; set; }
    public string? AttachmentFileName { get; set; }
}
