import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../data/models/query_model.dart';
import '../../../data/repositories/query_repository.dart';

// Events
abstract class AdminQueryEvent extends Equatable {
  const AdminQueryEvent();
  @override
  List<Object?> get props => [];
}

class LoadAdminQueriesEvent extends AdminQueryEvent {
  final int? status;
  final int? categoryId;
  final String? search;
  final int page;
  final bool isLoadMore;

  const LoadAdminQueriesEvent({
    this.status,
    this.categoryId,
    this.search,
    this.page = 1,
    this.isLoadMore = false,
  });

  @override
  List<Object?> get props => [status, categoryId, search, page, isLoadMore];
}

class AssignExpertEvent extends AdminQueryEvent {
  final int queryId;
  final int expertId;
  final String? remarks;

  const AssignExpertEvent({
    required this.queryId,
    required this.expertId,
    this.remarks,
  });

  @override
  List<Object?> get props => [queryId, expertId, remarks];
}

class UpdateQueryStatusEvent extends AdminQueryEvent {
  final int queryId;
  final int status;

  const UpdateQueryStatusEvent({required this.queryId, required this.status});

  @override
  List<Object?> get props => [queryId, status];
}

// States
abstract class AdminQueryState extends Equatable {
  const AdminQueryState();
  @override
  List<Object?> get props => [];
}

class AdminQueryInitial extends AdminQueryState {}

class AdminQueryLoading extends AdminQueryState {}

class AdminQueriesLoaded extends AdminQueryState {
  final List<QueryModel> queries;
  final int? selectedStatus;
  final bool hasMoreData;

  const AdminQueriesLoaded({
    required this.queries,
    this.selectedStatus,
    this.hasMoreData = true,
  });

  @override
  List<Object?> get props => [queries, selectedStatus, hasMoreData];
}

class AdminQueryActionSuccess extends AdminQueryState {
  final String message;

  const AdminQueryActionSuccess(this.message);

  @override
  List<Object?> get props => [message];
}

class AdminQueryError extends AdminQueryState {
  final String message;

  const AdminQueryError(this.message);

  @override
  List<Object?> get props => [message];
}

// BLoC
class AdminQueryBloc extends Bloc<AdminQueryEvent, AdminQueryState> {
  final QueryRepository queryRepository;

  AdminQueryBloc({required this.queryRepository}) : super(AdminQueryInitial()) {
    on<LoadAdminQueriesEvent>(_onLoadQueries);
    on<AssignExpertEvent>(_onAssignExpert);
    on<UpdateQueryStatusEvent>(_onUpdateStatus);
  }

  Future<void> _onLoadQueries(
    LoadAdminQueriesEvent event,
    Emitter<AdminQueryState> emit,
  ) async {
    if (!event.isLoadMore) {
      emit(AdminQueryLoading());
    }
    try {
      final newQueries = await queryRepository.getQueries(
        status: event.status,
        categoryId: event.categoryId,
        search: event.search,
        page: event.page,
        pageSize: 15,
      );

      if (event.isLoadMore && state is AdminQueriesLoaded) {
        final currentQueries = (state as AdminQueriesLoaded).queries;
        emit(
          AdminQueriesLoaded(
            queries: [...currentQueries, ...newQueries],
            selectedStatus: event.status,
            hasMoreData: newQueries.length == 15,
          ),
        );
      } else {
        emit(
          AdminQueriesLoaded(
            queries: newQueries,
            selectedStatus: event.status,
            hasMoreData: newQueries.length == 15,
          ),
        );
      }
    } catch (e) {
      if (!event.isLoadMore) {
        emit(AdminQueryError(e.toString()));
      }
    }
  }

  Future<void> _onAssignExpert(
    AssignExpertEvent event,
    Emitter<AdminQueryState> emit,
  ) async {
    emit(AdminQueryLoading());
    try {
      await queryRepository.assignExpert(
        event.queryId,
        event.expertId,
        event.remarks,
      );
      emit(const AdminQueryActionSuccess('Expert assigned successfully'));
    } catch (e) {
      emit(AdminQueryError(e.toString()));
    }
  }

  Future<void> _onUpdateStatus(
    UpdateQueryStatusEvent event,
    Emitter<AdminQueryState> emit,
  ) async {
    emit(AdminQueryLoading());
    try {
      await queryRepository.updateStatus(event.queryId, event.status);
      emit(const AdminQueryActionSuccess('Status updated successfully'));
    } catch (e) {
      emit(AdminQueryError(e.toString()));
    }
  }
}
