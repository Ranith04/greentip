namespace GreenTIP.Domain.Entities;

public class Page
{
    public int Id { get; set; }
    public string? Title { get; set; }
    public string Alias { get; set; } = string.Empty;
    public string? Content { get; set; }
    public DateTime? AddedOn { get; set; }
}
