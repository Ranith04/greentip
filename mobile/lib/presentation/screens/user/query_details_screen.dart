import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/utils/date_formatter.dart';
import '../../../data/models/query_model.dart';
import '../../blocs/query/query_bloc.dart';
import '../../widgets/feedback/timeline_stepper.dart';

class QueryDetailsScreen extends StatefulWidget {
  final int queryId;

  const QueryDetailsScreen({super.key, required this.queryId});

  @override
  State<QueryDetailsScreen> createState() => _QueryDetailsScreenState();
}

class _QueryDetailsScreenState extends State<QueryDetailsScreen> {
  @override
  void initState() {
    super.initState();
    context.read<QueryBloc>().add(LoadQueryDetailsEvent(widget.queryId));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF1F8F4), // Light mint background
      body: SafeArea(
        child: Column(
          children: [
            // Header
            Padding(
              padding: const EdgeInsets.fromLTRB(20, 16, 20, 0),
              child: Row(
                children: [
                  GestureDetector(
                    onTap: () => context.pop(),
                    child: const Icon(Icons.chevron_left, size: 28, color: Colors.black),
                  ),
                  const SizedBox(width: 8),
                  Text(
                    '#QT-${widget.queryId.toString().padLeft(4, '0')}',
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      color: Colors.black,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            Expanded(
              child: BlocBuilder<QueryBloc, QueryState>(
                builder: (context, state) {
                  if (state is QueryLoading) {
                    return const Center(
                      child: CircularProgressIndicator(color: Color(0xFF0F6B35)),
                    );
                  }

                  if (state is QueryError) {
                    return Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(Icons.error_outline, size: 48, color: AppColors.error),
                          const SizedBox(height: 12),
                          Text(state.message, style: const TextStyle(color: AppColors.textSecondary)),
                          const SizedBox(height: 16),
                          ElevatedButton(
                            onPressed: () => context.read<QueryBloc>().add(LoadQueryDetailsEvent(widget.queryId)),
                            child: const Text('Retry'),
                          ),
                        ],
                      ),
                    );
                  }

                  if (state is QueryDetailsLoaded) {
                    return _buildContent(state.query);
                  }

                  return const SizedBox.shrink();
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildContent(QueryModel query) {
    // Status colors
    Color statusBgColor;
    Color statusTextColor;
    String statusLabel = query.statusName ?? 'Pending';

    switch (statusLabel.toLowerCase()) {
      case 'responded':
      case 'answered':
        statusBgColor = const Color(0xFF0F6B35);
        statusTextColor = Colors.white;
        statusLabel = 'Responded';
        break;
      case 'pending':
      case 'submitted':
        statusBgColor = const Color(0xFFEA580C);
        statusTextColor = Colors.white;
        statusLabel = 'Pending';
        break;
      case 'in progress':
      case 'assigned':
        statusBgColor = const Color(0xFF1D4ED8);
        statusTextColor = Colors.white;
        statusLabel = 'In Progress';
        break;
      case 'closed':
        statusBgColor = const Color(0xFF475569);
        statusTextColor = Colors.white;
        statusLabel = 'Closed';
        break;
      default:
        statusBgColor = const Color(0xFF6B7280);
        statusTextColor = Colors.white;
    }

    return Column(
      children: [
        Expanded(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 20.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Top Card
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: const Color(0xFFE2E8F0)),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              color: const Color(0xFFE8F5E9),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              query.categoryName ?? 'General',
                              style: const TextStyle(
                                fontSize: 12,
                                fontWeight: FontWeight.w700,
                                color: Color(0xFF0F6B35),
                              ),
                            ),
                          ),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                            decoration: BoxDecoration(
                              color: statusBgColor,
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              statusLabel,
                              style: TextStyle(
                                fontSize: 12,
                                fontWeight: FontWeight.w600,
                                color: statusTextColor,
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      Text(
                        query.title,
                        style: const TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.w800,
                          color: Colors.black,
                          height: 1.3,
                        ),
                      ),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          const Icon(Icons.calendar_today_outlined, size: 16, color: Color(0xFF6B7280)),
                          const SizedBox(width: 8),
                          Text(
                            'Filed on ${DateFormatter.formatWithTime(query.createdAt)}',
                            style: const TextStyle(
                              fontSize: 13,
                              color: Color(0xFF6B7280),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 20),

                // Resolution Timeline
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: const Color(0xFFE2E8F0)),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Resolution Timeline',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.w800,
                          color: Colors.black,
                        ),
                      ),
                      const SizedBox(height: 24),
                      TimelineStepper(query: query),
                    ],
                  ),
                ),
                const SizedBox(height: 20),

                // Original Query Details
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: const Color(0xFFE2E8F0)),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Original Query Details',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.w800,
                          color: Colors.black,
                        ),
                      ),
                      const SizedBox(height: 20),
                      
                      _buildDetailField('Description', query.description),
                      const SizedBox(height: 16),
                      _buildDetailField('Project Type', query.sector),
                      const SizedBox(height: 16),
                      _buildDetailField('Location', query.state),
                      const SizedBox(height: 16),
                      _buildDetailField('Project Area (sq m)', query.consentNumber),
                    ],
                  ),
                ),
                const SizedBox(height: 20),

                // Attachments
                if (query.attachments.isNotEmpty) ...[
                  Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Attached Documents (${query.attachments.length})',
                          style: const TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.w800,
                            color: Colors.black,
                          ),
                        ),
                        const SizedBox(height: 16),
                        ...query.attachments.map(
                          (a) => Container(
                            margin: const EdgeInsets.only(bottom: 12),
                            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                            decoration: BoxDecoration(
                              color: const Color(0xFFF9FAFB),
                              borderRadius: BorderRadius.circular(14),
                              border: Border.all(color: const Color(0xFFE2E8F0)),
                            ),
                            child: Row(
                              children: [
                                const Icon(Icons.description_outlined, color: Color(0xFF0F6B35), size: 24),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Text(
                                    a.fileName,
                                    maxLines: 1,
                                    overflow: TextOverflow.ellipsis,
                                    style: const TextStyle(fontSize: 14, color: Colors.black, fontWeight: FontWeight.w500),
                                  ),
                                ),
                                const Icon(Icons.download_rounded, color: Color(0xFF6B7280), size: 20),
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 20),
                ],
              ],
            ),
          ),
        ),

        // Bottom Action Bar
        Container(
          padding: const EdgeInsets.all(20),
          decoration: const BoxDecoration(
            color: Colors.white,
            border: Border(top: BorderSide(color: Color(0xFFE2E8F0))),
          ),
          child: query.status >= 3
              ? SizedBox(
                  width: double.infinity,
                  height: 56,
                  child: ElevatedButton.icon(
                    onPressed: () {
                      context.push('/user/expert-response/${query.id}', extra: query);
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF0F6B35),
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(28),
                      ),
                      elevation: 0,
                    ),
                    icon: const Icon(Icons.verified_rounded, size: 20),
                    label: const Text(
                      'View Expert Opinion & Resolution',
                      style: TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                )
              : Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                  decoration: BoxDecoration(
                    color: const Color(0xFFE8F5E9),
                    borderRadius: BorderRadius.circular(14),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.info_outline_rounded, color: Color(0xFF0F6B35), size: 22),
                      const SizedBox(width: 12),
                      const Expanded(
                        child: Text(
                          'Assigned expert is evaluating this statutory case. You will be notified upon resolution.',
                          style: TextStyle(
                            fontSize: 13,
                            color: Color(0xFF0F6B35),
                            fontWeight: FontWeight.w500,
                            height: 1.4,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
        ),
      ],
    );
  }

  Widget _buildDetailField(String label, String? value) {
    if (value == null || value.isEmpty) return const SizedBox.shrink();
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(
            fontSize: 14,
            fontWeight: FontWeight.w600,
            color: Colors.black,
          ),
        ),
        const SizedBox(height: 8),
        Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          decoration: BoxDecoration(
            color: const Color(0xFFF9FAFB),
            borderRadius: BorderRadius.circular(14),
            border: Border.all(color: const Color(0xFFE2E8F0)),
          ),
          child: Text(
            value,
            style: const TextStyle(
              fontSize: 15,
              color: Colors.black,
              height: 1.4,
            ),
          ),
        ),
      ],
    );
  }
}
