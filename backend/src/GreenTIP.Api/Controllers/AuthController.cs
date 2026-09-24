using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Auth;
using GreenTIP.Application.Interfaces;
using Microsoft.AspNetCore.Mvc;

namespace GreenTIP.Api.Controllers;

[ApiController]
[Route("api/v1/[controller]")]
public class AuthController : ControllerBase
{
    private readonly IAuthService _authService;

    public AuthController(IAuthService authService)
    {
        _authService = authService;
    }

    [HttpPost("login")]
    public async Task<ActionResult<ApiResponse<AuthResponseDto>>> Login([FromBody] LoginRequestDto request, CancellationToken ct)
    {
        var result = await _authService.LoginAsync(request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpPost("register")]
    public async Task<ActionResult<ApiResponse<string>>> Register([FromBody] RegisterRequestDto request, CancellationToken ct)
    {
        var result = await _authService.RegisterAsync(request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpPost("verify-otp")]
    public async Task<ActionResult<ApiResponse<AuthResponseDto>>> VerifyOtp([FromBody] VerifyOtpRequestDto request, CancellationToken ct)
    {
        var result = await _authService.VerifyOtpAsync(request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpPost("refresh-token")]
    public async Task<ActionResult<ApiResponse<AuthResponseDto>>> RefreshToken([FromBody] RefreshTokenRequestDto request, CancellationToken ct)
    {
        var result = await _authService.RefreshTokenAsync(request, ct);
        if (!result.Success) return Unauthorized(result);
        return Ok(result);
    }

    [HttpPost("forgot-password")]
    public async Task<ActionResult<ApiResponse<string>>> ForgotPassword([FromBody] ForgotPasswordRequestDto request, CancellationToken ct)
    {
        var result = await _authService.ForgotPasswordAsync(request, ct);
        return Ok(result);
    }

    [HttpPost("reset-password")]
    public async Task<ActionResult<ApiResponse<string>>> ResetPassword([FromBody] ResetPasswordRequestDto request, CancellationToken ct)
    {
        var result = await _authService.ResetPasswordAsync(request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpGet("me")]
    public async Task<ActionResult<ApiResponse<GreenTIP.Application.DTOs.Users.UserDetailDto>>> GetCurrentUser([FromServices] IUserService userService, CancellationToken ct)
    {
        var claimId = User.FindFirst(System.Security.Claims.ClaimTypes.NameIdentifier)?.Value;
        var uid = int.TryParse(claimId, out var id) ? id : 19;

        var result = await userService.GetUserDetailAsync(uid, ct);
        if (!result.Success) return NotFound(result);
        return Ok(result);
    }

    [HttpPost("resend-otp")]
    public async Task<ActionResult<ApiResponse<string>>> ResendOtp([FromBody] ForgotPasswordRequestDto request, CancellationToken ct)
    {
        var result = await _authService.ForgotPasswordAsync(request, ct);
        return Ok(result);
    }
}
