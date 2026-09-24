import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/query_model.dart';
import '../../widgets/buttons/app_buttons.dart';

class QuerySubmittedScreen extends StatelessWidget {
  final QueryModel? query;

  const QuerySubmittedScreen({super.key, this.query});

  @override
  Widget build(BuildContext context) {
    final queryId = query != null ? query!.id : 1001;
    final formattedRef = '#QT-2024-${queryId.toString().padLeft(4, '0')}';

    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 32.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Spacer(),
              // Animated checkmark container
              Container(
                width: 96,
                height: 96,
                decoration: BoxDecoration(
                  color: AppColors.successLight,
                  shape: BoxShape.circle,
                  boxShadow: [
                    BoxShadow(
                      color: AppColors.success.withValues(alpha: 0.25),
                      blurRadius: 24,
                      offset: const Offset(0, 8),
                    ),
                  ],
                ),
                child: const Icon(
                  Icons.check_circle_rounded,
                  size: 60,
                  color: AppColors.success,
                ),
              ),
              const SizedBox(height: 28),
              const Text(
                'Query Filed Successfully!',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 24,
                  fontWeight: FontWeight.bold,
                  color: AppColors.textPrimary,
                  letterSpacing: -0.5,
                ),
              ),
              const SizedBox(height: 12),
              const Text(
                'Your compliance inquiry has been officially submitted and routed for technical evaluation.',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 14,
                  color: AppColors.textSecondary,
                  height: 1.4,
                ),
              ),
              const SizedBox(height: 28),

              // Reference Badge
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 16,
                  vertical: 12,
                ),
                decoration: BoxDecoration(
                  color: AppColors.primaryContainer.withValues(alpha: 0.6),
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(
                    color: AppColors.primaryLight.withValues(alpha: 0.4),
                  ),
                ),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Icon(
                      Icons.confirmation_number_outlined,
                      size: 20,
                      color: AppColors.primaryDark,
                    ),
                    const SizedBox(width: 10),
                    Text(
                      formattedRef,
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: AppColors.primaryDark,
                        letterSpacing: 0.5,
                      ),
                    ),
                    const SizedBox(width: 8),
                    IconButton(
                      icon: const Icon(
                        Icons.copy_rounded,
                        size: 18,
                        color: AppColors.primaryDark,
                      ),
                      onPressed: () {
                        Clipboard.setData(ClipboardData(text: formattedRef));
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                            content: Text('Reference ID copied to clipboard'),
                            behavior: SnackBarBehavior.floating,
                          ),
                        );
                      },
                    ),
                  ],
                ),
              ),
              const Spacer(),

              // Actions
              AppPrimaryButton(
                text: 'Track Query Progress',
                onPressed: () {
                  context.go('/user/query-details/$queryId');
                },
              ),
              const SizedBox(height: 12),
              AppOutlineButton(
                text: 'Back to Dashboard',
                borderColor: AppColors.border,
                textColor: AppColors.textPrimary,
                onPressed: () {
                  context.go('/user/home');
                },
              ),
            ],
          ),
        ),
      ),
    );
  }
}
