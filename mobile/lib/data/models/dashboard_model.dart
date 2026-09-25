import 'package:equatable/equatable.dart';
import 'query_model.dart';

class UserDashboardModel extends Equatable {
  final int totalQueries;
  final int activeQueries;
  final int resolvedQueries;
  final int pendingActionQueries;
  final List<QueryModel> recentQueries;
  final bool hasUrgentPending;
  final int unreadNotificationsCount;

  const UserDashboardModel({
    required this.totalQueries,
    required this.activeQueries,
    required this.resolvedQueries,
    required this.pendingActionQueries,
    required this.recentQueries,
    required this.hasUrgentPending,
    required this.unreadNotificationsCount,
  });

  factory UserDashboardModel.fromJson(Map<String, dynamic> json) {
    return UserDashboardModel(
      totalQueries: json['totalQueries'] as int? ?? 0,
      activeQueries: json['activeQueries'] as int? ?? 0,
      resolvedQueries: json['resolvedQueries'] as int? ?? 0,
      pendingActionQueries: json['pendingActionQueries'] as int? ?? 0,
      recentQueries:
          (json['recentQueries'] as List<dynamic>?)
              ?.map((q) => QueryModel.fromJson(q as Map<String, dynamic>))
              .toList() ??
          [],
      hasUrgentPending: json['hasUrgentPending'] as bool? ?? false,
      unreadNotificationsCount: json['unreadNotificationsCount'] as int? ?? 0,
    );
  }

  @override
  List<Object?> get props => [
    totalQueries,
    activeQueries,
    resolvedQueries,
    pendingActionQueries,
    recentQueries,
    hasUrgentPending,
    unreadNotificationsCount,
  ];
}

class AdminDashboardModel extends Equatable {
  final int totalQueries;
  final int unassignedQueries;
  final int inProgressQueries;
  final int resolvedTodayQueries;
  final int activeExpertsCount;
  final int totalUsers;
  final int slaBreachedQueriesCount;
  final List<QueryModel> recentUnassignedQueries;
  final List<QueryModel> recentQueries;

  const AdminDashboardModel({
    required this.totalQueries,
    required this.unassignedQueries,
    required this.inProgressQueries,
    required this.resolvedTodayQueries,
    required this.activeExpertsCount,
    required this.totalUsers,
    required this.slaBreachedQueriesCount,
    required this.recentUnassignedQueries,
    required this.recentQueries,
  });

  factory AdminDashboardModel.fromJson(Map<String, dynamic> json) {
    return AdminDashboardModel(
      totalQueries: json['totalQueries'] as int? ?? 0,
      unassignedQueries: json['unassignedQueries'] as int? ?? 0,
      inProgressQueries: json['inProgressQueries'] as int? ?? 0,
      resolvedTodayQueries: json['resolvedTodayQueries'] as int? ?? 0,
      activeExpertsCount: json['activeExpertsCount'] as int? ?? 0,
      totalUsers: json['totalUsers'] as int? ?? 0,
      slaBreachedQueriesCount: json['slaBreachedQueriesCount'] as int? ?? 0,
      recentUnassignedQueries:
          (json['recentUnassignedQueries'] as List<dynamic>?)
              ?.map((q) => QueryModel.fromJson(q as Map<String, dynamic>))
              .toList() ??
          [],
      recentQueries:
          (json['recentQueries'] as List<dynamic>?)
              ?.map((q) => QueryModel.fromJson(q as Map<String, dynamic>))
              .toList() ??
          [],
    );
  }

  @override
  List<Object?> get props => [
    totalQueries,
    unassignedQueries,
    inProgressQueries,
    resolvedTodayQueries,
    activeExpertsCount,
    totalUsers,
    slaBreachedQueriesCount,
    recentUnassignedQueries,
    recentQueries,
  ];
}
