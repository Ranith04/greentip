import '../../core/constants/api_endpoints.dart';
import '../../core/network/api_client.dart';
import '../models/expert_model.dart';

class ExpertRepository {
  final ApiClient _apiClient;

  ExpertRepository(this._apiClient);

  Future<List<ExpertModel>> getExperts({String? search, int? status}) async {
    final queryParams = <String, dynamic>{};
    if (search != null && search.isNotEmpty) queryParams['search'] = search;
    if (status != null) queryParams['status'] = status;

    final response = await _apiClient.get(
      ApiEndpoints.experts,
      queryParameters: queryParams,
    );

    final raw = response.data;
    final List<dynamic> list = raw is List
        ? raw
        : (raw is Map && raw['items'] is List
              ? raw['items'] as List<dynamic>
              : []);
    return list
        .map((item) => ExpertModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }

  Future<ExpertModel> getExpertById(int id) async {
    final response = await _apiClient.get(ApiEndpoints.expertById(id));
    return ExpertModel.fromJson(response.data as Map<String, dynamic>);
  }

  Future<ExpertModel> addExpert({
    required String name,
    required String email,
    required String mobile,
    required String password,
    String? specialization,
    int? status,
  }) async {
    final response = await _apiClient.post(
      ApiEndpoints.experts,
      data: {
        'name': name,
        'email': email,
        'mobile': mobile,
        'password': password,
        'specialization': specialization,
        if (status != null) 'status': status,
      },
    );

    return ExpertModel.fromJson(response.data as Map<String, dynamic>);
  }

  Future<void> toggleExpertStatus(int id, int status) async {
    await _apiClient.put(
      '${ApiEndpoints.expertById(id)}/status',
      data: {'status': status},
    );
  }
}
