import '../../core/constants/api_endpoints.dart';
import '../../core/network/api_client.dart';
import '../models/article_model.dart';
import '../models/category_model.dart';

class KnowledgeRepository {
  final ApiClient _apiClient;

  KnowledgeRepository(this._apiClient);

  Future<List<ArticleModel>> getArticles() async {
    final response = await _apiClient.get(ApiEndpoints.articles);
    final raw = response.data;
    final List<dynamic> list = raw is List
        ? raw
        : (raw is Map && raw['items'] is List
              ? raw['items'] as List<dynamic>
              : []);
    return list
        .map((item) => ArticleModel.fromJson(item as Map<String, dynamic>))
        .toList();
  }

  Future<List<ArticleModel>> getFaqs({int? categoryId}) async {
    final queryParams = <String, dynamic>{};
    if (categoryId != null) queryParams['categoryId'] = categoryId;

    final response = await _apiClient.get(
      ApiEndpoints.faqs,
      queryParameters: queryParams,
    );
    final raw = response.data;
    final List<dynamic> list = raw is List
        ? raw
        : (raw is Map && raw['items'] is List
              ? raw['items'] as List<dynamic>
              : []);
    return list
        .map((item) => ArticleModel.fromJson(item as Map<String, dynamic>))
        .toList();
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
