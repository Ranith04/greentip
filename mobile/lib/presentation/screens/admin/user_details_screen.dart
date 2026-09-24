import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/query_model.dart';
import '../../../data/models/user_model.dart';
import '../../widgets/cards/query_card.dart';

class UserDetailsScreen extends StatelessWidget {
  final UserModel user;

  const UserDetailsScreen({super.key, required this.user});

  @override
  Widget build(BuildContext context) {
    final mockUserQueries = [
      QueryModel(
        id: 1042,
        userId: user.id,
        userName: user.name,
        organization: user.organization,
        title:
            'Consent to Operate (CTO) renewal application regarding ZLD parameter limits',
        description:
            'Need clarification on MPCB circular dated 14th August regarding high TDS discharge.',
        categoryName: 'Water Pollution & Effluents',
        status: 2,
        statusName: 'In Review',
        createdAt: DateTime.now().subtract(const Duration(days: 3)),
      ),
      QueryModel(
        id: 1028,
        userId: user.id,
        userName: user.name,
        organization: user.organization,
        title: 'Stack height norms for new 500 kVA standby DG set',
        description:
            'Acoustic enclosure specification and flue gas monitoring port requirement.',
        categoryName: 'Air Emissions & Stack Monitoring',
        status: 3,
        statusName: 'Answered',
        createdAt: DateTime.now().subtract(const Duration(days: 14)),
      ),
    ];

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Text(user.name),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
          onPressed: () => context.pop(),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // User Card
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
                    radius: 32,
                    backgroundColor: AppColors.primaryContainer,
                    child: Text(
                      user.name.isNotEmpty ? user.name[0].toUpperCase() : 'U',
                      style: const TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: AppColors.primaryDark,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    user.name,
                    style: const TextStyle(
                      fontSize: 17,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    user.organization ?? 'Industrial Facility',
                    style: const TextStyle(
                      fontSize: 13,
                      color: AppColors.primaryDark,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const Divider(height: 24),
                  _row(
                    Icons.badge_outlined,
                    'Designation',
                    user.designation ?? 'Compliance Officer',
                  ),
                  _row(Icons.email_outlined, 'Corporate Email', user.email),
                  _row(Icons.phone_outlined, 'Mobile Contact', user.mobile),
                  _row(
                    Icons.verified_user_outlined,
                    'Account Status',
                    user.status == 1
                        ? 'Active / In Good Standing'
                        : 'Suspended',
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Query History Section (Admin Screen 12)
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  'Inquiry History for Facility',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: AppColors.textPrimary,
                  ),
                ),
                Text(
                  '${mockUserQueries.length} total',
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                    color: AppColors.primary,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),

            ListView.separated(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: mockUserQueries.length,
              separatorBuilder: (_, _) => const SizedBox(height: 10),
              itemBuilder: (context, index) {
                final q = mockUserQueries[index];
                return QueryCard(
                  query: q,
                  onTap: () => context.push('/admin/query-details/${q.id}'),
                );
              },
            ),
          ],
        ),
      ),
    );
  }

  Widget _row(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8.0),
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
}
