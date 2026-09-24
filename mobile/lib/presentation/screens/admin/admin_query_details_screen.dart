import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/utils/date_formatter.dart';
import '../../../data/models/query_model.dart';
import '../../blocs/admin_query/admin_query_bloc.dart';
import '../../blocs/query/query_bloc.dart';
import '../../widgets/buttons/app_buttons.dart';
import '../../widgets/feedback/status_badge.dart';

class AdminQueryDetailsScreen extends StatefulWidget {
  final int queryId;

  const AdminQueryDetailsScreen({super.key, required this.queryId});

  @override
  State<AdminQueryDetailsScreen> createState() =>
      _AdminQueryDetailsScreenState();
}

class _AdminQueryDetailsScreenState extends State<AdminQueryDetailsScreen> {
  @override
  void initState() {
    super.initState();
    context.read<QueryBloc>().add(LoadQueryDetailsEvent(widget.queryId));
  }

  void _showStatusDialog(QueryModel query) {
    int selected = query.status;

    showDialog(
      context: context,
      builder: (ctx) => StatefulBuilder(
        builder: (context, setDialogState) => AlertDialog(
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(16),
          ),
          title: const Text(
            'Change Inquiry Status',
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              _statusRadio(
                0,
                'Submitted',
                selected,
                (v) => setDialogState(() => selected = v),
              ),
              _statusRadio(
                1,
                'Assigned to Expert',
                selected,
                (v) => setDialogState(() => selected = v),
              ),
              _statusRadio(
                2,
                'In Technical Review',
                selected,
                (v) => setDialogState(() => selected = v),
              ),
              _statusRadio(
                3,
                'Answered / Resolved',
                selected,
                (v) => setDialogState(() => selected = v),
              ),
              _statusRadio(
                4,
                'Closed & Archived',
                selected,
                (v) => setDialogState(() => selected = v),
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Cancel'),
            ),
            ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
              ),
              onPressed: () {
                context.read<AdminQueryBloc>().add(
                  UpdateQueryStatusEvent(queryId: query.id, status: selected),
                );
                Navigator.pop(ctx);
                context.read<QueryBloc>().add(
                  LoadQueryDetailsEvent(widget.queryId),
                );
              },
              child: const Text(
                'Update Status',
                style: TextStyle(color: Colors.white),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _statusRadio(
    int val,
    String label,
    int current,
    ValueChanged<int> onSelect,
  ) {
    // ignore: deprecated_member_use
    return RadioListTile<int>(
      value: val,
      // ignore: deprecated_member_use
      groupValue: current,
      title: Text(label, style: const TextStyle(fontSize: 13)),
      activeColor: AppColors.primary,
      contentPadding: EdgeInsets.zero,
      // ignore: deprecated_member_use
      onChanged: (v) {
        if (v != null) onSelect(v);
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Text(
          'Admin Triage: #QT-${widget.queryId.toString().padLeft(4, '0')}',
        ),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
          onPressed: () => context.pop(),
        ),
      ),
      body: BlocBuilder<QueryBloc, QueryState>(
        builder: (context, state) {
          if (state is QueryLoading) {
            return const Center(
              child: CircularProgressIndicator(color: AppColors.primary),
            );
          }

          if (state is QueryDetailsLoaded) {
            final query = state.query;

            return Column(
              children: [
                Expanded(
                  child: SingleChildScrollView(
                    padding: const EdgeInsets.all(20.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Status Header
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
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    query.categoryName ??
                                        'Environmental Compliance',
                                    style: const TextStyle(
                                      fontSize: 13,
                                      fontWeight: FontWeight.bold,
                                      color: AppColors.primaryDark,
                                    ),
                                  ),
                                  StatusBadge(status: query.status),
                                ],
                              ),
                              const SizedBox(height: 12),
                              Text(
                                query.title,
                                style: const TextStyle(
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                  height: 1.3,
                                ),
                              ),
                              const SizedBox(height: 8),
                              Text(
                                'Received on ${DateFormatter.formatWithTime(query.createdAt)}',
                                style: const TextStyle(
                                  fontSize: 12,
                                  color: AppColors.textMuted,
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 18),

                        // Submitter Industrial Unit Details
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
                                'Industrial Facility',
                                style: TextStyle(
                                  fontSize: 15,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              const Divider(height: 20),
                              _row('Submitter Name', query.userName),
                              _row('Organization', query.organization),
                              _row('Plant Facility', query.industryName),
                              _row('Sector', query.sector),
                              _row('State PCB', query.state),
                              _row('CTO / CTE Reg No', query.consentNumber),
                              _row('Corporate Email', query.userEmail),
                              _row('Contact Phone', query.userMobile),
                            ],
                          ),
                        ),
                        const SizedBox(height: 18),

                        // Full Query Body
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
                                'Inquiry Description',
                                style: TextStyle(
                                  fontSize: 15,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              const SizedBox(height: 10),
                              Text(
                                query.description,
                                style: const TextStyle(
                                  fontSize: 14,
                                  color: AppColors.textPrimary,
                                  height: 1.5,
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(height: 18),

                        // Assigned Expert Card
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
                                'Assigned Technical Specialist',
                                style: TextStyle(
                                  fontSize: 15,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              const Divider(height: 20),
                              if (query.assignedExpertName != null) ...[
                                Row(
                                  children: [
                                    CircleAvatar(
                                      backgroundColor:
                                          AppColors.primaryContainer,
                                      child: const Icon(
                                        Icons.person,
                                        color: AppColors.primaryDark,
                                      ),
                                    ),
                                    const SizedBox(width: 12),
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment:
                                            CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            query.assignedExpertName!,
                                            style: const TextStyle(
                                              fontSize: 14,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                          Text(
                                            query.assignedExpertSpecialization ??
                                                'Environmental Specialist',
                                            style: const TextStyle(
                                              fontSize: 12,
                                              color: AppColors.textSecondary,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ],
                                ),
                                if (query.assignedAt != null) ...[
                                  const SizedBox(height: 10),
                                  Text(
                                    'Assigned on: ${DateFormatter.formatWithTime(query.assignedAt)}',
                                    style: const TextStyle(
                                      fontSize: 11,
                                      color: AppColors.textMuted,
                                    ),
                                  ),
                                ],
                              ] else ...[
                                const Row(
                                  children: [
                                    Icon(
                                      Icons.warning_amber_rounded,
                                      color: AppColors.warning,
                                      size: 20,
                                    ),
                                    SizedBox(width: 8),
                                    Text(
                                      'No expert assigned yet. Needs immediate triage.',
                                      style: TextStyle(
                                        fontSize: 13,
                                        color: AppColors.warning,
                                        fontWeight: FontWeight.w600,
                                      ),
                                    ),
                                  ],
                                ),
                              ],
                            ],
                          ),
                        ),
                        const SizedBox(height: 18),
                      ],
                    ),
                  ),
                ),

                // Bottom Admin Actions
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    border: Border(top: BorderSide(color: AppColors.divider)),
                  ),
                  child: Row(
                    children: [
                      Expanded(
                        child: AppOutlineButton(
                          text: 'Change Status',
                          borderColor: AppColors.border,
                          textColor: AppColors.textPrimary,
                          icon: Icons.edit_note_rounded,
                          onPressed: () => _showStatusDialog(query),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: AppPrimaryButton(
                          text: query.assignedExpertId != null
                              ? 'Reassign Expert'
                              : 'Assign Expert',
                          icon: Icons.person_add_alt_1_rounded,
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
                ),
              ],
            );
          }

          return const SizedBox.shrink();
        },
      ),
    );
  }

  Widget _row(String label, String? value) {
    if (value == null || value.isEmpty) return const SizedBox.shrink();
    return Padding(
      padding: const EdgeInsets.only(bottom: 6.0),
      child: Row(
        children: [
          SizedBox(
            width: 120,
            child: Text(
              label,
              style: const TextStyle(fontSize: 12, color: AppColors.textMuted),
            ),
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
