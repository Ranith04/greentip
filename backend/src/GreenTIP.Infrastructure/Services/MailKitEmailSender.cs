using GreenTIP.Application.Interfaces;
using MailKit.Net.Smtp;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.Logging;
using MimeKit;

namespace GreenTIP.Infrastructure.Services;

public class MailKitEmailSender : IEmailService
{
    private readonly IConfiguration _configuration;
    private readonly ILogger<MailKitEmailSender> _logger;

    public MailKitEmailSender(IConfiguration configuration, ILogger<MailKitEmailSender> logger)
    {
        _configuration = configuration;
        _logger = logger;
    }

    public async Task<bool> SendEmailAsync(string toEmail, string toName, string subject, string htmlContent, string? attachmentPath = null)
    {
        try
        {
            var host = _configuration["Smtp:Host"];
            var portStr = _configuration["Smtp:Port"];
            var username = _configuration["Smtp:Username"];
            var password = _configuration["Smtp:Password"];
            var fromEmail = _configuration["Smtp:FromEmail"] ?? "no-reply@grcgreentip.com";
            var fromName = _configuration["Smtp:FromName"] ?? "GRC GreenTIP";

            // If SMTP is not configured, log email to console (dev fallback)
            if (string.IsNullOrEmpty(host) || host == "smtp.example.com")
            {
                _logger.LogInformation("[DEV EMAIL DISPATCH] To: {ToName} <{ToEmail}> | Subject: {Subject} | Content preview: {Preview}",
                    toName, toEmail, subject, htmlContent.Length > 100 ? htmlContent[..100] + "..." : htmlContent);
                return true;
            }

            var message = new MimeMessage();
            message.From.Add(new MailboxAddress(fromName, fromEmail));
            message.To.Add(new MailboxAddress(toName, toEmail));
            message.Subject = subject;

            var builder = new BodyBuilder
            {
                HtmlBody = htmlContent
            };

            if (!string.IsNullOrEmpty(attachmentPath) && File.Exists(attachmentPath))
            {
                await builder.Attachments.AddAsync(attachmentPath);
            }

            message.Body = builder.ToMessageBody();

            using var client = new SmtpClient();
            var port = int.TryParse(portStr, out var p) ? p : 587;
            await client.ConnectAsync(host, port, MailKit.Security.SecureSocketOptions.StartTls);

            if (!string.IsNullOrEmpty(username) && !string.IsNullOrEmpty(password))
            {
                await client.AuthenticateAsync(username, password);
            }

            await client.SendAsync(message);
            await client.DisconnectAsync(true);

            return true;
        }
        catch (Exception ex)
        {
            _logger.LogError(ex, "Failed to send email to {ToEmail}", toEmail);
            return false;
        }
    }

    public async Task<bool> SendOtpEmailAsync(string toEmail, string toName, string otpCode)
    {
        var subject = "GRC GreenTIP: Account Verification Code";
        var htmlContent = $@"
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #E2E8F0; border-radius: 12px;'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <h2 style='color: #2E7D32; margin: 0;'>GRC GreenTIP</h2>
                    <p style='color: #64748B; font-size: 14px;'>Technical Interactive Platform</p>
                </div>
                <p>Dear <strong>{toName}</strong>,</p>
                <p>Thank you for registering on GreenTIP. Please use the following 6-digit verification code to activate your account:</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <span style='font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #2E7D32; background: #E8F5E9; padding: 12px 24px; border-radius: 8px; border: 1px dashed #2E7D32;'>{otpCode}</span>
                </div>
                <p style='font-size: 13px; color: #64748B;'>This code will expire in 10 minutes. If you did not request this, please ignore this email.</p>
                <hr style='border: none; border-top: 1px solid #E2E8F0; margin: 20px 0;'/>
                <p style='font-size: 12px; color: #94A3B8; text-align: center;'>&copy; {DateTime.UtcNow.Year} GRC India. All rights reserved.</p>
            </div>";

        return await SendEmailAsync(toEmail, toName, subject, htmlContent);
    }

    public async Task<(int successCount, int failedCount)> SendBulkEmailAsync(IEnumerable<string> recipients, string subject, string message, string? attachmentPath = null)
    {
        int success = 0;
        int failed = 0;

        foreach (var recipient in recipients)
        {
            var html = $@"
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <h3 style='color: #2E7D32;'>GRC GreenTIP Announcement</h3>
                    <div style='font-size: 15px; line-height: 1.6; color: #1E293B;'>{message.Replace("\n", "<br/>")}</div>
                    <p style='margin-top: 30px; font-size: 13px; color: #64748B;'>Regards,<br/><strong>GRC GreenTIP Team</strong></p>
                </div>";

            var result = await SendEmailAsync(recipient, recipient, subject, html, attachmentPath);
            if (result) success++;
            else failed++;
        }

        return (success, failed);
    }
}
