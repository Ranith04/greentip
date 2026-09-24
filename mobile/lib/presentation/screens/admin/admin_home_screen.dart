import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/utils/date_formatter.dart';
import '../../blocs/admin_dashboard/admin_dashboard_bloc.dart';
import '../../widgets/cards/kpi_stat_card.dart';
import '../../widgets/cards/query_card.dart';
import '../../widgets/feedback/empty_state_widget.dart';

class AdminHomeScreen extends StatefulWidget {
  const AdminHomeScreen({super.key});

  @override
  State<AdminHomeScreen> createState() => _AdminHomeScreenState();
}

class _AdminHomeScreenState extends State<AdminHomeScreen> {
  @override
  void initState() {
    super.initState();
    context.read<AdminDashboardBloc>().add(LoadAdminDashboardEvent());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'GreenTIP Admin Console',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: AppColors.primaryDark,
              ),
            ),
            Text(
              DateFormatter.formatShort(DateTime.now()),
              style: const TextStyle(fontSize: 12, color: AppColors.textMuted),
            ),
          ],
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh_rounded),
            onPressed: () => context.read<AdminDashboardBloc>().add(
              RefreshAdminDashboardEvent(),
            ),
          ),
          IconButton(
            icon: const Icon(Icons.notifications_outlined),
            onPressed: () => context.push('/user/notifications'),
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          context.read<AdminDashboardBloc>().add(RefreshAdminDashboardEvent());
        },
        child: BlocBuilder<AdminDashboardBloc, AdminDashboardState>(
          builder: (context, state) {
            if (state is AdminDashboardLoading) {
              return const Center(
                child: CircularProgressIndicator(color: AppColors.primary),
              );
            }

            if (state is AdminDashboardError) {
              return Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(
                      Icons.error_outline,
                      size: 48,
                      color: AppColors.error,
                    ),
                    const SizedBox(height: 12),
                    Text(
                      state.message,
                      style: const TextStyle(color: AppColors.textSecondary),
                    ),
                    const SizedBox(height: 16),
                    ElevatedButton(
                      onPressed: () => context.read<AdminDashboardBloc>().add(
                        LoadAdminDashboardEvent(),
                      ),
                      child: const Text('Retry'),
                    ),
                  ],
                ),
              );
            }

            if (state is AdminDashboardLoaded) {
              final d = state.dashboard;

              return SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                padding: const EdgeInsets.symmetric(
                  horizontal: 20.0,
                  vertical: 16.0,
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // SLA breach alert
                    if (d.slaBreachedQueriesCount > 0) ...[
                      Container(
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: AppColors.errorLight,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(
                            color: AppColors.error.withValues(alpha: 0.4),
                          ),
                        ),
                        child: Row(
                          children: [
                            const Icon(
                              Icons.alarm_on_rounded,
                              color: AppColors.error,
                              size: 28,
                            ),
                            const SizedBox(width: 12),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    '${d.slaBreachedQueriesCount} Query Overdue / SLA Breached',
                                    style: const TextStyle(
                                      fontWeight: FontWeight.bold,
                                      fontSize: 13,
                                      color: AppColors.error,
                                    ),
                                  ),
                                  const SizedBox(height: 2),
                                  const Text(
                                    'Statutory response deadline exceeded. Immediate reassignment required.',
                                    style: TextStyle(
                                      fontSize: 11,
                                      color: Color(0xFF991B1B),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(height: 20),
                    ],

                    // Top KPI Row 1
                    Row(
                      children: [
                        Expanded(
                          child: KpiStatCard(
                            title: 'Total Inquiries',
                            value: '${d.totalQueries}',
                            icon: Icons.all_inbox_rounded,
                            iconColor: AppColors.primary,
                            onTap: () => context.go('/admin/queries'),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: KpiStatCard(
                            title: 'Unassigned',
                            value: '${d.unassignedQueries}',
                            icon: Icons.assignment_late_rounded,
                            iconColor: AppColors.error,
                            onTap: () => context.go('/admin/queries'),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),

                    // Top KPI Row 2
                    Row(
                      children: [
                        Expanded(
                          child: KpiStatCard(
                            title: 'In Review',
                            value: '${d.inProgressQueries}',
                            icon: Icons.hourglass_top_rounded,
                            iconColor: AppColors.warning,
                            onTap: () => context.go('/admin/queries'),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: KpiStatCard(
                            title: 'Resolved Today',
                            value: '${d.resolvedTodayQueries}',
                            icon: Icons.check_circle_rounded,
                            iconColor: AppColors.success,
                            onTap: () => context.go('/admin/queries'),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: KpiStatCard(
                            title: 'Experts',
                            value: '${d.activeExpertsCount}',
                            icon: Icons.people_rounded,
                            iconColor: AppColors.secondary,
                            onTap: () => context.go('/admin/experts'),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 28),

                    // Unassigned Queue
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text(
                          'Requires Expert Assignment',
                          style: TextStyle(
                            fontSize: 17,
                            fontWeight: FontWeight.bold,
                            color: AppColors.textPrimary,
                          ),
                        ),
                        Text(
                          '${d.recentUnassignedQueries.length} pending',
                          style: const TextStyle(
                            fontSize: 12,
                            fontWeight: FontWeight.bold,
                            color: AppColors.error,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),

                    if (d.recentUnassignedQueries.isEmpty)
                      Container(
                        width: double.infinity,
                        padding: const EdgeInsets.all(24),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(color: AppColors.divider),
                        ),
                        child: const Row(
                          children: [
                            Icon(
                              Icons.check_circle_outline_rounded,
                              color: AppColors.success,
                              size: 24,
                            ),
                            SizedBox(width: 12),
                            Text(
                              'All active compliance queries are assigned!',
                              style: TextStyle(
                                fontSize: 13,
                                fontWeight: FontWeight.w600,
                                color: AppColors.textSecondary,
                              ),
                            ),
                          ],
                        ),
                      )
                    else
                      ListView.separated(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: d.recentUnassignedQueries.length,
                        separatorBuilder: (_, _) => const SizedBox(height: 10),
                        itemBuilder: (context, index) {
                          final query = d.recentUnassignedQueries[index];
                          return Container(
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(16),
                              border: Border.all(color: AppColors.divider),
                            ),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      '#QT-${query.id.toString().padLeft(4, '0')}',
                                      style: const TextStyle(
                                        fontWeight: FontWeight.bold,
                                        color: AppColors.primary,
                                      ),
                                    ),
                                    Text(
                                      query.categoryName ?? 'Environmental',
                                      style: const TextStyle(
                                        fontSize: 12,
                                        color: AppColors.textMuted,
                                      ),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 6),
                                Text(
                                  query.title,
                                  style: const TextStyle(
                                    fontWeight: FontWeight.bold,
                                    fontSize: 14,
                                  ),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  'Unit: ${query.industryName ?? "Unspecified"} (${query.state ?? "SPCB"})',
                                  style: const TextStyle(
                                    fontSize: 12,
                                    color: AppColors.textSecondary,
                                  ),
                                ),
                                const SizedBox(height: 12),
                                SizedBox(
                                  width: double.infinity,
                                  child: ElevatedButton.icon(
                                    style: ElevatedButton.styleFrom(
                                      backgroundColor: AppColors.primary,
                                      foregroundColor: Colors.white,
                                      padding: const EdgeInsets.symmetric(
                                        vertical: 10,
                                      ),
                                      shape: RoundedRectangleBorder(
                                        borderRadius: BorderRadius.circular(10),
                                      ),
                                    ),
                                    icon: const Icon(
                                      Icons.person_add_alt_1_rounded,
                                      size: 16,
                                    ),
                                    label: const Text(
                                      'Assign Technical Expert',
                                      style: TextStyle(fontSize: 13),
                                    ),
                                    onPressed: () {
                                      context.push(
                                        '/admin/assign-expert/${query.id}',
                                        extra: query,
                                      );
                                    },
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                      ),
                    const SizedBox(height: 28),

                    // Recent Inquiries
                    const Text(
                      'Live Compliance Stream',
                      style: TextStyle(
                        fontSize: 17,
                        fontWeight: FontWeight.bold,
                        color: AppColors.textPrimary,
                      ),
                    ),
                    const SizedBox(height: 12),

                    if (d.recentQueries.isEmpty)
                      const EmptyStateWidget(
                        icon: Icons.inbox_rounded,
                        title: 'No Queries Registered',
                        description:
                            'Incoming queries from industrial plants will show here.',
                      )
                    else
                      ListView.separated(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: d.recentQueries.length,
                        separatorBuilder: (_, _) => const SizedBox(height: 12),
                        itemBuilder: (context, index) {
                          final query = d.recentQueries[index];
                          return QueryCard(
                            query: query,
                            onTap: () => context.push(
                              '/admin/query-details/${query.id}',
                            ),
                          );
                        },
                      ),
                  ],
                ),
              );
            }

            return const SizedBox.shrink();
          },
        ),
      ),
    );
  }
}
