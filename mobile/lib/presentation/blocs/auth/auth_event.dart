import 'package:equatable/equatable.dart';

abstract class AuthEvent extends Equatable {
  const AuthEvent();

  @override
  List<Object?> get props => [];
}

class CheckAuthStatusEvent extends AuthEvent {}

class LoginEvent extends AuthEvent {
  final String emailOrMobile;
  final String password;

  const LoginEvent({required this.emailOrMobile, required this.password});

  @override
  List<Object?> get props => [emailOrMobile, password];
}

class RegisterEvent extends AuthEvent {
  final String name;
  final String email;
  final String mobile;
  final String password;
  final String? organization;
  final String? designation;

  const RegisterEvent({
    required this.name,
    required this.email,
    required this.mobile,
    required this.password,
    this.organization,
    this.designation,
  });

  @override
  List<Object?> get props => [
    name,
    email,
    mobile,
    password,
    organization,
    designation,
  ];
}

class VerifyOtpEvent extends AuthEvent {
  final String emailOrMobile;
  final String otp;

  const VerifyOtpEvent({required this.emailOrMobile, required this.otp});

  @override
  List<Object?> get props => [emailOrMobile, otp];
}

class ResendOtpEvent extends AuthEvent {
  final String emailOrMobile;

  const ResendOtpEvent({required this.emailOrMobile});

  @override
  List<Object?> get props => [emailOrMobile];
}

class LogoutEvent extends AuthEvent {}
