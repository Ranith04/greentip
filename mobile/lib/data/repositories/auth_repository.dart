import '../../core/constants/api_endpoints.dart';
import '../../core/network/api_client.dart';
import '../../core/storage/secure_storage_service.dart';
import '../models/user_model.dart';

class AuthRepository {
  final ApiClient _apiClient;
  final SecureStorageService _storageService;

  AuthRepository(this._apiClient, this._storageService);

  Future<UserModel> login(String emailOrMobile, String password) async {
    final response = await _apiClient.post(
      ApiEndpoints.login,
      data: {'email': emailOrMobile, 'password': password},
    );

    final data = response.data as Map<String, dynamic>;
    final user = UserModel.fromJson(data);
    if (user.token != null) {
      await _storageService.saveToken(user.token!);
      await _storageService.saveUser(user);
    }
    return user;
  }

  Future<void> register({
    required String name,
    required String email,
    required String mobile,
    required String password,
    String? organization,
    String? designation,
  }) async {
    await _apiClient.post(
      ApiEndpoints.register,
      data: {
        'name': name,
        'email': email,
        'contactNo': mobile,
        'password': password,
        'confirmPassword': password,
        'organization': organization,
        'designation': designation,
      },
    );
  }

  Future<UserModel> verifyOtp(String emailOrMobile, String otp) async {
    final response = await _apiClient.post(
      ApiEndpoints.verifyOtp,
      data: {'email': emailOrMobile, 'otpCode': otp},
    );

    final data = response.data as Map<String, dynamic>;
    final user = UserModel.fromJson(data);
    if (user.token != null) {
      await _storageService.saveToken(user.token!);
      await _storageService.saveUser(user);
    }
    return user;
  }

  Future<void> resendOtp(String emailOrMobile) async {
    await _apiClient.post(
      ApiEndpoints.resendOtp,
      data: {'email': emailOrMobile},
    );
  }

  Future<UserModel?> getCurrentUser() async {
    try {
      final response = await _apiClient.get(ApiEndpoints.currentUser);
      final user = UserModel.fromJson(response.data as Map<String, dynamic>);
      await _storageService.saveUser(user);
      return user;
    } catch (_) {
      return await _storageService.getUser();
    }
  }

  Future<UserModel?> getSavedUser() async {
    return await _storageService.getUser();
  }

  Future<bool> isLoggedIn() async {
    final token = await _storageService.getToken();
    return token != null && token.isNotEmpty;
  }

  Future<void> logout() async {
    await _storageService.clearAll();
  }
}
