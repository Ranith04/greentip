import 'package:equatable/equatable.dart';

class BulkEmailLogModel extends Equatable {
  final int id;
  final String subject;
  final String message;
  final String targetAudience;
  final int recipientCount;
  final DateTime sentAt;
  final String status;
  final String? senderName;

  const BulkEmailLogModel({
    required this.id,
    required this.subject,
    required this.message,
    required this.targetAudience,
    required this.recipientCount,
    required this.sentAt,
    required this.status,
    this.senderName,
  });

  factory BulkEmailLogModel.fromJson(Map<String, dynamic> json) {
    return BulkEmailLogModel(
      id: json['id'] as int? ?? 0,
      subject: json['subject'] as String? ?? '',
      message: json['message'] as String? ?? '',
      targetAudience: json['targetAudience'] as String? ?? 'All Users',
      recipientCount: json['recipientCount'] as int? ?? 0,
      sentAt: json['sentAt'] != null
          ? DateTime.tryParse(json['sentAt'] as String) ?? DateTime.now()
          : DateTime.now(),
      status: json['status'] as String? ?? 'Sent',
      senderName: json['senderName'] as String?,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'subject': subject,
      'message': message,
      'targetAudience': targetAudience,
      'recipientCount': recipientCount,
      'sentAt': sentAt.toIso8601String(),
      'status': status,
      'senderName': senderName,
    };
  }

  @override
  List<Object?> get props => [
    id,
    subject,
    message,
    targetAudience,
    recipientCount,
    sentAt,
    status,
    senderName,
  ];
}
