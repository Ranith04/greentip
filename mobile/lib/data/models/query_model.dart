import 'package:equatable/equatable.dart';

class QueryAttachmentModel extends Equatable {
  final int id;
  final String fileName;
  final String filePath;
  final String? fileType;
  final int fileSize;
  final DateTime? createdAt;

  const QueryAttachmentModel({
    required this.id,
    required this.fileName,
    required this.filePath,
    this.fileType,
    required this.fileSize,
    this.createdAt,
  });

  factory QueryAttachmentModel.fromJson(Map<String, dynamic> json) {
    return QueryAttachmentModel(
      id: json['id'] as int? ?? 0,
      fileName: json['fileName'] as String? ?? 'Document',
      filePath: json['filePath'] as String? ?? '',
      fileType: json['fileType'] as String?,
      fileSize: (json['fileSize'] as num?)?.toInt() ?? 0,
      createdAt: json['createdAt'] != null
          ? DateTime.tryParse(json['createdAt'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'fileName': fileName,
      'filePath': filePath,
      'fileType': fileType,
      'fileSize': fileSize,
      'createdAt': createdAt?.toIso8601String(),
    };
  }

  @override
  List<Object?> get props => [
    id,
    fileName,
    filePath,
    fileType,
    fileSize,
    createdAt,
  ];
}

class QueryReviewModel extends Equatable {
  final int id;
  final int rating;
  final String? feedbackTag;
  final String? comments;
  final DateTime? createdAt;

  const QueryReviewModel({
    required this.id,
    required this.rating,
    this.feedbackTag,
    this.comments,
    this.createdAt,
  });

  factory QueryReviewModel.fromJson(Map<String, dynamic> json) {
    return QueryReviewModel(
      id: json['id'] as int? ?? 0,
      rating: json['rating'] as int? ?? 5,
      feedbackTag: json['feedbackTag'] as String?,
      comments: json['comments'] as String?,
      createdAt: json['createdAt'] != null
          ? DateTime.tryParse(json['createdAt'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'rating': rating,
      'feedbackTag': feedbackTag,
      'comments': comments,
      'createdAt': createdAt?.toIso8601String(),
    };
  }

  @override
  List<Object?> get props => [id, rating, feedbackTag, comments, createdAt];
}

class QueryModel extends Equatable {
  final int id;
  final int userId;
  final String userName;
  final String? organization;
  final String? userEmail;
  final String? userMobile;
  final String? industryName;
  final String? sector;
  final String? state;
  final String? consentNumber;
  final int? categoryId;
  final String? categoryName;
  final String title;
  final String description;
  final String urgency;
  final int status;
  final String statusName;
  final DateTime createdAt;
  final int? assignedExpertId;
  final String? assignedExpertName;
  final String? assignedExpertSpecialization;
  final DateTime? assignedAt;
  final String? expertRemarks;
  final DateTime? respondedAt;
  final String? expertResponse;
  final DateTime? slaDeadline;
  final bool isSlaBreached;
  final List<QueryAttachmentModel> attachments;
  final QueryReviewModel? review;

  const QueryModel({
    required this.id,
    required this.userId,
    required this.userName,
    this.organization,
    this.userEmail,
    this.userMobile,
    this.industryName,
    this.sector,
    this.state,
    this.consentNumber,
    this.categoryId,
    this.categoryName,
    required this.title,
    required this.description,
    this.urgency = 'Normal',
    required this.status,
    required this.statusName,
    required this.createdAt,
    this.assignedExpertId,
    this.assignedExpertName,
    this.assignedExpertSpecialization,
    this.assignedAt,
    this.expertRemarks,
    this.respondedAt,
    this.expertResponse,
    this.slaDeadline,
    this.isSlaBreached = false,
    this.attachments = const [],
    this.review,
  });

  bool get isUrgent => urgency.toLowerCase() == 'urgent';

  factory QueryModel.fromJson(Map<String, dynamic> json) {
    return QueryModel(
      id: json['id'] as int? ?? 0,
      userId: json['userId'] as int? ?? 0,
      userName: json['userName'] as String? ?? 'User',
      organization: json['organization'] as String?,
      userEmail: json['userEmail'] as String?,
      userMobile: json['userMobile'] as String?,
      industryName: json['industryName'] as String?,
      sector: json['sector'] as String?,
      state: json['state'] as String?,
      consentNumber: json['consentNumber'] as String?,
      categoryId: json['categoryId'] as int?,
      categoryName: json['categoryName'] as String?,
      title: json['title'] as String? ?? '',
      description: json['description'] as String? ?? '',
      urgency: json['urgency'] as String? ?? 'Normal',
      status: json['status'] as int? ?? 0,
      statusName: json['statusName'] as String? ?? 'Submitted',
      createdAt: json['createdAt'] != null
          ? DateTime.parse(json['createdAt'] as String)
          : DateTime.now(),
      assignedExpertId: json['assignedExpertId'] as int?,
      assignedExpertName: json['assignedExpertName'] as String?,
      assignedExpertSpecialization:
          json['assignedExpertSpecialization'] as String?,
      assignedAt: json['assignedAt'] != null
          ? DateTime.tryParse(json['assignedAt'] as String)
          : null,
      expertRemarks: json['expertRemarks'] as String?,
      respondedAt: json['respondedAt'] != null
          ? DateTime.tryParse(json['respondedAt'] as String)
          : null,
      expertResponse: json['expertResponse'] as String?,
      slaDeadline: json['slaDeadline'] != null
          ? DateTime.tryParse(json['slaDeadline'] as String)
          : null,
      isSlaBreached: json['isSlaBreached'] as bool? ?? false,
      attachments:
          (json['attachments'] as List<dynamic>?)
              ?.map(
                (a) => QueryAttachmentModel.fromJson(a as Map<String, dynamic>),
              )
              .toList() ??
          [],
      review: json['review'] != null
          ? QueryReviewModel.fromJson(json['review'] as Map<String, dynamic>)
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'userId': userId,
      'userName': userName,
      'organization': organization,
      'userEmail': userEmail,
      'userMobile': userMobile,
      'industryName': industryName,
      'sector': sector,
      'state': state,
      'consentNumber': consentNumber,
      'categoryId': categoryId,
      'categoryName': categoryName,
      'title': title,
      'description': description,
      'urgency': urgency,
      'status': status,
      'statusName': statusName,
      'createdAt': createdAt.toIso8601String(),
      'assignedExpertId': assignedExpertId,
      'assignedExpertName': assignedExpertName,
      'assignedExpertSpecialization': assignedExpertSpecialization,
      'assignedAt': assignedAt?.toIso8601String(),
      'expertRemarks': expertRemarks,
      'respondedAt': respondedAt?.toIso8601String(),
      'expertResponse': expertResponse,
      'slaDeadline': slaDeadline?.toIso8601String(),
      'isSlaBreached': isSlaBreached,
      'attachments': attachments.map((a) => a.toJson()).toList(),
      'review': review?.toJson(),
    };
  }

  @override
  List<Object?> get props => [
    id,
    userId,
    userName,
    organization,
    categoryId,
    categoryName,
    title,
    description,
    urgency,
    status,
    statusName,
    createdAt,
    assignedExpertId,
    assignedExpertName,
    expertResponse,
    attachments,
    review,
  ];
}
