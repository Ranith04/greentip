import 'package:get_it/get_it.dart';
import '../../data/repositories/auth_repository.dart';
import '../../data/repositories/bulk_email_repository.dart';
import '../../data/repositories/dashboard_repository.dart';
import '../../data/repositories/expert_repository.dart';
import '../../data/repositories/knowledge_repository.dart';
import '../../data/repositories/notification_repository.dart';
import '../../data/repositories/query_repository.dart';
import '../../data/repositories/user_repository.dart';
import '../../presentation/blocs/admin_dashboard/admin_dashboard_bloc.dart';
import '../../presentation/blocs/admin_query/admin_query_bloc.dart';
import '../../presentation/blocs/auth/auth_bloc.dart';
import '../../presentation/blocs/bulk_email/bulk_email_bloc.dart';
import '../../presentation/blocs/expert/expert_bloc.dart';
import '../../presentation/blocs/knowledge/knowledge_bloc.dart';
import '../../presentation/blocs/notification/notification_bloc.dart';
import '../../presentation/blocs/query/query_bloc.dart';
import '../../presentation/blocs/user_dashboard/user_dashboard_bloc.dart';
import '../../presentation/blocs/user_management/user_management_bloc.dart';
import '../network/api_client.dart';
import '../storage/secure_storage_service.dart';

final getIt = GetIt.instance;

Future<void> initDependencies() async {
  // Core & Storage
  final storageService = SecureStorageService();
  getIt.registerSingleton<SecureStorageService>(storageService);

  final apiClient = ApiClient(storageService);
  getIt.registerSingleton<ApiClient>(apiClient);

  // Repositories
  getIt.registerLazySingleton<AuthRepository>(
    () => AuthRepository(getIt<ApiClient>(), getIt<SecureStorageService>()),
  );
  getIt.registerLazySingleton<DashboardRepository>(
    () => DashboardRepository(getIt<ApiClient>()),
  );
  getIt.registerLazySingleton<QueryRepository>(
    () => QueryRepository(getIt<ApiClient>()),
  );
  getIt.registerLazySingleton<UserRepository>(
    () => UserRepository(getIt<ApiClient>()),
  );
  getIt.registerLazySingleton<ExpertRepository>(
    () => ExpertRepository(getIt<ApiClient>()),
  );
  getIt.registerLazySingleton<BulkEmailRepository>(
    () => BulkEmailRepository(getIt<ApiClient>()),
  );
  getIt.registerLazySingleton<KnowledgeRepository>(
    () => KnowledgeRepository(getIt<ApiClient>()),
  );
  getIt.registerLazySingleton<NotificationRepository>(
    () => NotificationRepository(getIt<ApiClient>()),
  );

  // BLoCs (Factories for state isolation)
  getIt.registerFactory<AuthBloc>(
    () => AuthBloc(
      authRepository: getIt<AuthRepository>(),
      storageService: getIt<SecureStorageService>(),
    ),
  );
  getIt.registerFactory<UserDashboardBloc>(
    () => UserDashboardBloc(dashboardRepository: getIt<DashboardRepository>()),
  );
  getIt.registerFactory<QueryBloc>(
    () => QueryBloc(queryRepository: getIt<QueryRepository>()),
  );
  getIt.registerFactory<AdminDashboardBloc>(
    () => AdminDashboardBloc(dashboardRepository: getIt<DashboardRepository>()),
  );
  getIt.registerFactory<AdminQueryBloc>(
    () => AdminQueryBloc(queryRepository: getIt<QueryRepository>()),
  );
  getIt.registerFactory<ExpertBloc>(
    () => ExpertBloc(expertRepository: getIt<ExpertRepository>()),
  );
  getIt.registerFactory<UserManagementBloc>(
    () => UserManagementBloc(userRepository: getIt<UserRepository>()),
  );
  getIt.registerFactory<BulkEmailBloc>(
    () => BulkEmailBloc(bulkEmailRepository: getIt<BulkEmailRepository>()),
  );
  getIt.registerFactory<KnowledgeBloc>(
    () => KnowledgeBloc(knowledgeRepository: getIt<KnowledgeRepository>()),
  );
  getIt.registerFactory<NotificationBloc>(
    () => NotificationBloc(
      notificationRepository: getIt<NotificationRepository>(),
    ),
  );
}
