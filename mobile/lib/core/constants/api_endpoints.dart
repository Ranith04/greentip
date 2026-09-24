import 'dart:io';

class ApiEndpoints {
  // Automatically select host based on platform:
  // Android Emulator uses 10.0.2.2 to reach host machine
  // iOS Simulator or desktop uses localhost
  static String get baseUrl {
    try {
      if (Platform.isAndroid) {
        return 'http://192.168.0.5:5296/api/v1';
      }
    } catch (_) {
      // Fallback for web/test
    }
    return 'http://192.168.0.5:5296/api/v1';
  }

  // Auth
  static const String login = '/auth/login';
  static const String register = '/auth/register';
  static const String verifyOtp = '/auth/verify-otp';
  static const String resendOtp = '/auth/resend-otp';
  static const String currentUser = '/auth/me';

  // Dashboard
  static const String userDashboard = '/dashboard/user';
  static const String adminDashboard = '/dashboard/admin';

  // Queries
  static const String queries = '/queries';
  static const String queryCategories = '/queries/categories';
  static String queryById(int id) => '/queries/$id';
  static String assignExpert(int id) => '/queries/$id/assign';
  static String respondQuery(int id) => '/queries/$id/respond';
  static String reviewQuery(int id) => '/queries/$id/review';
  static String updateQueryStatus(int id) => '/queries/$id/status';

  // Users & Experts
  static const String users = '/users';
  static String userById(int id) => '/users/$id';
  static const String experts = '/experts';
  static String expertById(int id) => '/experts/$id';

  // Knowledge & Communication
  static const String articles = '/knowledge/articles';
  static const String faqs = '/knowledge/faqs';
  static const String bulkEmail = '/email/bulk/send';
  static const String bulkEmailLogs = '/email/bulk/logs';
  static const String notifications = '/notifications';
  static String markNotificationRead(int id) => '/notifications/read-all';
}
