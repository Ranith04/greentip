import '../../core/constants/api_endpoints.dart';
import '../../core/network/api_client.dart';
import '../models/notification_model.dart';

class NotificationRepository {
  final ApiClient _apiClient;

  NotificationRepository(this._apiClient);

  Future<List<NotificationModel>> getNotifications() async {
    final response = await _apiClient.get(ApiEndpoints.notifications);
    final raw = response.data;
    final List<dynamic> list = raw is List
        ? raw
        : (raw is Map && raw['items'] is List
              ? raw['items'] as List<dynamic>
              : []);
    return list
        .map((item) => NotificationModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }

  Future<void> markAsRead(int id) async {
    await _apiClient.post(ApiEndpoints.markNotificationRead(id));
  }
}
