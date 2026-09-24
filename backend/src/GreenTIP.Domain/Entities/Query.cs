using GreenTIP.Domain.Enums;

namespace GreenTIP.Domain.Entities;

public class Query
{
    public int Id { get; set; }
    public int UserId { get; set; }
    public int? CategoryId { get; set; }
    public string? QueryText { get; set; } // Maps to column `query`
    public int Status { get; set; } = 0; // 0=pending, 1=completed, 2=rejected
    public DateTime? AddedOn { get; set; } = DateTime.UtcNow;
    public DateTime? ModifiedOn { get; set; } = DateTime.UtcNow;

    // Structured fields for mobile app (additive, non-destructive)
    public string? QueryCode { get; set; } // e.g. "GT-2026-00125"
    public string? QueryTitle { get; set; }
    public string? ProjectType { get; set; }
    public string? Location { get; set; }
    public double? ProjectAreaSqm { get; set; }

    // Helper status enum
    public QueryStatus QueryStatus => (QueryStatus)Status;

    // Navigation
    public User User { get; set; } = null!;
    public Category? Category { get; set; }
    public ICollection<QueryAssign> Assignments { get; set; } = new List<QueryAssign>();
    public ICollection<QueryAttachment> Attachments { get; set; } = new List<QueryAttachment>();
    public QueryReview? Review { get; set; }
}
