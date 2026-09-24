import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../data/models/bulk_email_model.dart';
import '../../../data/repositories/bulk_email_repository.dart';

// Events
abstract class BulkEmailEvent extends Equatable {
  const BulkEmailEvent();
  @override
  List<Object?> get props => [];
}

class SendBulkEmailEvent extends BulkEmailEvent {
  final String subject;
  final String message;
  final String targetAudience;
  final List<int>? specificUserIds;

  const SendBulkEmailEvent({
    required this.subject,
    required this.message,
    required this.targetAudience,
    this.specificUserIds,
  });

  @override
  List<Object?> get props => [
    subject,
    message,
    targetAudience,
    specificUserIds,
  ];
}

class LoadBulkEmailLogsEvent extends BulkEmailEvent {}

// States
abstract class BulkEmailState extends Equatable {
  const BulkEmailState();
  @override
  List<Object?> get props => [];
}

class BulkEmailInitial extends BulkEmailState {}

class BulkEmailLoading extends BulkEmailState {}

class BulkEmailSentSuccess extends BulkEmailState {
  final String message;

  const BulkEmailSentSuccess(this.message);

  @override
  List<Object?> get props => [message];
}

class BulkEmailLogsLoaded extends BulkEmailState {
  final List<BulkEmailLogModel> logs;

  const BulkEmailLogsLoaded(this.logs);

  @override
  List<Object?> get props => [logs];
}

class BulkEmailError extends BulkEmailState {
  final String message;

  const BulkEmailError(this.message);

  @override
  List<Object?> get props => [message];
}

// BLoC
class BulkEmailBloc extends Bloc<BulkEmailEvent, BulkEmailState> {
  final BulkEmailRepository bulkEmailRepository;

  BulkEmailBloc({required this.bulkEmailRepository})
    : super(BulkEmailInitial()) {
    on<SendBulkEmailEvent>(_onSendBulkEmail);
    on<LoadBulkEmailLogsEvent>(_onLoadLogs);
  }

  Future<void> _onSendBulkEmail(
    SendBulkEmailEvent event,
    Emitter<BulkEmailState> emit,
  ) async {
    emit(BulkEmailLoading());
    try {
      await bulkEmailRepository.sendBulkEmail(
        subject: event.subject,
        message: event.message,
        targetAudience: event.targetAudience,
        specificUserIds: event.specificUserIds,
      );
      emit(
        const BulkEmailSentSuccess(
          'Broadcast email successfully queued for delivery',
        ),
      );
    } catch (e) {
      emit(BulkEmailError(e.toString()));
    }
  }

  Future<void> _onLoadLogs(
    LoadBulkEmailLogsEvent event,
    Emitter<BulkEmailState> emit,
  ) async {
    emit(BulkEmailLoading());
    try {
      final logs = await bulkEmailRepository.getLogs();
      emit(BulkEmailLogsLoaded(logs));
    } catch (e) {
      emit(BulkEmailError(e.toString()));
    }
  }
}
