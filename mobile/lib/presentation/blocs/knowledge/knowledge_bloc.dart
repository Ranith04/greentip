import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../data/models/article_model.dart';
import '../../../data/models/category_model.dart';
import '../../../data/repositories/knowledge_repository.dart';

// Events
abstract class KnowledgeEvent extends Equatable {
  const KnowledgeEvent();
  @override
  List<Object?> get props => [];
}

class LoadKnowledgeBaseEvent extends KnowledgeEvent {
  final int? categoryId;
  final String? search;

  const LoadKnowledgeBaseEvent({this.categoryId, this.search});

  @override
  List<Object?> get props => [categoryId, search];
}

// States
abstract class KnowledgeState extends Equatable {
  const KnowledgeState();
  @override
  List<Object?> get props => [];
}

class KnowledgeInitial extends KnowledgeState {}

class KnowledgeLoading extends KnowledgeState {}

class KnowledgeLoaded extends KnowledgeState {
  final List<ArticleModel> articles;
  final List<ArticleModel> faqs;
  final List<CategoryModel> categories;
  final int? selectedCategoryId;

  const KnowledgeLoaded({
    required this.articles,
    required this.faqs,
    required this.categories,
    this.selectedCategoryId,
  });

  @override
  List<Object?> get props => [articles, faqs, categories, selectedCategoryId];
}

class KnowledgeError extends KnowledgeState {
  final String message;

  const KnowledgeError(this.message);

  @override
  List<Object?> get props => [message];
}

// BLoC
class KnowledgeBloc extends Bloc<KnowledgeEvent, KnowledgeState> {
  final KnowledgeRepository knowledgeRepository;

  KnowledgeBloc({required this.knowledgeRepository})
    : super(KnowledgeInitial()) {
    on<LoadKnowledgeBaseEvent>(_onLoadKnowledgeBase);
  }

  Future<void> _onLoadKnowledgeBase(
    LoadKnowledgeBaseEvent event,
    Emitter<KnowledgeState> emit,
  ) async {
    emit(KnowledgeLoading());
    try {
      final articles = await knowledgeRepository.getArticles();
      final faqs = await knowledgeRepository.getFaqs(
        categoryId: event.categoryId,
      );
      final categories = await knowledgeRepository.getCategories();

      emit(
        KnowledgeLoaded(
          articles: articles,
          faqs: faqs,
          categories: categories,
          selectedCategoryId: event.categoryId,
        ),
      );
    } catch (e) {
      emit(KnowledgeError(e.toString()));
    }
  }
}
