import 'package:equatable/equatable.dart';

class UserModel extends Equatable {
  final int id;
  final String name;
  final String email;
  final String mobile;
  final String? designation;
  final String? organization;
  final int roleId; // 0=Admin, 1=Expert, 2=EndUser, 3=MD
  final String roleName;
  final int status;
  final String? token;

  const UserModel({
    required this.id,
    required this.name,
    required this.email,
    required this.mobile,
    this.designation,
    this.organization,
    required this.roleId,
    required this.roleName,
    required this.status,
    this.token,
  });

  bool get isAdmin => roleId == 0;
  bool get isExpert => roleId == 1;
  bool get isEndUser => roleId == 2;
  bool get isMD => roleId == 3;

  factory UserModel.fromJson(Map<String, dynamic> json) {
    int parsedRoleId = 2;
    if (json['roleId'] is int) {
      parsedRoleId = json['roleId'] as int;
    } else if (json['roleType'] != null) {
      parsedRoleId = int.tryParse(json['roleType'].toString()) ?? 2;
    }

    return UserModel(
      id: json['id'] as int? ?? json['userId'] as int? ?? 0,
      name: json['name'] as String? ?? '',
      email: json['email'] as String? ?? '',
      mobile: json['mobile'] as String? ?? json['contactNo'] as String? ?? '',
      designation: json['designation'] as String?,
      organization: json['organization'] as String?,
      roleId: parsedRoleId,
      roleName:
          json['roleName'] as String? ??
          (parsedRoleId == 0
              ? 'Admin'
              : (parsedRoleId == 1 ? 'Expert' : 'User')),
      status: json['status'] is int
          ? json['status'] as int
          : (int.tryParse(json['status']?.toString() ?? '1') ?? 1),
      token: json['token'] as String? ?? json['accessToken'] as String?,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'mobile': mobile,
      'designation': designation,
      'organization': organization,
      'roleId': roleId,
      'roleName': roleName,
      'status': status,
      'token': token,
    };
  }

  UserModel copyWith({
    int? id,
    String? name,
    String? email,
    String? mobile,
    String? designation,
    String? organization,
    int? roleId,
    String? roleName,
    int? status,
    String? token,
  }) {
    return UserModel(
      id: id ?? this.id,
      name: name ?? this.name,
      email: email ?? this.email,
      mobile: mobile ?? this.mobile,
      designation: designation ?? this.designation,
      organization: organization ?? this.organization,
      roleId: roleId ?? this.roleId,
      roleName: roleName ?? this.roleName,
      status: status ?? this.status,
      token: token ?? this.token,
    );
  }

  @override
  List<Object?> get props => [
    id,
    name,
    email,
    mobile,
    designation,
    organization,
    roleId,
    roleName,
    status,
    token,
  ];
}
