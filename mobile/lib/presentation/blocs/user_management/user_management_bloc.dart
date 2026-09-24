import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../data/models/user_model.dart';
import '../../../data/repositories/user_repository.dart';

// Events
abstract class UserManagementEvent extends Equatable {
  const UserManagementEvent();
  @override
  List<Object?> get props => [];
}

class LoadUsersEvent extends UserManagementEvent {
  final String? search;
  final int? roleId;

  const LoadUsersEvent({this.search, this.roleId});

  @override
  List<Object?> get props => [search, roleId];
}

class ToggleUserStatusEvent extends UserManagementEvent {
  final int userId;
  final int status;

  const ToggleUserStatusEvent({required this.userId, required this.status});

  @override
  List<Object?> get props => [userId, status];
}

// States
abstract class UserManagementState extends Equatable {
  const UserManagementState();
  @override
  List<Object?> get props => [];
}

class UserManagementInitial extends UserManagementState {}

class UserManagementLoading extends UserManagementState {}

class UsersLoaded extends UserManagementState {
  final List<UserModel> users;

  const UsersLoaded(this.users);

  @override
  List<Object?> get props => [users];
}

class UserManagementActionSuccess extends UserManagementState {
  final String message;

  const UserManagementActionSuccess(this.message);

  @override
  List<Object?> get props => [message];
}

class UserManagementError extends UserManagementState {
  final String message;

  const UserManagementError(this.message);

  @override
  List<Object?> get props => [message];
}

// BLoC
class UserManagementBloc
    extends Bloc<UserManagementEvent, UserManagementState> {
  final UserRepository userRepository;

  UserManagementBloc({required this.userRepository})
    : super(UserManagementInitial()) {
    on<LoadUsersEvent>(_onLoadUsers);
    on<ToggleUserStatusEvent>(_onToggleStatus);
  }

  Future<void> _onLoadUsers(
    LoadUsersEvent event,
    Emitter<UserManagementState> emit,
  ) async {
    emit(UserManagementLoading());
    try {
      final users = await userRepository.getUsers(
        search: event.search,
        roleId: event.roleId,
      );
      emit(UsersLoaded(users));
    } catch (e) {
      emit(UserManagementError(e.toString()));
    }
  }

  Future<void> _onToggleStatus(
    ToggleUserStatusEvent event,
    Emitter<UserManagementState> emit,
  ) async {
    try {
      await userRepository.updateUserStatus(event.userId, event.status);
      emit(const UserManagementActionSuccess('User status updated'));
      final users = await userRepository.getUsers();
      emit(UsersLoaded(users));
    } catch (e) {
      emit(UserManagementError(e.toString()));
    }
  }
}
