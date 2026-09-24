using System.Security.Claims;
using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Dashboard;
using GreenTIP.Application.Interfaces;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;

namespace GreenTIP.Api.Controllers;

[ApiController]
[Route("api/v1/[controller]")]
public class DashboardController : ControllerBase
{
    private readonly IDashboardService _dashboardService;

    public DashboardController(IDashboardService dashboardService)
    {
        _dashboardService = dashboardService;
    }

    [HttpGet("user")]
    public async Task<ActionResult<ApiResponse<UserDashboardDto>>> GetUserDashboard([FromQuery] int? userId, CancellationToken ct)
    {
        // Resolve user ID from JWT if present, otherwise from query (for testing)
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedId = int.TryParse(claimId, out var id) ? id : (userId ?? 19);

        var result = await _dashboardService.GetUserDashboardAsync(resolvedId, ct);
        return Ok(result);
    }

    [HttpGet("admin")]
    public async Task<ActionResult<ApiResponse<AdminDashboardDto>>> GetAdminDashboard([FromQuery] int? adminId, CancellationToken ct)
    {
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedId = int.TryParse(claimId, out var id) ? id : (adminId ?? 1);

        var result = await _dashboardService.GetAdminDashboardAsync(resolvedId, ct);
        return Ok(result);
    }
}
