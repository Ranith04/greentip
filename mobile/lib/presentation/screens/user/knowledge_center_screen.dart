import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/article_model.dart';
import '../../blocs/knowledge/knowledge_bloc.dart';
import '../../widgets/feedback/empty_state_widget.dart';

class KnowledgeCenterScreen extends StatefulWidget {
  const KnowledgeCenterScreen({super.key});

  @override
  State<KnowledgeCenterScreen> createState() => _KnowledgeCenterScreenState();
}

class _KnowledgeCenterScreenState extends State<KnowledgeCenterScreen> {
  final TextEditingController _searchController = TextEditingController();
  String _searchQuery = '';

  // Category grid data matching reference design
  final List<Map<String, dynamic>> _categories = [
    {
      'name': 'Environmental Clearance',
      'icon': Icons.eco,
      'bgColor': Color(0xFFE8F5E9),
      'iconColor': Color(0xFF2E7D32),
    },
    {
      'name': 'Construction',
      'icon': Icons.home_work_outlined,
      'bgColor': Color(0xFFE8F5E9),
      'iconColor': Color(0xFF2E7D32),
    },
    {
      'name': 'Real Estate',
      'icon': Icons.apartment_outlined,
      'bgColor': Color(0xFFE8EAF6),
      'iconColor': Color(0xFF3F51B5),
    },
    {
      'name': 'NOC',
      'icon': Icons.assignment_outlined,
      'bgColor': Color(0xFFE3F2FD),
      'iconColor': Color(0xFF1565C0),
    },
    {
      'name': 'Mining',
      'icon': Icons.landscape_outlined,
      'bgColor': Color(0xFFFFF3E0),
      'iconColor': Color(0xFFE65100),
    },
    {
      'name': 'Enviro-Legal',
      'icon': Icons.gavel_rounded,
      'bgColor': Color(0xFFF3E5F5),
      'iconColor': Color(0xFF7B1FA2),
    },
  ];

  final List<ArticleModel> _fallbackArticles = const [
    ArticleModel(
      id: 1,
      title: 'Understanding EC Requirements for Development Projects',
      content: 'Under the Water (Prevention and Control of Pollution) Act, all industrial units must obtain prior Consent to Operate.',
      category: 'Environmental Clearance',
      readTime: '4 min read',
    ),
    ArticleModel(
      id: 2,
      title: 'Mining Lease Process: Step by Step Guide',
      content: 'A complete walkthrough of the mining lease process from application to approval.',
      category: 'Mining',
      readTime: '5 min read',
    ),
    ArticleModel(
      id: 3,
      title: 'CRZ Regulations: What You Need to Know',
      content: 'Coastal Regulation Zone guidelines for construction near shorelines.',
      category: 'CRZ & Coastal',
      readTime: '6 min read',
    ),
  ];

  @override
  void initState() {
    super.initState();
    context.read<KnowledgeBloc>().add(const LoadKnowledgeBaseEvent());
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF1F8F4),
      body: SafeArea(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header
            Padding(
              padding: const EdgeInsets.fromLTRB(20, 16, 20, 0),
              child: Row(
                children: [
                  GestureDetector(
                    onTap: () => context.pop(),
                    child: const Icon(Icons.chevron_left, size: 28, color: Colors.black),
                  ),
                  const SizedBox(width: 8),
                  const Text(
                    'Knowledge Center',
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      color: Colors.black,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(horizontal: 20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Search bar
                    Container(
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(28),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: Row(
                        children: [
                          Expanded(
                            child: TextField(
                              controller: _searchController,
                              onChanged: (val) {
                                setState(() => _searchQuery = val.toLowerCase());
                              },
                              decoration: const InputDecoration(
                                hintText: 'Search articles...',
                                hintStyle: TextStyle(
                                  color: Color(0xFF9CA3AF),
                                  fontSize: 15,
                                ),
                                prefixIcon: Icon(
                                  Icons.search,
                                  color: Color(0xFF9CA3AF),
                                  size: 22,
                                ),
                                border: InputBorder.none,
                                contentPadding: EdgeInsets.symmetric(
                                  horizontal: 20,
                                  vertical: 14,
                                ),
                              ),
                            ),
                          ),
                          Container(
                            margin: const EdgeInsets.only(right: 6),
                            decoration: const BoxDecoration(
                              color: Color(0xFFF5F5F5),
                              shape: BoxShape.circle,
                            ),
                            child: IconButton(
                              icon: const Icon(
                                Icons.arrow_forward,
                                color: Color(0xFF0F6B35),
                                size: 20,
                              ),
                              onPressed: () {},
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 24),

                    // Category grid (2 columns, 3 rows)
                    GridView.builder(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: 2,
                        crossAxisSpacing: 14,
                        mainAxisSpacing: 14,
                        childAspectRatio: 1.3,
                      ),
                      itemCount: _categories.length,
                      itemBuilder: (context, index) {
                        final cat = _categories[index];
                        return Container(
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(16),
                            border: Border.all(color: const Color(0xFFE2E8F0)),
                          ),
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Container(
                                width: 48,
                                height: 48,
                                decoration: BoxDecoration(
                                  color: cat['bgColor'] as Color,
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                child: Icon(
                                  cat['icon'] as IconData,
                                  color: cat['iconColor'] as Color,
                                  size: 24,
                                ),
                              ),
                              const SizedBox(height: 10),
                              Text(
                                cat['name'] as String,
                                textAlign: TextAlign.center,
                                style: const TextStyle(
                                  fontSize: 13,
                                  fontWeight: FontWeight.w600,
                                  color: Colors.black,
                                ),
                              ),
                            ],
                          ),
                        );
                      },
                    ),
                    const SizedBox(height: 28),

                    // Recent Articles header
                    const Text(
                      'Recent Articles',
                      style: TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.w800,
                        color: Colors.black,
                      ),
                    ),
                    const SizedBox(height: 16),

                    // Articles list
                    BlocBuilder<KnowledgeBloc, KnowledgeState>(
                      builder: (context, state) {
                        List<ArticleModel> articles = _fallbackArticles;
                        if (state is KnowledgeLoaded && state.articles.isNotEmpty) {
                          articles = state.articles;
                        }

                        final filtered = articles.where((a) {
                          if (_searchQuery.isNotEmpty &&
                              !a.title.toLowerCase().contains(_searchQuery) &&
                              !a.content.toLowerCase().contains(_searchQuery)) {
                            return false;
                          }
                          return true;
                        }).toList();

                        if (filtered.isEmpty) {
                          return const EmptyStateWidget(
                            icon: Icons.menu_book_outlined,
                            title: 'No Articles Found',
                            description: 'Try adjusting your search keywords.',
                          );
                        }

                        return ListView.separated(
                          shrinkWrap: true,
                          physics: const NeverScrollableScrollPhysics(),
                          itemCount: filtered.length,
                          separatorBuilder: (_, __) => const SizedBox(height: 12),
                          itemBuilder: (context, index) {
                            final article = filtered[index];
                            return InkWell(
                              onTap: () {
                                context.push(
                                  '/user/article-details/${article.id}',
                                  extra: article,
                                );
                              },
                              borderRadius: BorderRadius.circular(16),
                              child: Container(
                                padding: const EdgeInsets.all(14),
                                decoration: BoxDecoration(
                                  color: Colors.white,
                                  borderRadius: BorderRadius.circular(16),
                                  border: Border.all(color: const Color(0xFFE2E8F0)),
                                ),
                                child: Row(
                                  children: [
                                    // Thumbnail
                                    Container(
                                      width: 64,
                                      height: 64,
                                      decoration: BoxDecoration(
                                        color: const Color(0xFFE8F5E9),
                                        borderRadius: BorderRadius.circular(12),
                                      ),
                                      child: const Icon(
                                        Icons.eco,
                                        size: 28,
                                        color: Color(0xFF2E7D32),
                                      ),
                                    ),
                                    const SizedBox(width: 14),
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            article.title,
                                            style: const TextStyle(
                                              fontSize: 14,
                                              fontWeight: FontWeight.w700,
                                              color: Colors.black,
                                              height: 1.3,
                                            ),
                                            maxLines: 2,
                                            overflow: TextOverflow.ellipsis,
                                          ),
                                          const SizedBox(height: 6),
                                          Row(
                                            children: [
                                              const Icon(
                                                Icons.timer_outlined,
                                                size: 12,
                                                color: Color(0xFF9CA3AF),
                                              ),
                                              const SizedBox(width: 4),
                                              Text(
                                                article.readTime ?? '5 min read',
                                                style: const TextStyle(
                                                  fontSize: 11,
                                                  color: Color(0xFF9CA3AF),
                                                ),
                                              ),
                                              const SizedBox(width: 12),
                                              Container(
                                                padding: const EdgeInsets.symmetric(
                                                  horizontal: 8,
                                                  vertical: 2,
                                                ),
                                                decoration: BoxDecoration(
                                                  color: const Color(0xFFE8F5E9),
                                                  borderRadius: BorderRadius.circular(6),
                                                ),
                                                child: Text(
                                                  article.category ?? 'General',
                                                  style: const TextStyle(
                                                    fontSize: 10,
                                                    fontWeight: FontWeight.w600,
                                                    color: Color(0xFF0F6B35),
                                                  ),
                                                ),
                                              ),
                                            ],
                                          ),
                                        ],
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            );
                          },
                        );
                      },
                    ),
                    const SizedBox(height: 20),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
