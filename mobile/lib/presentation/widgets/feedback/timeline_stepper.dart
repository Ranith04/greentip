import 'package:flutter/material.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/utils/date_formatter.dart';
import '../../../data/models/query_model.dart';

class TimelineStepper extends StatelessWidget {
  final QueryModel query;

  const TimelineStepper({super.key, required this.query});

  @override
  Widget build(BuildContext context) {
    final stages = [
      _TimelineStep(
        title: 'Submitted',
        description: 'Query filed and statutory reference generated',
        date: query.createdAt,
        isCompleted: query.status >= 0,
        isActive: query.status == 0,
      ),
      _TimelineStep(
        title: 'Assigned to Expert',
        description: query.assignedExpertName != null
            ? 'Assigned to ${query.assignedExpertName} (${query.assignedExpertSpecialization ?? "Specialist"})'
            : 'Pending expert assignment by admin',
        date: query.assignedAt,
        isCompleted: query.status >= 1,
        isActive: query.status == 1,
      ),
      _TimelineStep(
        title: 'Expert Responded',
        description: 'Expert provided technical recommendation and response',
        date: query.assignedAt?.add(const Duration(hours: 2)),
        isCompleted: query.status >= 2,
        isActive: query.status == 2,
      ),
      _TimelineStep(
        title: 'Closed',
        description: query.status == 3
            ? 'Query resolved and closed by user/admin'
            : 'Pending final resolution',
        date: query.respondedAt,
        isCompleted: query.status >= 3,
        isActive: query.status >= 3,
      ),
    ];

    return Column(
      children: List.generate(stages.length, (index) {
        final step = stages[index];
        final isLast = index == stages.length - 1;

        return Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Indicator and connecting line
            Column(
              children: [
                Container(
                  width: 28,
                  height: 28,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: step.isCompleted
                        ? AppColors.primary
                        : Colors.grey.shade200,
                    border: Border.all(
                      color: step.isActive
                          ? AppColors.primaryLight
                          : Colors.transparent,
                      width: 2,
                    ),
                  ),
                  child: Center(
                    child: step.isCompleted
                        ? const Icon(Icons.check, size: 16, color: Colors.white)
                        : Text(
                            '${index + 1}',
                            style: TextStyle(
                              fontSize: 12,
                              fontWeight: FontWeight.bold,
                              color: Colors.grey.shade600,
                            ),
                          ),
                  ),
                ),
                if (!isLast)
                  Container(
                    width: 2,
                    height: 50,
                    color: step.isCompleted
                        ? AppColors.primary
                        : Colors.grey.shade200,
                  ),
              ],
            ),
            const SizedBox(width: 14),
            // Step content
            Expanded(
              child: Padding(
                padding: const EdgeInsets.only(top: 2.0, bottom: 20.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          step.title,
                          style: TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.w600,
                            color: step.isCompleted
                                ? AppColors.textPrimary
                                : AppColors.textMuted,
                          ),
                        ),
                        if (step.date != null && step.isCompleted)
                          Text(
                            DateFormatter.formatShort(step.date),
                            style: const TextStyle(
                              fontSize: 11,
                              color: AppColors.textMuted,
                            ),
                          ),
                      ],
                    ),
                    const SizedBox(height: 4),
                    Text(
                      step.description,
                      style: TextStyle(
                        fontSize: 12,
                        color: step.isCompleted
                            ? AppColors.textSecondary
                            : AppColors.textMuted,
                        height: 1.3,
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        );
      }),
    );
  }
}

class _TimelineStep {
  final String title;
  final String description;
  final DateTime? date;
  final bool isCompleted;
  final bool isActive;

  _TimelineStep({
    required this.title,
    required this.description,
    this.date,
    required this.isCompleted,
    required this.isActive,
  });
}
