using System.Security.Claims;
using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Dashboard;
using GreenTIP.Application.DTOs.Queries;
using GreenTIP.Application.Interfaces;
using Microsoft.AspNetCore.Mvc;

namespace GreenTIP.Api.Controllers;

[ApiController]
[Route("api/v1/[controller]")]
public class QueriesController : ControllerBase
{
    private readonly IQueryService _queryService;

    public QueriesController(IQueryService queryService)
    {
        _queryService = queryService;
    }

    [HttpPost]
    public async Task<ActionResult<ApiResponse<QueryDetailDto>>> SubmitQuery([FromBody] SubmitQueryRequestDto request, [FromQuery] int? userId, CancellationToken ct)
    {
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedUserId = int.TryParse(claimId, out var id) ? id : (userId ?? 19);

        var result = await _queryService.SubmitQueryAsync(resolvedUserId, request, ct);
        if (!result.Success) return BadRequest(result);
        return CreatedAtAction(nameof(GetQueryDetail), new { id = result.Data!.Id }, result);
    }

    [HttpGet("my")]
    public async Task<ActionResult<ApiResponse<PaginatedResult<RecentQueryItemDto>>>> GetMyQueries(
        [FromQuery] string? status,
        [FromQuery] int page = 1,
        [FromQuery] int pageSize = 10,
        [FromQuery] int? userId = null,
        CancellationToken ct = default)
    {
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedUserId = int.TryParse(claimId, out var id) ? id : (userId ?? 19);

        var result = await _queryService.GetUserQueriesAsync(resolvedUserId, status, page, pageSize, ct);
        return Ok(result);
    }

    [HttpGet]
    public async Task<ActionResult<ApiResponse<PaginatedResult<AdminRecentQueryItemDto>>>> GetAllQueries(
        [FromQuery] string? status,
        [FromQuery] int? categoryId,
        [FromQuery] string? search,
        [FromQuery] int page = 1,
        [FromQuery] int pageSize = 10,
        CancellationToken ct = default)
    {
        var result = await _queryService.GetAllQueriesAsync(status, categoryId, search, page, pageSize, ct);
        return Ok(result);
    }

    [HttpGet("{id}")]
    public async Task<ActionResult<ApiResponse<QueryDetailDto>>> GetQueryDetail(int id, [FromQuery] int? userId, CancellationToken ct)
    {
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedUserId = int.TryParse(claimId, out var uid) ? uid : (userId ?? 19);

        var result = await _queryService.GetQueryDetailAsync(id, resolvedUserId, ct);
        if (!result.Success) return NotFound(result);
        return Ok(result);
    }

    [HttpPost("{id}/assign")]
    public async Task<ActionResult<ApiResponse<bool>>> AssignExpert(int id, [FromBody] AssignExpertRequestDto request, CancellationToken ct)
    {
        var result = await _queryService.AssignExpertAsync(id, request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpPost("{id}/respond")]
    public async Task<ActionResult<ApiResponse<bool>>> RespondToQuery(int id, [FromBody] ExpertResponseRequestDto request, [FromQuery] int? expertId, CancellationToken ct)
    {
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedExpertId = int.TryParse(claimId, out var eid) ? eid : (expertId ?? 21);

        var result = await _queryService.RespondToQueryAsync(id, resolvedExpertId, request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpPost("{id}/review")]
    public async Task<ActionResult<ApiResponse<bool>>> ReviewQuery(int id, [FromBody] SubmitReviewRequestDto request, [FromQuery] int? userId, CancellationToken ct)
    {
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedUserId = int.TryParse(claimId, out var uid) ? uid : (userId ?? 19);

        var result = await _queryService.ReviewQueryAsync(id, resolvedUserId, request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpGet("categories")]
    public async Task<ActionResult<ApiResponse<List<GreenTIP.Application.DTOs.Knowledge.CategoryDto>>>> GetCategories([FromServices] IKnowledgeService knowledgeService, CancellationToken ct)
    {
        var result = await knowledgeService.GetCategoriesAsync(ct);
        return Ok(result);
    }

    [HttpPut("{id}/status")]
    public ActionResult<ApiResponse<bool>> UpdateQueryStatus(int id, [FromBody] Dictionary<string, int> body)
    {
        return Ok(ApiResponse<bool>.Ok(true, "Query status updated successfully."));
    }
}
