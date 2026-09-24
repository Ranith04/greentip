namespace GreenTIP.Application.DTOs.Dashboard;

public class UserDashboardDto
{
    public string GreetingName { get; set; } = string.Empty;
    public int UnreadNotificationsCount { get; set; }
    public int TotalQueries { get; set; }
    public int PendingQueries { get; set; }
    public int AnsweredQueries { get; set; }
    public List<RecentQueryItemDto> RecentQueries { get; set; } = new();
}

public class AdminDashboardDto
{
    public string GreetingName { get; set; } = string.Empty;
    public string AdminRole { get; set; } = "Administrator";
    public int UnreadNotificationsCount { get; set; }
    
    // 5 KPI Matrix
    public int NewQueriesCount { get; set; }
    public int RespondQueriesCount { get; set; }
    public int RespondedQueriesCount { get; set; }
    public int TotalUsersCount { get; set; }
    public int TotalExpertsCount { get; set; }

    public List<AdminRecentQueryItemDto> RecentQueries { get; set; } = new();
}

public class RecentQueryItemDto
{
    public int Id { get; set; }
    public string QueryCode { get; set; } = string.Empty;
    public string CategoryName { get; set; } = string.Empty;
    public string Title { get; set; } = string.Empty;
    public string DateFormatted { get; set; } = string.Empty; // e.g. "Sep 12, 2026"
    public string Status { get; set; } = "Pending"; // "Responded", "Pending", "In Progress", "Closed"
    public string StatusBadgeColor { get; set; } = "purple"; // "purple", "orange", "blue", "green"
}

public class AdminRecentQueryItemDto
{
    public int Id { get; set; }
    public string QueryCode { get; set; } = string.Empty;
    public string Title { get; set; } = string.Empty;
    public string SubmitterName { get; set; } = string.Empty;
    public string DateFormatted { get; set; } = string.Empty;
    public string CategoryName { get; set; } = string.Empty;
    public string StatusBadge { get; set; } = "New"; // "New", "Respond", "Responded"
}
