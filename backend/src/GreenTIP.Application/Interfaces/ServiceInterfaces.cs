using GreenTIP.Application.Common;
using GreenTIP.Application.DTOs.Auth;
using GreenTIP.Application.DTOs.Dashboard;
using GreenTIP.Application.DTOs.Email;
using GreenTIP.Application.DTOs.Knowledge;
using GreenTIP.Application.DTOs.Queries;
using GreenTIP.Application.DTOs.Users;

namespace GreenTIP.Application.Interfaces;

public interface IAuthService
{
    Task<ApiResponse<AuthResponseDto>> LoginAsync(LoginRequestDto request, CancellationToken ct = default);
    Task<ApiResponse<string>> RegisterAsync(RegisterRequestDto request, CancellationToken ct = default);
    Task<ApiResponse<AuthResponseDto>> VerifyOtpAsync(VerifyOtpRequestDto request, CancellationToken ct = default);
    Task<ApiResponse<AuthResponseDto>> RefreshTokenAsync(RefreshTokenRequestDto request, CancellationToken ct = default);
    Task<ApiResponse<string>> ForgotPasswordAsync(ForgotPasswordRequestDto request, CancellationToken ct = default);
    Task<ApiResponse<string>> ResetPasswordAsync(ResetPasswordRequestDto request, CancellationToken ct = default);
}

public interface IDashboardService
{
    Task<ApiResponse<UserDashboardDto>> GetUserDashboardAsync(int userId, CancellationToken ct = default);
    Task<ApiResponse<AdminDashboardDto>> GetAdminDashboardAsync(int adminUserId, CancellationToken ct = default);
}

public interface IQueryService
{
    Task<ApiResponse<QueryDetailDto>> SubmitQueryAsync(int userId, SubmitQueryRequestDto request, CancellationToken ct = default);
    Task<ApiResponse<PaginatedResult<RecentQueryItemDto>>> GetUserQueriesAsync(int userId, string? status, int page, int pageSize, CancellationToken ct = default);
    Task<ApiResponse<PaginatedResult<AdminRecentQueryItemDto>>> GetAllQueriesAsync(string? status, int? categoryId, string? search, int page, int pageSize, CancellationToken ct = default);
    Task<ApiResponse<QueryDetailDto>> GetQueryDetailAsync(int queryId, int currentUserId, CancellationToken ct = default);
    Task<ApiResponse<bool>> AssignExpertAsync(int queryId, AssignExpertRequestDto request, CancellationToken ct = default);
    Task<ApiResponse<bool>> RespondToQueryAsync(int queryId, int expertId, ExpertResponseRequestDto request, CancellationToken ct = default);
    Task<ApiResponse<bool>> ReviewQueryAsync(int queryId, int userId, SubmitReviewRequestDto request, CancellationToken ct = default);
}

public interface IUserService
{
    Task<ApiResponse<PaginatedResult<UserListDto>>> GetUsersAsync(string? search, int page, int pageSize, CancellationToken ct = default);
    Task<ApiResponse<UserDetailDto>> GetUserDetailAsync(int userId, CancellationToken ct = default);
    Task<ApiResponse<PaginatedResult<RecentQueryItemDto>>> GetUserQueryHistoryAsync(int userId, string? status, int page, int pageSize, CancellationToken ct = default);
    Task<ApiResponse<bool>> ToggleUserStatusAsync(int userId, CancellationToken ct = default);
    Task<ApiResponse<bool>> UpdateProfileAsync(int userId, UpdateProfileDto request, CancellationToken ct = default);
    Task<ApiResponse<bool>> ChangePasswordAsync(int userId, ChangePasswordDto request, CancellationToken ct = default);
}

public interface IExpertService
{
    Task<ApiResponse<List<ExpertListDto>>> GetExpertsAsync(string? search, CancellationToken ct = default);
    Task<ApiResponse<ExpertDetailDto>> GetExpertDetailAsync(int expertId, CancellationToken ct = default);
    Task<ApiResponse<bool>> CreateExpertAsync(CreateExpertDto request, CancellationToken ct = default);
    Task<ApiResponse<bool>> ToggleExpertStatusAsync(int expertId, CancellationToken ct = default);
}

public interface IBulkEmailService
{
    Task<ApiResponse<EmailPreviewDto>> PreviewEmailAsync(ComposeEmailDto request, CancellationToken ct = default);
    Task<ApiResponse<bool>> SendBulkEmailAsync(ComposeEmailDto request, CancellationToken ct = default);
    Task<ApiResponse<PaginatedResult<BulkEmailLogDto>>> GetLogsAsync(string? search, int page, int pageSize, CancellationToken ct = default);
    Task<ApiResponse<BulkEmailLogDto>> GetLogDetailAsync(int id, CancellationToken ct = default);
}

public interface IKnowledgeService
{
    Task<ApiResponse<List<CategoryDto>>> GetCategoriesAsync(CancellationToken ct = default);
    Task<ApiResponse<List<ArticleSummaryDto>>> GetArticlesAsync(int? categoryId, string? search, CancellationToken ct = default);
    Task<ApiResponse<ArticleDetailDto>> GetArticleDetailAsync(int id, CancellationToken ct = default);
}

public interface INotificationService
{
    Task<ApiResponse<List<AppNotificationDto>>> GetNotificationsAsync(int userId, bool isAdmin, CancellationToken ct = default);
    Task<ApiResponse<bool>> MarkAllAsReadAsync(int userId, bool isAdmin, CancellationToken ct = default);
}
