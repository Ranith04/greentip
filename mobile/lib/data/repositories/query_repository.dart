import 'package:dio/dio.dart';
import '../../core/constants/api_endpoints.dart';
import '../../core/network/api_client.dart';
import '../models/category_model.dart';
import '../models/query_model.dart';

class QueryRepository {
  final ApiClient _apiClient;

  QueryRepository(this._apiClient);

  Future<List<QueryModel>> getQueries({
    int? status,
    int? categoryId,
    String? search,
    int page = 1,
    int pageSize = 50,
  }) async {
    final queryParams = <String, dynamic>{'page': page, 'pageSize': pageSize};
    if (status != null) queryParams['status'] = status;
    if (categoryId != null) queryParams['categoryId'] = categoryId;
    if (search != null && search.isNotEmpty) queryParams['search'] = search;

    final response = await _apiClient.get(
      ApiEndpoints.queries,
      queryParameters: queryParams,
    );

    final raw = response.data;
    final List<dynamic> list = raw is List
        ? raw
        : (raw is Map && raw['items'] is List
              ? raw['items'] as List<dynamic>
              : []);
    return list
        .map((item) => QueryModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }

  Future<QueryModel> getQueryById(int id) async {
    final response = await _apiClient.get(ApiEndpoints.queryById(id));
    return QueryModel.fromJson(response.data as Map<String, dynamic>);
  }

  Future<QueryModel> submitQuery({
    required String title,
    required String description,
    required String urgency,
    int? categoryId,
    String? industryName,
    String? sector,
    String? state,
    String? consentNumber,
    List<String>? filePaths,
  }) async {
    if (filePaths != null && filePaths.isNotEmpty) {
      final Map<String, dynamic> data = {
        'title': title,
        'description': description,
        'urgency': urgency,
      };
      if (categoryId != null) data['categoryId'] = categoryId;
      if (industryName != null) data['industryName'] = industryName;
      if (sector != null) data['sector'] = sector;
      if (state != null) data['state'] = state;
      if (consentNumber != null) data['consentNumber'] = consentNumber;

      final formData = FormData.fromMap(data);

      for (var path in filePaths) {
        final fileName = path.split(RegExp(r'[\\/]')).last;
        formData.files.add(
          MapEntry(
            'files',
            await MultipartFile.fromFile(path, filename: fileName),
          ),
        );
      }

      final response = await _apiClient.post(
        ApiEndpoints.queries,
        data: formData,
      );
      return QueryModel.fromJson(response.data as Map<String, dynamic>);
    } else {
      final response = await _apiClient.post(
        ApiEndpoints.queries,
        data: {
          'title': title,
          'description': description,
          'urgency': urgency,
          'categoryId': categoryId,
          'industryName': industryName,
          'sector': sector,
          'state': state,
          'consentNumber': consentNumber,
        },
      );
      return QueryModel.fromJson(response.data as Map<String, dynamic>);
    }
  }

  Future<void> assignExpert(int queryId, int expertId, String? remarks) async {
    await _apiClient.post(
      ApiEndpoints.assignExpert(queryId),
      data: {'expertId': expertId, 'remarks': remarks},
    );
  }

  Future<void> respondQuery(int queryId, String responseText) async {
    await _apiClient.post(
      ApiEndpoints.respondQuery(queryId),
      data: {'response': responseText},
    );
  }

  Future<void> reviewQuery(
    int queryId,
    int rating,
    String? feedbackTag,
    String? comments,
  ) async {
    await _apiClient.post(
      ApiEndpoints.reviewQuery(queryId),
      data: {
        'rating': rating,
        'feedbackTag': feedbackTag,
        'comments': comments,
      },
    );
  }

  Future<void> updateStatus(int queryId, int status) async {
    await _apiClient.put(
      ApiEndpoints.updateQueryStatus(queryId),
      data: {'status': status},
    );
  }

  Future<List<CategoryModel>> getCategories() async {
    final response = await _apiClient.get(ApiEndpoints.queryCategories);
    final raw = response.data;
    final List<dynamic> list = raw is List
        ? raw
        : (raw is Map && raw['items'] is List
              ? raw['items'] as List<dynamic>
              : []);
    return list
        .map((item) => CategoryModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }
}
