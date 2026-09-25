import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'core/constants/app_strings.dart';
import 'core/di/injection.dart';
import 'core/router/app_router.dart';
import 'core/theme/app_theme.dart';
import 'presentation/blocs/admin_dashboard/admin_dashboard_bloc.dart';
import 'presentation/blocs/admin_query/admin_query_bloc.dart';
import 'presentation/blocs/auth/auth_bloc.dart';
import 'presentation/blocs/bulk_email/bulk_email_bloc.dart';
import 'presentation/blocs/expert/expert_bloc.dart';
import 'presentation/blocs/knowledge/knowledge_bloc.dart';
import 'presentation/blocs/notification/notification_bloc.dart';
import 'presentation/blocs/query/query_bloc.dart';
import 'presentation/blocs/user_dashboard/user_dashboard_bloc.dart';
import 'presentation/blocs/user_management/user_management_bloc.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await initDependencies();

  runApp(const GreenTipApp());
}

class GreenTipApp extends StatefulWidget {
  const GreenTipApp({super.key});

  @override
  State<GreenTipApp> createState() => _GreenTipAppState();
}

class _GreenTipAppState extends State<GreenTipApp> {
  late final GoRouter _router;

  @override
  void initState() {
    super.initState();
    _router = AppRouter.getRouter(getIt<AuthBloc>());
  }

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider<AuthBloc>(create: (_) => getIt<AuthBloc>()),
        BlocProvider<UserDashboardBloc>(
          create: (_) => getIt<UserDashboardBloc>(),
        ),
        BlocProvider<QueryBloc>(create: (_) => getIt<QueryBloc>()),
        BlocProvider<AdminDashboardBloc>(
          create: (_) => getIt<AdminDashboardBloc>(),
        ),
        BlocProvider<AdminQueryBloc>(create: (_) => getIt<AdminQueryBloc>()),
        BlocProvider<ExpertBloc>(create: (_) => getIt<ExpertBloc>()),
        BlocProvider<UserManagementBloc>(
          create: (_) => getIt<UserManagementBloc>(),
        ),
        BlocProvider<BulkEmailBloc>(create: (_) => getIt<BulkEmailBloc>()),
        BlocProvider<KnowledgeBloc>(create: (_) => getIt<KnowledgeBloc>()),
        BlocProvider<NotificationBloc>(
          create: (_) => getIt<NotificationBloc>(),
        ),
      ],
      child: MaterialApp.router(
        title: AppStrings.appName,
        debugShowCheckedModeBanner: false,
        theme: AppTheme.lightTheme,
        routerConfig: _router,
      ),
    );
  }
}
