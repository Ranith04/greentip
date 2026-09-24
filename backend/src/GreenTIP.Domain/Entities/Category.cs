namespace GreenTIP.Domain.Entities;

public class Category
{
    public int Id { get; set; }
    public string CatName { get; set; } = string.Empty;
    public string Alias { get; set; } = string.Empty;
    public string Status { get; set; } = "1";
    public DateTime AddedOn { get; set; } = DateTime.UtcNow;

    // Navigation
    public ICollection<Query> Queries { get; set; } = new List<Query>();
}
