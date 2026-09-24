using GreenTIP.Domain.Enums;

namespace GreenTIP.Domain.Entities;

public class QueryAssign
{
    public int Id { get; set; }
    public int QueryId { get; set; }
    public int UserId { get; set; } // Expert Id
    public string? ExpertAnswer { get; set; }
    public string? Answer { get; set; }
    public DateTime? AddedOn { get; set; } = DateTime.UtcNow;
    public DateTime? RespondDate { get; set; }
    public DateTime? AdminReviewDate { get; set; }
    public int Status { get; set; } = 0; // 0=pending, 1=respond, 2=rejected
    public int ReviewStatus { get; set; } = 0; // 0=pending, 1=reviewed

    // Helper status enum
    public AssignmentStatus AssignmentStatus => (AssignmentStatus)Status;

    // Navigation
    public Query Query { get; set; } = null!;
    public User Expert { get; set; } = null!;
}
