import 'package:flutter/material.dart';
import '../../../core/utils/status_helper.dart';

class StatusBadge extends StatelessWidget {
  final int status;
  final String? customLabel;

  const StatusBadge({super.key, required this.status, this.customLabel});

  @override
  Widget build(BuildContext context) {
    final label = customLabel ?? StatusHelper.getStatusLabel(status);
    final bg = StatusHelper.getStatusBgColor(status);
    final text = StatusHelper.getStatusTextColor(status);
    final icon = StatusHelper.getStatusIcon(status);

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 14, color: text),
          const SizedBox(width: 4),
          Text(
            label,
            style: TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.w600,
              color: text,
            ),
          ),
        ],
      ),
    );
  }
}
