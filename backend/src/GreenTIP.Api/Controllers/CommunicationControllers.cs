using System.Security.Claims;
using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Email;
using GreenTIP.Application.DTOs.Knowledge;
using GreenTIP.Application.DTOs.Queries;
using GreenTIP.Application.Interfaces;
using Microsoft.AspNetCore.Mvc;

namespace GreenTIP.Api.Controllers;

[ApiController]
[Route("api/v1/email/bulk")]
public class BulkEmailController : ControllerBase
{
    private readonly IBulkEmailService _bulkEmailService;

    public BulkEmailController(IBulkEmailService bulkEmailService)
    {
        _bulkEmailService = bulkEmailService;
    }

    [HttpPost("preview")]
    public async Task<ActionResult<ApiResponse<EmailPreviewDto>>> Preview([FromBody] ComposeEmailDto request, CancellationToken ct)
    {
        var result = await _bulkEmailService.PreviewEmailAsync(request, ct);
        return Ok(result);
    }

    [HttpPost("send")]
    public async Task<ActionResult<ApiResponse<bool>>> Send([FromBody] ComposeEmailDto request, CancellationToken ct)
    {
        var result = await _bulkEmailService.SendBulkEmailAsync(request, ct);
        if (!result.Success) return BadRequest(result);
        return Ok(result);
    }

    [HttpGet("logs")]
    public async Task<ActionResult<ApiResponse<PaginatedResult<BulkEmailLogDto>>>> GetLogs(
        [FromQuery] string? search,
        [FromQuery] int page = 1,
        [FromQuery] int pageSize = 10,
        CancellationToken ct = default)
    {
        var result = await _bulkEmailService.GetLogsAsync(search, page, pageSize, ct);
        return Ok(result);
    }

    [HttpGet("logs/{id}")]
    public async Task<ActionResult<ApiResponse<BulkEmailLogDto>>> GetLogDetail(int id, CancellationToken ct)
    {
        var result = await _bulkEmailService.GetLogDetailAsync(id, ct);
        if (!result.Success) return NotFound(result);
        return Ok(result);
    }
}

[ApiController]
[Route("api/v1/[controller]")]
public class KnowledgeController : ControllerBase
{
    private readonly IKnowledgeService _knowledgeService;

    public KnowledgeController(IKnowledgeService knowledgeService)
    {
        _knowledgeService = knowledgeService;
    }

    [HttpGet("categories")]
    public async Task<ActionResult<ApiResponse<List<CategoryDto>>>> GetCategories(CancellationToken ct)
    {
        var result = await _knowledgeService.GetCategoriesAsync(ct);
        return Ok(result);
    }

    [HttpGet("articles")]
    public async Task<ActionResult<ApiResponse<List<ArticleSummaryDto>>>> GetArticles([FromQuery] int? categoryId, [FromQuery] string? search, CancellationToken ct)
    {
        var result = await _knowledgeService.GetArticlesAsync(categoryId, search, ct);
        return Ok(result);
    }

    [HttpGet("articles/{id}")]
    public async Task<ActionResult<ApiResponse<ArticleDetailDto>>> GetArticleDetail(int id, CancellationToken ct)
    {
        var result = await _knowledgeService.GetArticleDetailAsync(id, ct);
        if (!result.Success) return NotFound(result);
        return Ok(result);
    }
}

[ApiController]
[Route("api/v1/[controller]")]
public class NotificationsController : ControllerBase
{
    private readonly INotificationService _notificationService;

    public NotificationsController(INotificationService notificationService)
    {
        _notificationService = notificationService;
    }

    [HttpGet]
    public async Task<ActionResult<ApiResponse<List<AppNotificationDto>>>> GetNotifications(
        [FromQuery] int? userId,
        [FromQuery] bool isAdmin = false,
        CancellationToken ct = default)
    {
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedUserId = int.TryParse(claimId, out var uid) ? uid : (userId ?? 19);

        var result = await _notificationService.GetNotificationsAsync(resolvedUserId, isAdmin, ct);
        return Ok(result);
    }

    [HttpPost("read-all")]
    public async Task<ActionResult<ApiResponse<bool>>> MarkAllAsRead(
        [FromQuery] int? userId,
        [FromQuery] bool isAdmin = false,
        CancellationToken ct = default)
    {
        var claimId = User.FindFirstValue(ClaimTypes.NameIdentifier);
        var resolvedUserId = int.TryParse(claimId, out var uid) ? uid : (userId ?? 19);

        var result = await _notificationService.MarkAllAsReadAsync(resolvedUserId, isAdmin, ct);
        return Ok(result);
    }
}

[ApiController]
[Route("api/v1/[controller]")]
public class UploadController : ControllerBase
{
    private readonly IFileStorageService _fileStorageService;

    public UploadController(IFileStorageService fileStorageService)
    {
        _fileStorageService = fileStorageService;
    }

    [HttpPost]
    public async Task<ActionResult<ApiResponse<AttachmentPayloadDto>>> Upload(IFormFile file, [FromQuery] string folder = "documents")
    {
        if (file == null || file.Length == 0)
        {
            return BadRequest(ApiResponse<AttachmentPayloadDto>.Fail("No file uploaded."));
        }

        using var stream = file.OpenReadStream();
        var (filePath, fileName, fileSize) = await _fileStorageService.SaveFileAsync(stream, file.FileName, folder);

        var payload = new AttachmentPayloadDto
        {
            FileName = fileName,
            FilePath = filePath,
            FileSizeBytes = fileSize,
            MimeType = file.ContentType
        };

        return Ok(ApiResponse<AttachmentPayloadDto>.Ok(payload, "File uploaded successfully."));
    }
}
