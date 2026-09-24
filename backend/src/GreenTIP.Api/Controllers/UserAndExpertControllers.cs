using System.Security.Claims;
using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Dashboard;
using GreenTIP.Application.DTOs.Users;
using GreenTIP.Application.Interfaces;
using Microsoft.AspNetCore.Mvc;

namespace GreenTIP.Api.Controllers;

[ApiController]
[Route("api/v1/[controller]")]
public class UsersController : ControllerBase
{
    private readonly IUserService _userService;

    public UsersController(IUserService userService)
    {
        _userService = userService;
    }

    [HttpGet]
    public async Task<ActionResult<ApiResponse<PaginatedResult<UserListDto>>>> GetUsers(
        [FromQuery] string? search,
        [FromQuery] int page = 1,
        [FromQuery] int pageSize = 10,
        CancellationToken ct = default)
    {
        var result = await _userService.GetUsersAsync(search, page, pageSize, ct);
        return Ok(result);
    }

    [HttpGet("{id}")]
    public async Task<ActionResult<ApiResponse<UserDetailDto>>> GetUserDetail(int id, CancellationToken ct)
    {
        var result = await _userService.GetUserDetailAsync(id, ct);
        if (!result.Success) return NotFound(result);
        return Ok(result);
    }

    [HttpGet("{id}/queries")]
    public async Task<ActionResult<ApiResponse<PaginatedResult<RecentQueryItemDto>>>> GetUserQueries(
        int id,
        [FromQuery] string? status,
        [FromQuery] int page = 1,
        [FromQuery] int pageSize = 10,
        CancellationToken ct = default)
    {
        var result = await _userService.GetUserQueryHistoryAsync(id, status, page, pageSize, ct);
        return Ok(result);
    }

    [HttpPatch("{id}/status")]
    public async Task<ActionResult<ApiResponse<bool>>> ToggleUserStatus(int id, CancellationToken ct)
    {
        var result = await _userService.ToggleUserStatusAsync(id, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpPut("profile")]
    public async Task<ActionResult<ApiResponse<bool>>> UpdateProfile([FromBody] UpdateProfileDto request, [FromQuery] int? userId, CancellationToken ct)
    {
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedUserId = int.TryParse(claimId, out var uid) ? uid : (userId ?? 19);

        var result = await _userService.UpdateProfileAsync(resolvedUserId, request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpPut("change-password")]
    public async Task<ActionResult<ApiResponse<bool>>> ChangePassword([FromBody] ChangePasswordDto request, [FromQuery] int? userId, CancellationToken ct)
    {
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedUserId = int.TryParse(claimId, out var uid) ? uid : (userId ?? 19);

        var result = await _userService.ChangePasswordAsync(resolvedUserId, request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }
}

[ApiController]
[Route("api/v1/[controller]")]
public class ExpertsController : ControllerBase
{
    private readonly IExpertService _expertService;

    public ExpertsController(IExpertService expertService)
    {
        _expertService = expertService;
    }

    [HttpGet]
    public async Task<ActionResult<ApiResponse<List<ExpertListDto>>>> GetExperts([FromQuery] string? search, CancellationToken ct)
    {
        var result = await _expertService.GetExpertsAsync(search, ct);
        return Ok(result);
    }

    [HttpGet("{id}")]
    public async Task<ActionResult<ApiResponse<ExpertDetailDto>>> GetExpertDetail(int id, CancellationToken ct)
    {
        var result = await _expertService.GetExpertDetailAsync(id, ct);
        if (!result.Success) return NotFound(result);
        return Ok(result);
    }

    [HttpPost]
    public async Task<ActionResult<ApiResponse<bool>>> CreateExpert([FromBody] CreateExpertDto request, CancellationToken ct)
    {
        var result = await _expertService.CreateExpertAsync(request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpPatch("{id}/status")]
    public async Task<ActionResult<ApiResponse<bool>>> ToggleExpertStatus(int id, CancellationToken ct)
    {
        var result = await _expertService.ToggleExpertStatusAsync(id, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }
}
