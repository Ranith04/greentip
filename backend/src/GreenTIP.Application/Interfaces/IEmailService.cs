namespace GreenTIP.Application.Interfaces;

public interface IEmailService
{
    Task<bool> SendEmailAsync(string toEmail, string toName, string subject, string htmlContent, string? attachmentPath = null);
    Task<bool> SendOtpEmailAsync(string toEmail, string toName, string otpCode);
    Task<(int successCount, int failedCount)> SendBulkEmailAsync(IEnumerable<string> recipients, string subject, string message, string? attachmentPath = null);
}
