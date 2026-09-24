import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../data/models/dashboard_model.dart';
import '../../../data/repositories/dashboard_repository.dart';

// Events
abstract class UserDashboardEvent extends Equatable {
  const UserDashboardEvent();
  @override
  List<Object?> get props => [];
}

class LoadUserDashboardEvent extends UserDashboardEvent {}

class RefreshUserDashboardEvent extends UserDashboardEvent {}

// States
abstract class UserDashboardState extends Equatable {
  const UserDashboardState();
  @override
  List<Object?> get props => [];
}

class UserDashboardInitial extends UserDashboardState {}

class UserDashboardLoading extends UserDashboardState {}

class UserDashboardLoaded extends UserDashboardState {
  final UserDashboardModel dashboard;

  const UserDashboardLoaded(this.dashboard);

  @override
  List<Object?> get props => [dashboard];
}

class UserDashboardError extends UserDashboardState {
  final String message;

  const UserDashboardError(this.message);

  @override
  List<Object?> get props => [message];
}

// BLoC
class UserDashboardBloc extends Bloc<UserDashboardEvent, UserDashboardState> {
  final DashboardRepository dashboardRepository;

  UserDashboardBloc({required this.dashboardRepository})
    : super(UserDashboardInitial()) {
    on<LoadUserDashboardEvent>(_onLoadDashboard);
    on<RefreshUserDashboardEvent>(_onRefreshDashboard);
  }

  Future<void> _onLoadDashboard(
    LoadUserDashboardEvent event,
    Emitter<UserDashboardState> emit,
  ) async {
    emit(UserDashboardLoading());
    try {
      final data = await dashboardRepository.getUserDashboard();
      emit(UserDashboardLoaded(data));
    } catch (e) {
      emit(UserDashboardError(e.toString()));
    }
  }

  Future<void> _onRefreshDashboard(
    RefreshUserDashboardEvent event,
    Emitter<UserDashboardState> emit,
  ) async {
    try {
      final data = await dashboardRepository.getUserDashboard();
      emit(UserDashboardLoaded(data));
    } catch (e) {
      emit(UserDashboardError(e.toString()));
    }
  }
}
