namespace GreenTIP.Domain.Entities;

public class Faq
{
    public int Id { get; set; }
    public string Question { get; set; } = string.Empty;
    public string Answer { get; set; } = string.Empty;
    public string Status { get; set; } = "1"; // '1'=Active
    public DateTime AddedOn { get; set; } = DateTime.UtcNow;
}
