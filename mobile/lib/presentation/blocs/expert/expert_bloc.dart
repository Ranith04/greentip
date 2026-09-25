import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../data/models/expert_model.dart';
import '../../../data/repositories/expert_repository.dart';

// Events
abstract class ExpertEvent extends Equatable {
  const ExpertEvent();
  @override
  List<Object?> get props => [];
}

class LoadExpertsEvent extends ExpertEvent {
  final String? search;
  final int? status;

  const LoadExpertsEvent({this.search, this.status});

  @override
  List<Object?> get props => [search, status];
}

class AddExpertEvent extends ExpertEvent {
  final String name;
  final String email;
  final String mobile;
  final String password;
  final String? specialization;
  final int status;

  const AddExpertEvent({
    required this.name,
    required this.email,
    required this.mobile,
    required this.password,
    this.specialization,
    this.status = 1,
  });

  @override
  List<Object?> get props => [name, email, mobile, password, specialization, status];
}

class ToggleExpertStatusEvent extends ExpertEvent {
  final int expertId;
  final int status;

  const ToggleExpertStatusEvent({required this.expertId, required this.status});

  @override
  List<Object?> get props => [expertId, status];
}

// States
abstract class ExpertState extends Equatable {
  const ExpertState();
  @override
  List<Object?> get props => [];
}

class ExpertInitial extends ExpertState {}

class ExpertLoading extends ExpertState {}

class ExpertsLoaded extends ExpertState {
  final List<ExpertModel> experts;

  const ExpertsLoaded(this.experts);

  @override
  List<Object?> get props => [experts];
}

class ExpertActionSuccess extends ExpertState {
  final String message;

  const ExpertActionSuccess(this.message);

  @override
  List<Object?> get props => [message];
}

class ExpertError extends ExpertState {
  final String message;

  const ExpertError(this.message);

  @override
  List<Object?> get props => [message];
}

// BLoC
class ExpertBloc extends Bloc<ExpertEvent, ExpertState> {
  final ExpertRepository expertRepository;

  ExpertBloc({required this.expertRepository}) : super(ExpertInitial()) {
    on<LoadExpertsEvent>(_onLoadExperts);
    on<AddExpertEvent>(_onAddExpert);
    on<ToggleExpertStatusEvent>(_onToggleStatus);
  }

  Future<void> _onLoadExperts(
    LoadExpertsEvent event,
    Emitter<ExpertState> emit,
  ) async {
    emit(ExpertLoading());
    try {
      final experts = await expertRepository.getExperts(
        search: event.search,
        status: event.status,
      );
      emit(ExpertsLoaded(experts));
    } catch (e) {
      emit(ExpertError(e.toString()));
    }
  }

  Future<void> _onAddExpert(
    AddExpertEvent event,
    Emitter<ExpertState> emit,
  ) async {
    emit(ExpertLoading());
    try {
      await expertRepository.addExpert(
        name: event.name,
        email: event.email,
        mobile: event.mobile,
        password: event.password,
        specialization: event.specialization,
        status: event.status,
      );
      emit(const ExpertActionSuccess('Expert added successfully'));
      final experts = await expertRepository.getExperts();
      emit(ExpertsLoaded(experts));
    } catch (e) {
      emit(ExpertError(e.toString()));
    }
  }

  Future<void> _onToggleStatus(
    ToggleExpertStatusEvent event,
    Emitter<ExpertState> emit,
  ) async {
    try {
      await expertRepository.toggleExpertStatus(event.expertId, event.status);
      emit(const ExpertActionSuccess('Expert status updated'));
      final experts = await expertRepository.getExperts();
      emit(ExpertsLoaded(experts));
    } catch (e) {
      emit(ExpertError(e.toString()));
    }
  }
}
