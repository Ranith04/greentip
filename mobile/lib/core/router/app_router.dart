import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../data/models/article_model.dart';
import '../../data/models/category_model.dart';
import '../../data/models/expert_model.dart';
import '../../data/models/query_model.dart';
import '../../data/models/user_model.dart';
import '../../presentation/screens/admin/add_expert_screen.dart';
import '../../presentation/screens/admin/admin_home_screen.dart';
import '../../presentation/screens/admin/admin_login_screen.dart';
import '../../presentation/screens/admin/admin_query_details_screen.dart';
import '../../presentation/screens/admin/admin_settings_screen.dart';
import '../../presentation/screens/admin/admin_shell_screen.dart';
import '../../presentation/screens/admin/all_queries_screen.dart';
import '../../presentation/screens/admin/assign_expert_screen.dart';
import '../../presentation/screens/admin/bulk_email_log_screen.dart';
import '../../presentation/screens/admin/compose_email_screen.dart';
import '../../presentation/screens/admin/expert_details_screen.dart';
import '../../presentation/screens/admin/expert_list_screen.dart';
import '../../presentation/screens/admin/user_details_screen.dart';
import '../../presentation/screens/admin/user_list_screen.dart';
import '../../presentation/screens/common/login_screen.dart';
import '../../presentation/screens/common/onboarding_screen.dart';
import '../../presentation/screens/common/otp_verification_screen.dart';
import '../../presentation/screens/common/signup_screen.dart';
import '../../presentation/screens/common/splash_screen.dart';
import '../../presentation/screens/user/article_details_screen.dart';
import '../../presentation/screens/user/ask_query_form_screen.dart';
import '../../presentation/screens/user/edit_profile_screen.dart';
import '../../presentation/screens/user/expert_response_screen.dart';
import '../../presentation/screens/user/knowledge_center_screen.dart';
import '../../presentation/screens/user/my_queries_screen.dart';
import '../../presentation/screens/user/notifications_screen.dart';
import '../../presentation/screens/user/query_details_screen.dart';
import '../../presentation/screens/user/query_submitted_screen.dart';
import '../../presentation/screens/user/review_query_screen.dart';
import '../../presentation/screens/user/review_response_screen.dart';
import '../../presentation/screens/user/select_category_screen.dart';
import '../../presentation/screens/user/user_home_screen.dart';
import '../../presentation/screens/user/user_profile_screen.dart';
import '../../presentation/screens/user/user_shell_screen.dart';

final GlobalKey<NavigatorState> _rootNavigatorKey = GlobalKey<NavigatorState>(
  debugLabel: 'root',
);

class AppRouter {
  static final GoRouter router = GoRouter(
    navigatorKey: _rootNavigatorKey,
    initialLocation: '/',
    routes: [
      // Common & Auth
      GoRoute(path: '/', builder: (context, state) => const SplashScreen()),
      GoRoute(
        path: '/onboarding',
        builder: (context, state) => const OnboardingScreen(),
      ),
      GoRoute(path: '/login', builder: (context, state) => const LoginScreen()),
      GoRoute(
        path: '/signup',
        builder: (context, state) => const SignupScreen(),
      ),
      GoRoute(
        path: '/otp-verification',
        builder: (context, state) {
          final emailOrMobile = state.extra as String? ?? '';
          return OtpVerificationScreen(emailOrMobile: emailOrMobile);
        },
      ),
      GoRoute(
        path: '/admin/login',
        builder: (context, state) => const AdminLoginScreen(),
      ),

      // User Shell Navigation
      StatefulShellRoute.indexedStack(
        builder: (context, state, navigationShell) {
          return UserShellScreen(navigationShell: navigationShell);
        },
        branches: [
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/user/home',
                builder: (context, state) => const UserHomeScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/user/my-queries',
                builder: (context, state) => const MyQueriesScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/user/knowledge',
                builder: (context, state) => const KnowledgeCenterScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/user/profile',
                builder: (context, state) => const UserProfileScreen(),
              ),
            ],
          ),
        ],
      ),

      // User Detail / Flow Push Routes
      GoRoute(
        path: '/user/select-category',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) => const SelectCategoryScreen(),
      ),
      GoRoute(
        path: '/user/ask-query',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final cat = state.extra as CategoryModel?;
          return AskQueryFormScreen(initialCategory: cat);
        },
      ),
      GoRoute(
        path: '/user/query-submitted',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final q = state.extra as QueryModel?;
          return QuerySubmittedScreen(query: q);
        },
      ),
      GoRoute(
        path: '/user/review-query',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final formData = state.extra as Map<String, dynamic>;
          return ReviewQueryScreen(formData: formData);
        },
      ),
      GoRoute(
        path: '/user/query-details/:id',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final id = int.tryParse(state.pathParameters['id'] ?? '0') ?? 0;
          return QueryDetailsScreen(queryId: id);
        },
      ),
      GoRoute(
        path: '/user/expert-response/:id',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final q = state.extra as QueryModel;
          return ExpertResponseScreen(query: q);
        },
      ),
      GoRoute(
        path: '/user/review-response/:id',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final q = state.extra as QueryModel;
          return ReviewResponseScreen(query: q);
        },
      ),
      GoRoute(
        path: '/user/notifications',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) => const NotificationsScreen(),
      ),
      GoRoute(
        path: '/user/article-details/:id',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final article = state.extra as ArticleModel;
          return ArticleDetailsScreen(article: article);
        },
      ),
      GoRoute(
        path: '/user/edit-profile',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) => const EditProfileScreen(),
      ),

      // Admin Shell Navigation
      StatefulShellRoute.indexedStack(
        builder: (context, state, navigationShell) {
          return AdminShellScreen(navigationShell: navigationShell);
        },
        branches: [
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/admin/dashboard',
                builder: (context, state) => const AdminHomeScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/admin/queries',
                builder: (context, state) => const AllQueriesScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/admin/experts',
                builder: (context, state) => const ExpertListScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/admin/broadcast',
                builder: (context, state) => const ComposeEmailScreen(),
              ),
            ],
          ),
          StatefulShellBranch(
            routes: [
              GoRoute(
                path: '/admin/settings',
                builder: (context, state) => const AdminSettingsScreen(),
              ),
            ],
          ),
        ],
      ),

      // Admin Detail / Action Push Routes
      GoRoute(
        path: '/admin/query-details/:id',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final id = int.tryParse(state.pathParameters['id'] ?? '0') ?? 0;
          return AdminQueryDetailsScreen(queryId: id);
        },
      ),
      GoRoute(
        path: '/admin/assign-expert/:id',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final id = int.tryParse(state.pathParameters['id'] ?? '0') ?? 0;
          final query = state.extra as QueryModel?;
          return AssignExpertScreen(queryId: id, query: query);
        },
      ),
      GoRoute(
        path: '/admin/add-expert',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) => const AddExpertScreen(),
      ),
      GoRoute(
        path: '/admin/expert-details/:id',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final expert = state.extra as ExpertModel;
          return ExpertDetailsScreen(expert: expert);
        },
      ),
      GoRoute(
        path: '/admin/users',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) => const UserListScreen(),
      ),
      GoRoute(
        path: '/admin/user-details/:id',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) {
          final user = state.extra as UserModel;
          return UserDetailsScreen(user: user);
        },
      ),
      GoRoute(
        path: '/admin/bulk-email-logs',
        parentNavigatorKey: _rootNavigatorKey,
        builder: (context, state) => const BulkEmailLogScreen(),
      ),
    ],
  );
}
