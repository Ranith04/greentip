namespace GreenTIP.Domain.Entities;

public class Setting
{
    public int Id { get; set; }
    public string OptionName { get; set; } = string.Empty;
    public string OptionValue { get; set; } = string.Empty;
}
