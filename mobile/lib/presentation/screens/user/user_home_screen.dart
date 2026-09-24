import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../blocs/auth/auth_bloc.dart';
import '../../blocs/auth/auth_state.dart';
import '../../blocs/query/query_bloc.dart';
import '../../blocs/user_dashboard/user_dashboard_bloc.dart';
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
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        toolbarHeight: 80,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Good Morning,',
              style: TextStyle(
                fontSize: 14,
                color: Color(0xFF6B7280),
              ),
            ),
            const SizedBox(height: 2),
            Row(
              children: [
                Text(
                  userName,
                  style: const TextStyle(
                    fontSize: 22,
                    fontWeight: FontWeight.w900,
                    color: Colors.black,
                  ),
                ),
                const SizedBox(width: 8),
                const Text('👋', style: TextStyle(fontSize: 20)),
              ],
            ),
          ],
        ),
        actions: [
          Padding(
            padding: const EdgeInsets.only(right: 16.0),
            child: Container(
              decoration: const BoxDecoration(
                color: Color(0xFFEDF6F1),
                shape: BoxShape.circle,
              ),
              child: IconButton(
                icon: const Icon(
                  Icons.notifications_none_rounded,
                  color: Color(0xFF034D2A),
                  size: 26,
                ),
                onPressed: () => context.push('/user/notifications'),
              ),
            ),
          ),
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
              Container(
                decoration: BoxDecoration(
                  color: const Color(0xFFECF5F0),
                  borderRadius: BorderRadius.circular(28),
                ),
                child: TextField(
                  decoration: InputDecoration(
                    hintText: 'Search GreenTIP...',
                    hintStyle: const TextStyle(color: Color(0xFF8BA596)),
                    prefixIcon: const Icon(
                      Icons.search,
                      color: Color(0xFF8BA596),
                    ),
                    border: InputBorder.none,
                    contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
                  ),
                ),
              ),
              const SizedBox(height: 24),

              // Green Banner Card
              GestureDetector(
                onTap: () => context.push('/user/select-category'),
                child: Container(
                  width: double.infinity,
                  height: 140,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(20),
                    image: const DecorationImage(
                      image: AssetImage('assets/images/banner_leaves.jpg'),
                      fit: BoxFit.cover,
                    ),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withValues(alpha: 0.1),
                        blurRadius: 10,
                        offset: const Offset(0, 4),
                      ),
                    ],
                  ),
                  child: Padding(
                    padding: const EdgeInsets.all(20.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Text(
                          'Have an\nenvironmental\nquestion?',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 18,
                            fontWeight: FontWeight.w900,
                            height: 1.2,
                          ),
                        ),
                        const SizedBox(height: 12),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: const Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Text(
                                'Ask an Expert',
                                style: TextStyle(
                                  color: Color(0xFF034D2A),
                                  fontWeight: FontWeight.bold,
                                  fontSize: 12,
                                ),
                              ),
                              SizedBox(width: 4),
                              Icon(Icons.arrow_forward, color: Color(0xFF034D2A), size: 14),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 32),

              // My Queries Header
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'My Queries',
                    style: TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.w900,
                      color: Colors.black,
                    ),
                  ),
                  TextButton(
                    onPressed: () => context.push('/user/my-queries'),
                    child: const Text(
                      'View All',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: Color(0xFF288B46),
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),

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
                        child: _buildStatCard(
                          title: 'Total',
                          value: '$total',
                          valueColor: const Color(0xFF1D4ED8),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: _buildStatCard(
                          title: 'Pending',
                          value: '$pending',
                          valueColor: const Color(0xFFEA580C),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: _buildStatCard(
                          title: 'Answered',
                          value: '$answered',
                          valueColor: const Color(0xFF047857),
                        ),
                      ),
                    ],
                  );
                },
              ),
              const SizedBox(height: 32),

              // Recent Queries Header
              const Text(
                'Recent Queries',
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.w900,
                  color: Colors.black,
                ),
              ),
              const SizedBox(height: 16),

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
                        description: 'Ask a question to connect with our experts.',
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
                        return _buildRecentQueryCard(query, context);
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

  Widget _buildStatCard({required String title, required String value, required Color valueColor}) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.02),
            blurRadius: 10,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        children: [
          Text(
            value,
            style: TextStyle(
              fontSize: 28,
              fontWeight: FontWeight.w900,
              color: valueColor,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            title,
            style: const TextStyle(
              fontSize: 12,
              color: Color(0xFF6B7280),
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildRecentQueryCard(dynamic query, BuildContext context) {
    return GestureDetector(
      onTap: () => context.push('/user/query-details/${query.id}'),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: const Color(0xFFE2E8F0)),
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            Container(
              width: 48,
              height: 48,
              decoration: const BoxDecoration(
                color: Color(0xFFF3F0FF),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.eco, color: Color(0xFF6D28D9)),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    query.queryCode ?? 'Do I require EC for my proje...', // truncate title logic
                    style: const TextStyle(
                      fontWeight: FontWeight.bold,
                      fontSize: 16,
                      color: Colors.black,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 4),
                  Text(
                    query.categoryName ?? 'Environmental Clearance',
                    style: const TextStyle(
                      fontSize: 13,
                      color: Color(0xFF6B7280),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    'Sep 12, 2026', // Format date
                    style: const TextStyle(
                      fontSize: 12,
                      color: Color(0xFF9CA3AF),
                    ),
                  ),
                ],
              ),
            ),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
              decoration: BoxDecoration(
                color: const Color(0xFF8B5CF6),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Text(
                query.statusName ?? 'Responded',
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 11,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
