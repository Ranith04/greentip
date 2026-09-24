import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/expert_model.dart';
import '../../widgets/buttons/app_buttons.dart';
import '../../widgets/cards/kpi_stat_card.dart';

class ExpertDetailsScreen extends StatelessWidget {
  final ExpertModel expert;

  const ExpertDetailsScreen({super.key, required this.expert});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Text(expert.name),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
          onPressed: () => context.pop(),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          children: [
            // Profile Card
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: AppColors.divider),
              ),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 36,
                    backgroundColor: AppColors.primaryContainer,
                    child: const Icon(
                      Icons.person,
                      size: 40,
                      color: AppColors.primaryDark,
                    ),
                  ),
                  const SizedBox(height: 14),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(
                        expert.name,
                        style: const TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(width: 6),
                      const Icon(
                        Icons.verified,
                        size: 18,
                        color: AppColors.primary,
                      ),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Text(
                    expert.specialization ??
                        'Environmental Compliance Specialist',
                    textAlign: TextAlign.center,
                    style: const TextStyle(
                      fontSize: 13,
                      color: AppColors.primaryDark,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 12),
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 10,
                      vertical: 4,
                    ),
                    decoration: BoxDecoration(
                      color: expert.isActive
                          ? AppColors.successLight
                          : AppColors.errorLight,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      expert.isActive
                          ? 'Active on Roster'
                          : 'Inactive / On Leave',
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: expert.isActive
                            ? AppColors.success
                            : AppColors.error,
                      ),
                    ),
                  ),
                  const Divider(height: 28),
                  _infoRow(
                    Icons.email_outlined,
                    'Official Email',
                    expert.email,
                  ),
                  _infoRow(
                    Icons.phone_outlined,
                    'Direct Contact',
                    expert.mobile,
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Performance KPI Grid
            Row(
              children: [
                Expanded(
                  child: KpiStatCard(
                    title: 'Active Workload',
                    value: '${expert.activeAssignedQueriesCount}',
                    icon: Icons.assignment_outlined,
                    iconColor: AppColors.warning,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: KpiStatCard(
                    title: 'Total Resolved',
                    value: '${expert.totalResolvedQueriesCount}',
                    icon: Icons.check_circle_outline,
                    iconColor: AppColors.success,
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: KpiStatCard(
                    title: 'User Rating',
                    value: '${expert.averageRating.toStringAsFixed(1)} ★',
                    icon: Icons.star_rounded,
                    iconColor: const Color(0xFFFFB300),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 24),

            // Workload Allocation Info Box
            Container(
              padding: const EdgeInsets.all(18),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: AppColors.divider),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Roster Capacity & SLA Standing',
                    style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 12),
                  _statItem('SLA Compliance Rate', '98.2% on-time turnaround'),
                  _statItem(
                    'Average Response Time',
                    '26.4 hours (Standard SLA 48h)',
                  ),
                  _statItem('Appeals / Re-reviews', '0 escalated cases'),
                  const SizedBox(height: 16),
                  AppOutlineButton(
                    text: 'View Assigned Inquiry Tickets',
                    icon: Icons.list_alt_rounded,
                    onPressed: () {
                      context.push('/admin/queries');
                    },
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _infoRow(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 10.0),
      child: Row(
        children: [
          Icon(icon, size: 16, color: AppColors.textMuted),
          const SizedBox(width: 10),
          Text(
            '$label: ',
            style: const TextStyle(fontSize: 12, color: AppColors.textMuted),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.w600,
                color: AppColors.textPrimary,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _statItem(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 13,
              color: AppColors.textSecondary,
            ),
          ),
          Text(
            value,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.bold,
              color: AppColors.textPrimary,
            ),
          ),
        ],
      ),
    );
  }
}
