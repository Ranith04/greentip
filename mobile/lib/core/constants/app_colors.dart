import 'package:flutter/material.dart';

class AppColors {
  // Brand Primary & Accents (Forest / Environmental Theme)
  static const Color primary = Color(0xFF2E7D32); // Forest Green
  static const Color primaryDark = Color(0xFF1B5E20); // Deep Forest Green
  static const Color primaryLight = Color(0xFF4CAF50); // Vibrant Leaf Green
  static const Color primaryContainer = Color(
    0xFFE8F5E9,
  ); // Mint Background Light
  static const Color secondary = Color(0xFF00796B); // Teal Accent
  static const Color secondaryContainer = Color(0xFFE0F2F1); // Teal Light Tint
  static const Color tertiary = Color(
    0xFFE65100,
  ); // Amber/Orange Accent (Urgent/Warnings)

  // Surface & Neutrals
  static const Color background = Color(0xFFF8FAF8);
  static const Color surface = Color(0xFFFFFFFF);
  static const Color surfaceElevated = Color(0xFFFFFFFF);
  static const Color cardBackground = Color(0xFFFFFFFF);
  static const Color divider = Color(0xFFE2E8F0);
  static const Color border = Color(0xFFE0E0E0);
  static const Color borderFocused = Color(0xFF2E7D32);

  // Typography Colors
  static const Color textPrimary = Color(0xFF1A202C);
  static const Color textSecondary = Color(0xFF4A5568);
  static const Color textMuted = Color(0xFF718096);
  static const Color textLight = Color(0xFFFFFFFF);

  // Status & SLA Badges
  static const Color statusSubmittedBg = Color(0xFFEFF6FF);
  static const Color statusSubmittedText = Color(0xFF1D4ED8);

  static const Color statusAssignedBg = Color(0xFFF5F3FF);
  static const Color statusAssignedText = Color(0xFF6D28D9);

  static const Color statusInReviewBg = Color(0xFFFEF3C7);
  static const Color statusInReviewText = Color(0xFFB45309);

  static const Color statusAnsweredBg = Color(0xFFECFDF5);
  static const Color statusAnsweredText = Color(0xFF047857);

  static const Color statusClosedBg = Color(0xFFF1F5F9);
  static const Color statusClosedText = Color(0xFF475569);

  static const Color error = Color(0xFFDC2626);
  static const Color errorLight = Color(0xFFFEE2E2);
  static const Color success = Color(0xFF16A34A);
  static const Color successLight = Color(0xFFDCFCE7);
  static const Color warning = Color(0xFFD97706);
  static const Color warningLight = Color(0xFFFEF3C7);
  static const Color info = Color(0xFF2563EB);
  static const Color infoLight = Color(0xFFDBEAFE);

  // Gradient definitions
  static const LinearGradient primaryGradient = LinearGradient(
    colors: [primary, primaryDark],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  static const LinearGradient cardGradient = LinearGradient(
    colors: [Color(0xFF2E7D32), Color(0xFF1B5E20)],
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );
}
