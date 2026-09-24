namespace GreenTIP.Application.DTOs.Queries;

public class SubmitQueryRequestDto
{
    public int CategoryId { get; set; }
    public string Title { get; set; } = string.Empty;
    public string Description { get; set; } = string.Empty;
    public string ProjectType { get; set; } = "Residential"; // Residential, Commercial, Industrial, Mining, Township, etc.
    public string Location { get; set; } = string.Empty; // e.g. "Hyderabad, Telangana"
    public double ProjectAreaSqm { get; set; }
    public List<AttachmentPayloadDto>? Attachments { get; set; }
}

public class AttachmentPayloadDto
{
    public string FileName { get; set; } = string.Empty;
    public string FilePath { get; set; } = string.Empty;
    public long FileSizeBytes { get; set; }
    public string MimeType { get; set; } = string.Empty;
}

public class QueryDetailDto
{
    public int Id { get; set; }
    public string QueryCode { get; set; } = string.Empty; // "GT-2026-00125"
    public int CategoryId { get; set; }
    public string CategoryName { get; set; } = string.Empty;
    public string Title { get; set; } = string.Empty;
    public string Description { get; set; } = string.Empty;
    public string ProjectType { get; set; } = string.Empty;
    public string Location { get; set; } = string.Empty;
    public double ProjectAreaSqm { get; set; }
    public string SubmitterName { get; set; } = string.Empty;
    public string SubmitterEmail { get; set; } = string.Empty;
    public string Status { get; set; } = "Pending";
    public DateTime SubmittedDate { get; set; }
    public string SubmittedDateFormatted { get; set; } = string.Empty;

    // 4-Step Timeline Stepper (Screen 14)
    public List<TimelineStepDto> Stepper { get; set; } = new();

    // Attachments
    public List<AttachmentDto> Attachments { get; set; } = new();

    // Expert Response (Screen 15)
    public ExpertResponseDto? ExpertResponse { get; set; }

    // User Review (Screen 16)
    public UserReviewDto? Review { get; set; }
}

public class TimelineStepDto
{
    public int StepIndex { get; set; } // 1, 2, 3, 4
    public string Title { get; set; } = string.Empty; // "Submitted", "Assigned to Expert", "Expert Responded", "Closed"
    public string Subtitle { get; set; } = string.Empty; // "Sep 12, 2026 10:45 AM", "Pending your review"
    public bool IsCompleted { get; set; }
    public bool IsCurrent { get; set; }
}

public class AttachmentDto
{
    public int Id { get; set; }
    public string FileName { get; set; } = string.Empty;
    public string FilePath { get; set; } = string.Empty;
    public long FileSizeBytes { get; set; }
    public string FormattedSize { get; set; } = string.Empty; // e.g. "1.1 MB"
    public string MimeType { get; set; } = string.Empty;
    public bool IsExpertResponse { get; set; }
}

public class ExpertResponseDto
{
    public string ExpertName { get; set; } = string.Empty;
    public string ExpertRole { get; set; } = "Environmental Expert";
    public string? ExpertAvatarUrl { get; set; }
    public string ResponseText { get; set; } = string.Empty;
    public DateTime RespondedAt { get; set; }
    public string RespondedDateFormatted { get; set; } = string.Empty;
    public AttachmentDto? Attachment { get; set; }
}

public class UserReviewDto
{
    public int Rating { get; set; } // 1 to 5
    public string IsResolved { get; set; } = "Yes"; // Yes, Partially, No
    public string? FeedbackText { get; set; }
    public DateTime ReviewedAt { get; set; }
}

public class AssignExpertRequestDto
{
    public int ExpertId { get; set; }
    public string? Note { get; set; } // e.g. "Assigning based on project location and category."
}

public class ExpertResponseRequestDto
{
    public string ResponseText { get; set; } = string.Empty;
    public AttachmentPayloadDto? Attachment { get; set; }
}

public class SubmitReviewRequestDto
{
    public int Rating { get; set; } // 1 to 5 stars
    public string IsResolved { get; set; } = "Yes"; // "Yes", "Partially", "No"
    public string? FeedbackText { get; set; }
}
