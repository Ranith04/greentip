using GreenTIP.Domain.Enums;

namespace GreenTIP.Domain.Entities;

public class User
{
    public int Id { get; set; }
    public string RoleType { get; set; } = "2"; // '0'=Admin, '1'=Expert, '2'=EndUser, '3'=MD
    public string? Name { get; set; }
    public string? Email { get; set; }
    public string? Password { get; set; }
    public string? Image { get; set; }
    public long? ContactNo { get; set; }
    public string? Description { get; set; }
    public string? ActivationCode { get; set; }
    public string? Linkedin { get; set; }
    public string? Facebook { get; set; }
    public string Status { get; set; } = "1"; // '0'=Inactive, '1'=Active, '2'=Blocked, '3'=Deleted
    public DateTime? CreatedOn { get; set; } = DateTime.UtcNow;
    public DateTime UpdatedOn { get; set; } = DateTime.UtcNow;
    public string? IpAddress { get; set; }
    public string? Token { get; set; }
    public DateTime? LastLogin { get; set; }
    public string? Username { get; set; }
    public string CompanyName { get; set; } = string.Empty;
    public string Occupation { get; set; } = string.Empty;
    public string EducationQualification { get; set; } = string.Empty;
    public string ExpertiseField { get; set; } = string.Empty;

    // Helper property to work with typed UserRole
    public UserRole Role => RoleType switch
    {
        "0" => UserRole.SuperAdmin,
        "1" => UserRole.Expert,
        "2" => UserRole.EndUser,
        "3" => UserRole.ManagingDirector,
        _ => UserRole.EndUser
    };

    // Navigation Properties
    public ICollection<Query> SubmittedQueries { get; set; } = new List<Query>();
    public ICollection<QueryAssign> AssignedQueries { get; set; } = new List<QueryAssign>();
    public ICollection<QueryReview> Reviews { get; set; } = new List<QueryReview>();
}
