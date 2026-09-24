import 'dart:convert';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../../data/models/user_model.dart';

class SecureStorageService {
  final FlutterSecureStorage _storage;

  SecureStorageService({FlutterSecureStorage? storage})
    : _storage = storage ?? const FlutterSecureStorage();

  static const String _keyToken = 'jwt_token';
  static const String _keyUser = 'cached_user';
  static const String _keyOnboardingSeen = 'onboarding_seen';

  Future<void> saveToken(String token) async {
    await _storage.write(key: _keyToken, value: token);
  }

  Future<String?> getToken() async {
    return await _storage.read(key: _keyToken);
  }

  Future<void> saveUser(UserModel user) async {
    await _storage.write(key: _keyUser, value: jsonEncode(user.toJson()));
  }

  Future<UserModel?> getUser() async {
    final userJson = await _storage.read(key: _keyUser);
    if (userJson == null) return null;
    try {
      return UserModel.fromJson(jsonDecode(userJson) as Map<String, dynamic>);
    } catch (_) {
      return null;
    }
  }

  Future<void> setOnboardingSeen() async {
    await _storage.write(key: _keyOnboardingSeen, value: 'true');
  }

  Future<bool> isOnboardingSeen() async {
    final val = await _storage.read(key: _keyOnboardingSeen);
    return val == 'true';
  }

  Future<void> clearAll() async {
    await _storage.delete(key: _keyToken);
    await _storage.delete(key: _keyUser);
  }
}
