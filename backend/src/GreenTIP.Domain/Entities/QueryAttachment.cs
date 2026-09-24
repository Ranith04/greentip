namespace GreenTIP.Domain.Entities;

public class QueryAttachment
{
    public int Id { get; set; }
    public int QueryId { get; set; }
    public int UploadedBy { get; set; }
    public string FileName { get; set; } = string.Empty;
    public string FilePath { get; set; } = string.Empty;
    public long FileSizeBytes { get; set; }
    public string MimeType { get; set; } = string.Empty;
    public bool IsExpertResponse { get; set; } = false;
    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    // Navigation
    public Query Query { get; set; } = null!;
    public User Uploader { get; set; } = null!;
}
