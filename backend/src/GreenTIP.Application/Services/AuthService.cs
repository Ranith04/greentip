using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Auth;
using GreenTIP.Application.Interfaces;
using GreenTIP.Domain.Entities;
using GreenTIP.Domain.Enums;
using GreenTIP.Domain.Repositories;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;

namespace GreenTIP.Application.Services;

public class AuthService : IAuthService
{
    private readonly IUnitOfWork _unitOfWork;
    private readonly IPasswordHasher _passwordHasher;
    private readonly IJwtTokenGenerator _jwtGenerator;
    private readonly IEmailService _emailService;
    private readonly ILogger<AuthService> _logger;

    public AuthService(
        IUnitOfWork unitOfWork,
        IPasswordHasher passwordHasher,
        IJwtTokenGenerator jwtGenerator,
        IEmailService emailService,
        ILogger<AuthService> logger)
    {
        _unitOfWork = unitOfWork;
        _passwordHasher = passwordHasher;
        _jwtGenerator = jwtGenerator;
        _emailService = emailService;
        _logger = logger;
    }

    public async Task<ApiResponse<AuthResponseDto>> LoginAsync(LoginRequestDto request, CancellationToken ct = default)
    {
        var userRepo = _unitOfWork.Repository<User>();
        var user = await userRepo.Query()
            .FirstOrDefaultAsync(u => u.Email == request.Email, ct);

        if (user == null)
        {
            return ApiResponse<AuthResponseDto>.Fail("Invalid email or password.");
        }

        if (user.Status == "0") // Inactive
        {
            return ApiResponse<AuthResponseDto>.Fail("Your account is pending verification. Please verify your email.");
        }

        if (user.Status == "2") // Blocked
        {
            return ApiResponse<AuthResponseDto>.Fail("Your account has been suspended. Please contact support.");
        }

        // Verify password using backward-compatible BCrypt
        var isValid = _passwordHasher.VerifyPassword(request.Password, user.Password ?? string.Empty);
        if (!isValid)
        {
            return ApiResponse<AuthResponseDto>.Fail("Invalid email or password.");
        }

        // Update last login
        user.LastLogin = DateTime.UtcNow;
        var refreshToken = _jwtGenerator.GenerateRefreshToken();
        user.Token = refreshToken;
        await _unitOfWork.SaveChangesAsync(ct);

        var accessToken = _jwtGenerator.GenerateAccessToken(user);

        var response = new AuthResponseDto
        {
            UserId = user.Id,
            Name = user.Name ?? string.Empty,
            Email = user.Email ?? string.Empty,
            RoleType = user.RoleType,
            RoleName = user.Role.ToString(),
            AccessToken = accessToken,
            RefreshToken = refreshToken,
            ExpiresAt = DateTime.UtcNow.AddMinutes(60),
            AvatarUrl = user.Image,
            Organization = user.CompanyName,
            Designation = user.Occupation
        };

        return ApiResponse<AuthResponseDto>.Ok(response, "Login successful.");
    }

    public async Task<ApiResponse<string>> RegisterAsync(RegisterRequestDto request, CancellationToken ct = default)
    {
        var userRepo = _unitOfWork.Repository<User>();
        var exists = await userRepo.Query().AnyAsync(u => u.Email == request.Email, ct);
        if (exists)
        {
            return ApiResponse<string>.Fail("An account with this email address already exists.");
        }

        if (request.Password != request.ConfirmPassword)
        {
            return ApiResponse<string>.Fail("Passwords do not match.");
        }

        var otp = new Random().Next(100000, 999999).ToString();

        var user = new User
        {
            Name = request.Name,
            Email = request.Email,
            Password = _passwordHasher.HashPassword(request.Password),
            RoleType = "2", // End User
            Status = "0", // Pending verification
            ActivationCode = otp,
            CompanyName = request.Organization ?? string.Empty,
            CreatedOn = DateTime.UtcNow,
            UpdatedOn = DateTime.UtcNow
        };

        if (long.TryParse(new string(request.ContactNo.Where(char.IsDigit).ToArray()), out var phone))
        {
            user.ContactNo = phone;
        }

        await userRepo.AddAsync(user, ct);
        await _unitOfWork.SaveChangesAsync(ct);

        // Send OTP email
        await _emailService.SendOtpEmailAsync(user.Email, user.Name, otp);

        return ApiResponse<string>.Ok(user.Email, "Account created successfully. Please enter the verification code sent to your email.");
    }

    public async Task<ApiResponse<AuthResponseDto>> VerifyOtpAsync(VerifyOtpRequestDto request, CancellationToken ct = default)
    {
        var userRepo = _unitOfWork.Repository<User>();
        var user = await userRepo.Query()
            .FirstOrDefaultAsync(u => u.Email == request.Email, ct);

        if (user == null)
        {
            return ApiResponse<AuthResponseDto>.Fail("User not found.");
        }

        if (user.ActivationCode != request.OtpCode)
        {
            return ApiResponse<AuthResponseDto>.Fail("Invalid or expired verification code.");
        }

        user.Status = "1"; // Active
        user.ActivationCode = null;
        var refreshToken = _jwtGenerator.GenerateRefreshToken();
        user.Token = refreshToken;
        await _unitOfWork.SaveChangesAsync(ct);

        var accessToken = _jwtGenerator.GenerateAccessToken(user);

        var response = new AuthResponseDto
        {
            UserId = user.Id,
            Name = user.Name ?? string.Empty,
            Email = user.Email ?? string.Empty,
            RoleType = user.RoleType,
            RoleName = user.Role.ToString(),
            AccessToken = accessToken,
            RefreshToken = refreshToken,
            ExpiresAt = DateTime.UtcNow.AddMinutes(60),
            Organization = user.CompanyName,
            Designation = user.Occupation
        };

        return ApiResponse<AuthResponseDto>.Ok(response, "Account verified successfully.");
    }

    public async Task<ApiResponse<AuthResponseDto>> RefreshTokenAsync(RefreshTokenRequestDto request, CancellationToken ct = default)
    {
        var userRepo = _unitOfWork.Repository<User>();
        var user = await userRepo.Query()
            .FirstOrDefaultAsync(u => u.Token == request.RefreshToken, ct);

        if (user == null)
        {
            return ApiResponse<AuthResponseDto>.Fail("Invalid refresh token.");
        }

        var newRefreshToken = _jwtGenerator.GenerateRefreshToken();
        user.Token = newRefreshToken;
        await _unitOfWork.SaveChangesAsync(ct);

        var accessToken = _jwtGenerator.GenerateAccessToken(user);

        var response = new AuthResponseDto
        {
            UserId = user.Id,
            Name = user.Name ?? string.Empty,
            Email = user.Email ?? string.Empty,
            RoleType = user.RoleType,
            RoleName = user.Role.ToString(),
            AccessToken = accessToken,
            RefreshToken = newRefreshToken,
            ExpiresAt = DateTime.UtcNow.AddMinutes(60),
            Organization = user.CompanyName,
            Designation = user.Occupation
        };

        return ApiResponse<AuthResponseDto>.Ok(response, "Token refreshed.");
    }

    public async Task<ApiResponse<string>> ForgotPasswordAsync(ForgotPasswordRequestDto request, CancellationToken ct = default)
    {
        var userRepo = _unitOfWork.Repository<User>();
        var user = await userRepo.Query().FirstOrDefaultAsync(u => u.Email == request.Email, ct);
        if (user == null)
        {
            // Security best practice: don't reveal user non-existence
            return ApiResponse<string>.Ok(request.Email, "If an account exists, a password reset link has been dispatched.");
        }

        var resetToken = Guid.NewGuid().ToString("N");
        user.Token = resetToken;
        await _unitOfWork.SaveChangesAsync(ct);

        await _emailService.SendEmailAsync(user.Email!, user.Name ?? "User", "GreenTIP: Reset Password",
            $"<p>Click <a href='https://greentip.com/reset-password?token={resetToken}&email={user.Email}'>here</a> to reset your password.</p>");

        return ApiResponse<string>.Ok(request.Email, "Password reset link has been dispatched.");
    }

    public async Task<ApiResponse<string>> ResetPasswordAsync(ResetPasswordRequestDto request, CancellationToken ct = default)
    {
        var userRepo = _unitOfWork.Repository<User>();
        var user = await userRepo.Query().FirstOrDefaultAsync(u => u.Email == request.Email && u.Token == request.Token, ct);
        if (user == null)
        {
            return ApiResponse<string>.Fail("Invalid or expired reset token.");
        }

        if (request.NewPassword != request.ConfirmNewPassword)
        {
            return ApiResponse<string>.Fail("Passwords do not match.");
        }

        user.Password = _passwordHasher.HashPassword(request.NewPassword);
        user.Token = null;
        await _unitOfWork.SaveChangesAsync(ct);

        return ApiResponse<string>.Ok(user.Email!, "Password updated successfully. Please log in with your new password.");
    }
}
