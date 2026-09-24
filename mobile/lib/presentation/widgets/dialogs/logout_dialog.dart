import 'package:flutter/material.dart';
import '../../../core/constants/app_colors.dart';
import '../buttons/app_buttons.dart';

class LogoutDialog extends StatelessWidget {
  final VoidCallback onConfirm;
  final bool isAdmin;

  const LogoutDialog({
    super.key,
    required this.onConfirm,
    this.isAdmin = false,
  });

  static Future<void> show(
    BuildContext context, {
    required VoidCallback onConfirm,
    bool isAdmin = false,
  }) {
    return showDialog(
      context: context,
      builder: (ctx) => LogoutDialog(onConfirm: onConfirm, isAdmin: isAdmin),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      backgroundColor: Colors.white,
      insetPadding: const EdgeInsets.all(24),
      child: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              padding: const EdgeInsets.all(16),
              decoration: const BoxDecoration(
                color: AppColors.errorLight,
                shape: BoxShape.circle,
              ),
              child: const Icon(
                Icons.logout_rounded,
                color: AppColors.error,
                size: 32,
              ),
            ),
            const SizedBox(height: 18),
            Text(
              isAdmin ? 'Sign Out of Admin Console?' : 'Log Out from GreenTIP?',
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppColors.textPrimary,
              ),
            ),
            const SizedBox(height: 10),
            Text(
              isAdmin
                  ? 'Your active administrative session will be securely terminated. Any unsaved drafts will be cleared.'
                  : 'You will need to sign back in with your credentials to access your queries and compliance alerts.',
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 13,
                color: AppColors.textSecondary,
                height: 1.4,
              ),
            ),
            const SizedBox(height: 24),
            Row(
              children: [
                Expanded(
                  child: AppOutlineButton(
                    text: 'Cancel',
                    borderColor: AppColors.border,
                    textColor: AppColors.textPrimary,
                    onPressed: () => Navigator.of(context).pop(),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: AppPrimaryButton(
                    text: 'Sign Out',
                    backgroundColor: AppColors.error,
                    onPressed: () {
                      Navigator.of(context).pop();
                      onConfirm();
                    },
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
