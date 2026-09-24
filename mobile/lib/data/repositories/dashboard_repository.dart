import '../../core/constants/api_endpoints.dart';
import '../../core/network/api_client.dart';
import '../models/dashboard_model.dart';

class DashboardRepository {
  final ApiClient _apiClient;

  DashboardRepository(this._apiClient);

  Future<UserDashboardModel> getUserDashboard() async {
    final response = await _apiClient.get(ApiEndpoints.userDashboard);
    return UserDashboardModel.fromJson(response.data as Map<String, dynamic>);
  }

  Future<AdminDashboardModel> getAdminDashboard() async {
    final response = await _apiClient.get(ApiEndpoints.adminDashboard);
    return AdminDashboardModel.fromJson(response.data as Map<String, dynamic>);
  }
}
