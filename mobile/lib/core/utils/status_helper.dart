import 'package:flutter/material.dart';
import '../constants/app_colors.dart';

class StatusHelper {
  static String getStatusLabel(int status) {
    switch (status) {
      case 0:
        return 'Submitted';
      case 1:
        return 'Assigned';
      case 2:
        return 'In Review';
      case 3:
        return 'Answered';
      case 4:
        return 'Closed';
      default:
        return 'Unknown';
    }
  }

  static Color getStatusBgColor(int status) {
    switch (status) {
      case 0:
        return AppColors.statusSubmittedBg;
      case 1:
        return AppColors.statusAssignedBg;
      case 2:
        return AppColors.statusInReviewBg;
      case 3:
        return AppColors.statusAnsweredBg;
      case 4:
        return AppColors.statusClosedBg;
      default:
        return Colors.grey.shade100;
    }
  }

  static Color getStatusTextColor(int status) {
    switch (status) {
      case 0:
        return AppColors.statusSubmittedText;
      case 1:
        return AppColors.statusAssignedText;
      case 2:
        return AppColors.statusInReviewText;
      case 3:
        return AppColors.statusAnsweredText;
      case 4:
        return AppColors.statusClosedText;
      default:
        return Colors.grey.shade700;
    }
  }

  static IconData getStatusIcon(int status) {
    switch (status) {
      case 0:
        return Icons.upload_file_rounded;
      case 1:
        return Icons.person_search_rounded;
      case 2:
        return Icons.hourglass_top_rounded;
      case 3:
        return Icons.check_circle_outline_rounded;
      case 4:
        return Icons.lock_outline_rounded;
      default:
        return Icons.info_outline_rounded;
    }
  }
}
