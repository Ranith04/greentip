import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../blocs/auth/auth_bloc.dart';
import '../../blocs/auth/auth_state.dart';
import '../../blocs/query/query_bloc.dart';
import '../../blocs/user_dashboard/user_dashboard_bloc.dart';
import '../../widgets/cards/kpi_stat_card.dart';
import '../../widgets/cards/query_card.dart';
import '../../widgets/feedback/empty_state_widget.dart';

class UserHomeScreen extends StatefulWidget {
  const UserHomeScreen({super.key});

  @override
  State<UserHomeScreen> createState() => _UserHomeScreenState();
}

class _UserHomeScreenState extends State<UserHomeScreen> {
  @override
  void initState() {
    super.initState();
    context.read<UserDashboardBloc>().add(LoadUserDashboardEvent());
    context.read<QueryBloc>().add(const LoadMyQueriesEvent());
  }

  @override
  Widget build(BuildContext context) {
    final authState = context.watch<AuthBloc>().state;
    final userName = authState is Authenticated ? authState.user.name : 'User';

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Text(
          'Good Morning, $userName',
          style: const TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: AppColors.textPrimary,
          ),
        ),
        actions: [
          BlocBuilder<UserDashboardBloc, UserDashboardState>(
            builder: (context, state) {
              final unread = state is UserDashboardLoaded
                  ? state.dashboard.unreadNotificationsCount
                  : 0;

              return Stack(
                alignment: Alignment.center,
                children: [
                  IconButton(
                    icon: const Icon(
                      Icons.notifications_outlined,
                      size: 24,
                      color: AppColors.textPrimary,
                    ),
                    onPressed: () => context.push('/user/notifications'),
                  ),
                  if (unread > 0)
                    Positioned(
                      top: 10,
                      right: 10,
                      child: Container(
                        padding: const EdgeInsets.all(4),
                        decoration: const BoxDecoration(
                          color: AppColors.error,
                          shape: BoxShape.circle,
                        ),
                        child: Text(
                          '$unread',
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 9,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                ],
              );
            },
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          context.read<UserDashboardBloc>().add(RefreshUserDashboardEvent());
          context.read<QueryBloc>().add(const LoadMyQueriesEvent());
        },
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.all(20.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Search Bar
              TextField(
                decoration: InputDecoration(
                  hintText: 'Search GreenTIP...',
                  prefixIcon: const Icon(
                    Icons.search,
                    color: AppColors.textMuted,
                  ),
                  filled: true,
                  fillColor: Colors.white,
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: AppColors.border),
                  ),
                  enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: AppColors.border),
                  ),
                ),
              ),
              const SizedBox(height: 24),

              // Green Banner Card
              GestureDetector(
                onTap: () => context.push('/user/select-category'),
                child: Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: AppColors.primary,
                    borderRadius: BorderRadius.circular(16),
                    boxShadow: [
                      BoxShadow(
                        color: AppColors.primary.withValues(alpha: 0.3),
                        blurRadius: 10,
                        offset: const Offset(0, 4),
                      ),
                    ],
                  ),
                  child: const Row(
                    children: [
                      Expanded(
                        child: Text(
                          'Have an environmental question?\nAsk an Expert',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                            height: 1.4,
                          ),
                        ),
                      ),
                      Icon(Icons.arrow_forward_rounded, color: Colors.white),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 28),

              // My Queries Summary Stats
              const Text(
                'My Queries',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: AppColors.textPrimary,
                ),
              ),
              const SizedBox(height: 16),

              BlocBuilder<QueryBloc, QueryState>(
                builder: (context, state) {
                  int total = 0;
                  int pending = 0;
                  int answered = 0;

                  if (state is QueriesLoaded) {
                    total = state.queries.length;
                    pending = state.queries
                        .where(
                          (q) =>
                              q.statusName.toLowerCase() == 'pending' ||
                              q.statusName.toLowerCase() == 'submitted',
                        )
                        .length;
                    answered = state.queries
                        .where(
                          (q) =>
                              q.statusName.toLowerCase() == 'responded' ||
                              q.statusName.toLowerCase() == 'answered' ||
                              q.statusName.toLowerCase() == 'closed',
                        )
                        .length;
                  }

                  return Row(
                    children: [
                      Expanded(
                        child: KpiStatCard(
                          title: 'Total',
                          value: '$total',
                          icon: Icons.assignment_outlined,
                          iconColor: Colors.blue,
                          onTap: () => context.push('/user/my-queries'),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: KpiStatCard(
                          title: 'Pending',
                          value: '$pending',
                          icon: Icons.pending_actions_outlined,
                          iconColor: Colors.orange,
                          onTap: () => context.push('/user/my-queries'),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: KpiStatCard(
                          title: 'Answered',
                          value: '$answered',
                          icon: Icons.check_circle_outline,
                          iconColor: AppColors.primary,
                          onTap: () => context.push('/user/my-queries'),
                        ),
                      ),
                    ],
                  );
                },
              ),
              const SizedBox(height: 28),

              // Recent Queries Header
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'Recent Queries',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: AppColors.textPrimary,
                    ),
                  ),
                  TextButton(
                    onPressed: () => context.push('/user/my-queries'),
                    child: const Text(
                      'View All',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                        color: AppColors.primary,
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 8),

              // Recent Queries List
              BlocBuilder<QueryBloc, QueryState>(
                builder: (context, state) {
                  if (state is QueryLoading) {
                    return const Center(
                      child: Padding(
                        padding: EdgeInsets.all(20.0),
                        child: CircularProgressIndicator(
                          color: AppColors.primary,
                        ),
                      ),
                    );
                  }

                  if (state is QueryError) {
                    return Center(
                      child: Text(
                        'Failed to load queries',
                        style: TextStyle(color: AppColors.error),
                      ),
                    );
                  }

                  if (state is QueriesLoaded) {
                    if (state.queries.isEmpty) {
                      return const EmptyStateWidget(
                        icon: Icons.inbox_outlined,
                        title: 'No Queries Submitted Yet',
                        description:
                            'Ask a question to connect with our experts.',
                      );
                    }

                    // Show top 3 recent queries
                    final recent = state.queries.take(3).toList();
                    return ListView.separated(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      itemCount: recent.length,
                      separatorBuilder: (_, _) => const SizedBox(height: 12),
                      itemBuilder: (context, index) {
                        final query = recent[index];
                        return QueryCard(
                          query: query,
                          onTap: () =>
                              context.push('/user/query-details/${query.id}'),
                        );
                      },
                    );
                  }

                  return const SizedBox.shrink();
                },
              ),
            ],
          ),
        ),
      ),
    );
  }
}
