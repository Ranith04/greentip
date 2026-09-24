import '../../core/constants/api_endpoints.dart';
import '../../core/network/api_client.dart';
import '../models/bulk_email_model.dart';

class BulkEmailRepository {
  final ApiClient _apiClient;

  BulkEmailRepository(this._apiClient);

  Future<void> sendBulkEmail({
    required String subject,
    required String message,
    required String targetAudience,
    List<int>? specificUserIds,
  }) async {
    await _apiClient.post(
      ApiEndpoints.bulkEmail,
      data: {
        'subject': subject,
        'message': message,
        'targetAudience': targetAudience,
        'specificUserIds': specificUserIds,
      },
    );
  }

  Future<List<BulkEmailLogModel>> getLogs({
    int page = 1,
    int pageSize = 50,
  }) async {
    final response = await _apiClient.get(
      ApiEndpoints.bulkEmailLogs,
      queryParameters: {'page': page, 'pageSize': pageSize},
    );

    final raw = response.data;
    final List<dynamic> list = raw is List
        ? raw
        : (raw is Map && raw['items'] is List
              ? raw['items'] as List<dynamic>
              : []);
    return list
        .map((item) => BulkEmailLogModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }
}
