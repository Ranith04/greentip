import 'package:equatable/equatable.dart';

class ArticleModel extends Equatable {
  final int id;
  final String title;
  final String content;
  final String? category;
  final String? readTime;
  final DateTime? updatedAt;
  final bool isBookmarked;

  const ArticleModel({
    required this.id,
    required this.title,
    required this.content,
    this.category,
    this.readTime = '4 min read',
    this.updatedAt,
    this.isBookmarked = false,
  });

  factory ArticleModel.fromJson(Map<String, dynamic> json) {
    return ArticleModel(
      id: json['id'] as int? ?? 0,
      title: json['title'] as String? ?? json['question'] as String? ?? '',
      content: json['content'] as String? ?? json['answer'] as String? ?? '',
      category: json['categoryName'] as String? ?? 'General Compliance',
      readTime: '3-5 min read',
      updatedAt: json['updatedAt'] != null
          ? DateTime.tryParse(json['updatedAt'] as String)
          : null,
      isBookmarked: false,
    );
  }

  ArticleModel copyWith({
    int? id,
    String? title,
    String? content,
    String? category,
    String? readTime,
    DateTime? updatedAt,
    bool? isBookmarked,
  }) {
    return ArticleModel(
      id: id ?? this.id,
      title: title ?? this.title,
      content: content ?? this.content,
      category: category ?? this.category,
      readTime: readTime ?? this.readTime,
      updatedAt: updatedAt ?? this.updatedAt,
      isBookmarked: isBookmarked ?? this.isBookmarked,
    );
  }

  @override
  List<Object?> get props => [
    id,
    title,
    content,
    category,
    readTime,
    isBookmarked,
  ];
}
