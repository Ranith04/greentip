import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/utils/date_formatter.dart';
import '../../../data/models/bulk_email_model.dart';
import '../../blocs/bulk_email/bulk_email_bloc.dart';
import '../../widgets/feedback/empty_state_widget.dart';

class BulkEmailLogScreen extends StatefulWidget {
  const BulkEmailLogScreen({super.key});

  @override
  State<BulkEmailLogScreen> createState() => _BulkEmailLogScreenState();
}

class _BulkEmailLogScreenState extends State<BulkEmailLogScreen> {
  final List<BulkEmailLogModel> _fallbackLogs = [
    BulkEmailLogModel(
      id: 1,
      subject: 'Urgent Advisory: ZLD Audit & Online Monitoring Integration',
      message:
          'All chemical and pharmaceutical units must submit CEMS calibration certificates.',
      targetAudience: 'Chemical & Petrochemical Sector',
      recipientCount: 58,
      sentAt: DateTime.now().subtract(const Duration(days: 2)),
      status: 'Delivered',
      senderName: 'Principal Secretary (Env)',
    ),
    BulkEmailLogModel(
      id: 2,
      subject: 'Annual Statutory Return (Form V) Submission Deadline Notice',
      message:
          'Reminder regarding mandatory submission of Environmental Statements by 30th September.',
      targetAudience: 'All Registered Facilities',
      recipientCount: 142,
      sentAt: DateTime.now().subtract(const Duration(days: 7)),
      status: 'Delivered',
      senderName: 'Admin Desk',
    ),
    BulkEmailLogModel(
      id: 3,
      subject: 'Hazardous Waste TSDF Manifest Digital Protocol',
      message:
          'Adoption of Form 10 online QR manifest for transboundary waste movement.',
      targetAudience: 'Units with Pending Inquiries',
      recipientCount: 34,
      sentAt: DateTime.now().subtract(const Duration(days: 15)),
      status: 'Delivered',
      senderName: 'Technical Director',
    ),
  ];

  @override
  void initState() {
    super.initState();
    context.read<BulkEmailBloc>().add(LoadBulkEmailLogsEvent());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('Broadcast Dispatch Audit Logs'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
          onPressed: () => context.pop(),
        ),
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          context.read<BulkEmailBloc>().add(LoadBulkEmailLogsEvent());
        },
        child: BlocBuilder<BulkEmailBloc, BulkEmailState>(
          builder: (context, state) {
            List<BulkEmailLogModel> logs = _fallbackLogs;
            if (state is BulkEmailLogsLoaded && state.logs.isNotEmpty) {
              logs = state.logs;
            }

            if (logs.isEmpty) {
              return const EmptyStateWidget(
                icon: Icons.mark_email_read_outlined,
                title: 'No Broadcasts Dispatched',
                description:
                    'Sent email circulars will appear here with delivery audit counts.',
              );
            }

            return ListView.separated(
              padding: const EdgeInsets.all(16.0),
              itemCount: logs.length,
              separatorBuilder: (_, _) => const SizedBox(height: 12),
              itemBuilder: (context, index) {
                final log = logs[index];

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
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(
                              horizontal: 8,
                              vertical: 3,
                            ),
                            decoration: BoxDecoration(
                              color: AppColors.primaryContainer,
                              borderRadius: BorderRadius.circular(6),
                            ),
                            child: Text(
                              log.targetAudience,
                              style: const TextStyle(
                                fontSize: 11,
                                fontWeight: FontWeight.bold,
                                color: AppColors.primaryDark,
                              ),
                            ),
                          ),
                          Container(
                            padding: const EdgeInsets.symmetric(
                              horizontal: 8,
                              vertical: 3,
                            ),
                            decoration: BoxDecoration(
                              color: AppColors.successLight,
                              borderRadius: BorderRadius.circular(6),
                            ),
                            child: Text(
                              '${log.recipientCount} Sent • ${log.status}',
                              style: const TextStyle(
                                fontSize: 11,
                                fontWeight: FontWeight.bold,
                                color: AppColors.success,
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 12),
                      Text(
                        log.subject,
                        style: const TextStyle(
                          fontSize: 15,
                          fontWeight: FontWeight.bold,
                          color: AppColors.textPrimary,
                        ),
                      ),
                      const SizedBox(height: 6),
                      Text(
                        log.message,
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(
                          fontSize: 13,
                          color: AppColors.textSecondary,
                          height: 1.3,
                        ),
                      ),
                      const Divider(height: 20),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            'Dispatched: ${DateFormatter.formatShort(log.sentAt)}',
                            style: const TextStyle(
                              fontSize: 11,
                              color: AppColors.textMuted,
                            ),
                          ),
                          if (log.senderName != null)
                            Text(
                              'By: ${log.senderName}',
                              style: const TextStyle(
                                fontSize: 11,
                                color: AppColors.textMuted,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                        ],
                      ),
                    ],
                  ),
                );
              },
            );
          },
        ),
      ),
    );
  }
}
