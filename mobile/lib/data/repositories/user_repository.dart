import '../../core/constants/api_endpoints.dart';
import '../../core/network/api_client.dart';
import '../models/user_model.dart';

class UserRepository {
  final ApiClient _apiClient;

  UserRepository(this._apiClient);

  Future<List<UserModel>> getUsers({
    String? search,
    int? roleId,
    int page = 1,
    int pageSize = 50,
  }) async {
    final queryParams = <String, dynamic>{'page': page, 'pageSize': pageSize};
    if (search != null && search.isNotEmpty) queryParams['search'] = search;
    if (roleId != null) queryParams['roleId'] = roleId;

    final response = await _apiClient.get(
      ApiEndpoints.users,
      queryParameters: queryParams,
    );

    final raw = response.data;
    final List<dynamic> list = raw is List
        ? raw
        : (raw is Map && raw['items'] is List
              ? raw['items'] as List<dynamic>
              : []);
    return list
        .map((item) => UserModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }

  Future<UserModel> getUserById(int id) async {
    final response = await _apiClient.get(ApiEndpoints.userById(id));
    return UserModel.fromJson(response.data as Map<String, dynamic>);
  }

  Future<void> updateUserStatus(int id, int status) async {
    await _apiClient.put(
      '${ApiEndpoints.userById(id)}/status',
      data: {'status': status},
    );
  }

  Future<void> updateProfile({
    required String name,
    required String mobile,
    String? organization,
    String? designation,
  }) async {
    await _apiClient.put(
      ApiEndpoints.currentUser,
      data: {
        'name': name,
        'mobile': mobile,
        'organization': organization,
        'designation': designation,
      },
    );
  }
}
