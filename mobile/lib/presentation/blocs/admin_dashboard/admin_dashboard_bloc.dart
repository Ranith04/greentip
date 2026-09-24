import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../data/models/dashboard_model.dart';
import '../../../data/repositories/dashboard_repository.dart';

// Events
abstract class AdminDashboardEvent extends Equatable {
  const AdminDashboardEvent();
  @override
  List<Object?> get props => [];
}

class LoadAdminDashboardEvent extends AdminDashboardEvent {}

class RefreshAdminDashboardEvent extends AdminDashboardEvent {}

// States
abstract class AdminDashboardState extends Equatable {
  const AdminDashboardState();
  @override
  List<Object?> get props => [];
}

class AdminDashboardInitial extends AdminDashboardState {}

class AdminDashboardLoading extends AdminDashboardState {}

class AdminDashboardLoaded extends AdminDashboardState {
  final AdminDashboardModel dashboard;

  const AdminDashboardLoaded(this.dashboard);

  @override
  List<Object?> get props => [dashboard];
}

class AdminDashboardError extends AdminDashboardState {
  final String message;

  const AdminDashboardError(this.message);

  @override
  List<Object?> get props => [message];
}

// BLoC
class AdminDashboardBloc
    extends Bloc<AdminDashboardEvent, AdminDashboardState> {
  final DashboardRepository dashboardRepository;

  AdminDashboardBloc({required this.dashboardRepository})
    : super(AdminDashboardInitial()) {
    on<LoadAdminDashboardEvent>(_onLoadDashboard);
    on<RefreshAdminDashboardEvent>(_onRefreshDashboard);
  }

  Future<void> _onLoadDashboard(
    LoadAdminDashboardEvent event,
    Emitter<AdminDashboardState> emit,
  ) async {
    emit(AdminDashboardLoading());
    try {
      final data = await dashboardRepository.getAdminDashboard();
      emit(AdminDashboardLoaded(data));
    } catch (e) {
      emit(AdminDashboardError(e.toString()));
    }
  }

  Future<void> _onRefreshDashboard(
    RefreshAdminDashboardEvent event,
    Emitter<AdminDashboardState> emit,
  ) async {
    try {
      final data = await dashboardRepository.getAdminDashboard();
      emit(AdminDashboardLoaded(data));
    } catch (e) {
      emit(AdminDashboardError(e.toString()));
    }
  }
}
