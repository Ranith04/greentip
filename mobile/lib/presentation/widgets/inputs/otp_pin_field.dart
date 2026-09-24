import 'package:flutter/material.dart';
import 'package:pinput/pinput.dart';
import '../../../core/constants/app_colors.dart';

class OtpPinField extends StatelessWidget {
  final int length;
  final ValueChanged<String> onCompleted;

  const OtpPinField({super.key, this.length = 6, required this.onCompleted});

  @override
  Widget build(BuildContext context) {
    final defaultPinTheme = PinTheme(
      width: 48,
      height: 56,
      textStyle: const TextStyle(
        fontSize: 22,
        fontWeight: FontWeight.bold,
        color: AppColors.textPrimary,
      ),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppColors.border),
      ),
    );

    final focusedPinTheme = defaultPinTheme.copyWith(
      decoration: defaultPinTheme.decoration?.copyWith(
        border: Border.all(color: AppColors.primary, width: 2),
      ),
    );

    return Pinput(
      length: length,
      defaultPinTheme: defaultPinTheme,
      focusedPinTheme: focusedPinTheme,
      showCursor: true,
      onCompleted: onCompleted,
    );
  }
}
