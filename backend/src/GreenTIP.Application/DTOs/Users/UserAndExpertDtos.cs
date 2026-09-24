namespace GreenTIP.Application.DTOs.Users;

public class UserListDto
{
    public int Id { get; set; }
    public string Name { get; set; } = string.Empty;
    public string Email { get; set; } = string.Empty;
    public int TotalQueries { get; set; }
    public string Status { get; set; } = "Active"; // "Active", "Inactive"
}

public class UserDetailDto
{
    public int Id { get; set; }
    public string Name { get; set; } = string.Empty;
    public string Role { get; set; } = "End User";
    public string Email { get; set; } = string.Empty;
    public string Mobile { get; set; } = string.Empty;
    public string Organization { get; set; } = string.Empty;
    public string Status { get; set; } = "Active";
    public int TotalQueries { get; set; }
    public int PendingQueries { get; set; }
    public int AnsweredQueries { get; set; }
}

public class UpdateProfileDto
{
    public string FullName { get; set; } = string.Empty;
    public string? Email { get; set; }
    public string? Mobile { get; set; }
    public string? Designation { get; set; }
    public string? Organization { get; set; }
    public string? AvatarUrl { get; set; }
}

public class ChangePasswordDto
{
    public string CurrentPassword { get; set; } = string.Empty;
    public string NewPassword { get; set; } = string.Empty;
    public string ConfirmNewPassword { get; set; } = string.Empty;
}

public class ExpertListDto
{
    public int Id { get; set; }
    public string Name { get; set; } = string.Empty;
    public string Designation { get; set; } = string.Empty; // e.g. "Environmental Expert", "Enviro-Legal Expert"
    public string CategorySpecialty { get; set; } = string.Empty;
    public string Status { get; set; } = "Active";
    public string? AvatarUrl { get; set; }
    public int AssignedQueriesCount { get; set; }
    public int AnsweredQueriesCount { get; set; }
    public int PendingQueriesCount { get; set; }
}

public class ExpertDetailDto
{
    public int Id { get; set; }
    public string Name { get; set; } = string.Empty;
    public string Designation { get; set; } = "Environmental Expert";
    public string Email { get; set; } = string.Empty;
    public string Mobile { get; set; } = string.Empty;
    public string Status { get; set; } = "Active";
    public string? AvatarUrl { get; set; }
    public List<string> ExpertiseCategories { get; set; } = new(); // ["Environmental Clearance", "Enviro-Legal", "CRZ"]
    
    // Workload stats
    public int AssignedCategoryCount { get; set; } = 12;
    public int AnsweredQueriesCount { get; set; } = 10;
    public int PendingQueriesCount { get; set; } = 2;
}

public class CreateExpertDto
{
    public string FullName { get; set; } = string.Empty;
    public string Email { get; set; } = string.Empty;
    public string Mobile { get; set; } = string.Empty;
    public string ExpertiseCategory { get; set; } = string.Empty;
    public string Designation { get; set; } = string.Empty;
    public string? PhotoUrl { get; set; }
}
