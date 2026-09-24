import 'package:equatable/equatable.dart';

class ExpertModel extends Equatable {
  final int id;
  final String name;
  final String email;
  final String mobile;
  final String? specialization;
  final int activeAssignedQueriesCount;
  final int totalResolvedQueriesCount;
  final double averageRating;
  final int status;

  const ExpertModel({
    required this.id,
    required this.name,
    required this.email,
    required this.mobile,
    this.specialization,
    required this.activeAssignedQueriesCount,
    required this.totalResolvedQueriesCount,
    required this.averageRating,
    required this.status,
  });

  bool get isActive => status == 1;

  factory ExpertModel.fromJson(Map<String, dynamic> json) {
    return ExpertModel(
      id: json['id'] as int? ?? 0,
      name: json['name'] as String? ?? 'Expert',
      email: json['email'] as String? ?? '',
      mobile: json['mobile'] as String? ?? '',
      specialization: json['specialization'] as String?,
      activeAssignedQueriesCount:
          json['activeAssignedQueriesCount'] as int? ?? 0,
      totalResolvedQueriesCount: json['totalResolvedQueriesCount'] as int? ?? 0,
      averageRating: (json['averageRating'] as num?)?.toDouble() ?? 5.0,
      status: json['status'] as int? ?? 1,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'mobile': mobile,
      'specialization': specialization,
      'activeAssignedQueriesCount': activeAssignedQueriesCount,
      'totalResolvedQueriesCount': totalResolvedQueriesCount,
      'averageRating': averageRating,
      'status': status,
    };
  }

  @override
  List<Object?> get props => [
    id,
    name,
    email,
    mobile,
    specialization,
    activeAssignedQueriesCount,
    totalResolvedQueriesCount,
    averageRating,
    status,
  ];
}
