import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../data/models/category_model.dart';
import '../../../data/models/query_model.dart';
import '../../../data/repositories/query_repository.dart';

// Events
abstract class QueryEvent extends Equatable {
  const QueryEvent();
  @override
  List<Object?> get props => [];
}

class LoadMyQueriesEvent extends QueryEvent {
  final int? status;
  final int? categoryId;
  final String? search;

  const LoadMyQueriesEvent({this.status, this.categoryId, this.search});

  @override
  List<Object?> get props => [status, categoryId, search];
}

class LoadQueryDetailsEvent extends QueryEvent {
  final int queryId;

  const LoadQueryDetailsEvent(this.queryId);

  @override
  List<Object?> get props => [queryId];
}

class SubmitQueryEvent extends QueryEvent {
  final String title;
  final String description;
  final String urgency;
  final int? categoryId;
  final String? industryName;
  final String? sector;
  final String? state;
  final String? consentNumber;
  final List<String>? filePaths;

  const SubmitQueryEvent({
    required this.title,
    required this.description,
    required this.urgency,
    this.categoryId,
    this.industryName,
    this.sector,
    this.state,
    this.consentNumber,
    this.filePaths,
  });

  @override
  List<Object?> get props => [
    title,
    description,
    urgency,
    categoryId,
    industryName,
    sector,
    state,
    consentNumber,
    filePaths,
  ];
}

class SubmitReviewEvent extends QueryEvent {
  final int queryId;
  final int rating;
  final String? feedbackTag;
  final String? comments;

  const SubmitReviewEvent({
    required this.queryId,
    required this.rating,
    this.feedbackTag,
    this.comments,
  });

  @override
  List<Object?> get props => [queryId, rating, feedbackTag, comments];
}

class LoadCategoriesEvent extends QueryEvent {}

// States
abstract class QueryState extends Equatable {
  const QueryState();
  @override
  List<Object?> get props => [];
}

class QueryInitial extends QueryState {}

class QueryLoading extends QueryState {}

class QueriesLoaded extends QueryState {
  final List<QueryModel> queries;
  final List<CategoryModel> categories;
  final int? selectedStatus;

  const QueriesLoaded({
    required this.queries,
    this.categories = const [],
    this.selectedStatus,
  });

  @override
  List<Object?> get props => [queries, categories, selectedStatus];
}

class QueryDetailsLoaded extends QueryState {
  final QueryModel query;

  const QueryDetailsLoaded(this.query);

  @override
  List<Object?> get props => [query];
}

class QuerySubmitSuccess extends QueryState {
  final QueryModel query;

  const QuerySubmitSuccess(this.query);

  @override
  List<Object?> get props => [query];
}

class QueryReviewSuccess extends QueryState {}

class QueryError extends QueryState {
  final String message;

  const QueryError(this.message);

  @override
  List<Object?> get props => [message];
}

// BLoC
class QueryBloc extends Bloc<QueryEvent, QueryState> {
  final QueryRepository queryRepository;

  QueryBloc({required this.queryRepository}) : super(QueryInitial()) {
    on<LoadMyQueriesEvent>(_onLoadMyQueries);
    on<LoadQueryDetailsEvent>(_onLoadQueryDetails);
    on<SubmitQueryEvent>(_onSubmitQuery);
    on<SubmitReviewEvent>(_onSubmitReview);
    on<LoadCategoriesEvent>(_onLoadCategories);
  }

  Future<void> _onLoadMyQueries(
    LoadMyQueriesEvent event,
    Emitter<QueryState> emit,
  ) async {
    emit(QueryLoading());
    try {
      final queries = await queryRepository.getQueries(
        status: event.status,
        categoryId: event.categoryId,
        search: event.search,
      );
      final categories = await queryRepository.getCategories();
      emit(
        QueriesLoaded(
          queries: queries,
          categories: categories,
          selectedStatus: event.status,
        ),
      );
    } catch (e) {
      emit(QueryError(e.toString()));
    }
  }

  Future<void> _onLoadQueryDetails(
    LoadQueryDetailsEvent event,
    Emitter<QueryState> emit,
  ) async {
    emit(QueryLoading());
    try {
      final query = await queryRepository.getQueryById(event.queryId);
      emit(QueryDetailsLoaded(query));
    } catch (e) {
      emit(QueryError(e.toString()));
    }
  }

  Future<void> _onSubmitQuery(
    SubmitQueryEvent event,
    Emitter<QueryState> emit,
  ) async {
    emit(QueryLoading());
    try {
      final created = await queryRepository.submitQuery(
        title: event.title,
        description: event.description,
        urgency: event.urgency,
        categoryId: event.categoryId,
        industryName: event.industryName,
        sector: event.sector,
        state: event.state,
        consentNumber: event.consentNumber,
        filePaths: event.filePaths,
      );
      emit(QuerySubmitSuccess(created));
    } catch (e) {
      emit(QueryError(e.toString()));
    }
  }

  Future<void> _onSubmitReview(
    SubmitReviewEvent event,
    Emitter<QueryState> emit,
  ) async {
    emit(QueryLoading());
    try {
      await queryRepository.reviewQuery(
        event.queryId,
        event.rating,
        event.feedbackTag,
        event.comments,
      );
      emit(QueryReviewSuccess());
    } catch (e) {
      emit(QueryError(e.toString()));
    }
  }

  Future<void> _onLoadCategories(
    LoadCategoriesEvent event,
    Emitter<QueryState> emit,
  ) async {
    try {
      final categories = await queryRepository.getCategories();
      if (state is QueriesLoaded) {
        final current = state as QueriesLoaded;
        emit(
          QueriesLoaded(
            queries: current.queries,
            categories: categories,
            selectedStatus: current.selectedStatus,
          ),
        );
      }
    } catch (_) {}
  }
}
