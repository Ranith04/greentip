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
  int? _selectedCategory;
  String _searchQuery = '';

  final List<ArticleModel> _fallbackArticles = const [
    ArticleModel(
      id: 1,
      title: 'Water Act 1974: Standard Operating Limits & Penal Provisions',
      content:
          'Under the Water (Prevention and Control of Pollution) Act, 1974, all industrial units discharging sewage or trade effluents into water bodies, sewers, or on land must obtain prior Consent to Operate (CTO). Key requirements include zero untreated effluent discharge, strict adherence to BOD/COD thresholds, and real-time CEMS integration.',
      category: 'Water Pollution',
      readTime: '4 min read',
    ),
    ArticleModel(
      id: 2,
      title: 'Air Pollution Standards for Captive DG Sets & Boiler Stacks',
      content:
          'SPCB and CPCB regulations dictate mandatory acoustic enclosures and minimum stack heights calculated based on generator capacity (H = h + 0.2 × √kVA). Regular flue gas emission testing for Particulate Matter (PM), SO2, and NOx is strictly enforced during biannual audits.',
      category: 'Air Standards',
      readTime: '5 min read',
    ),
    ArticleModel(
      id: 3,
      title: 'Hazardous Waste Management Rules: Form 10 Manifest Protocol',
      content:
          'Transportation of hazardous wastes to Common TSDF facilities requires strict adherence to the 7-copy Form 10 manifest system. Industrial facilities must maintain Form 3 register of waste generation and submit annual Form 4 returns before June 30th each year.',
      category: 'Hazardous Waste',
      readTime: '6 min read',
    ),
    ArticleModel(
      id: 4,
      title: 'Step-by-Step Guide for Consent to Operate (CTO) Renewal in 2024',
      content:
          'Renewals must be submitted at least 120 days prior to expiry via the state online single window portal. Essential attachments include capital investment CA certificates, past year production logs, effluent analysis, and proof of green belt development.',
      category: 'Consents & Clearances',
      readTime: '7 min read',
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
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('Statutory Knowledge Center'),
      ),
      body: Column(
        children: [
          // Search Box
          Container(
            color: Colors.white,
            padding: const EdgeInsets.symmetric(
              horizontal: 16.0,
              vertical: 12.0,
            ),
            child: TextField(
              controller: _searchController,
              onChanged: (val) {
                setState(() {
                  _searchQuery = val.toLowerCase();
                });
              },
              decoration: InputDecoration(
                hintText: 'Search laws, acts, thresholds, forms...',
                prefixIcon: const Icon(
                  Icons.search,
                  size: 20,
                  color: AppColors.textMuted,
                ),
                contentPadding: const EdgeInsets.symmetric(vertical: 10),
                filled: true,
                fillColor: AppColors.background,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                  borderSide: BorderSide.none,
                ),
              ),
            ),
          ),

          // Categories Filter Row
          Container(
            color: Colors.white,
            height: 48,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              children: [
                _filterChip('All Domains', null),
                _filterChip('Water Pollution', 1),
                _filterChip('Air Standards', 2),
                _filterChip('Hazardous Waste', 3),
                _filterChip('Consents & Clearances', 4),
              ],
            ),
          ),
          const Divider(height: 1),

          // Article List
          Expanded(
            child: BlocBuilder<KnowledgeBloc, KnowledgeState>(
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
                    description:
                        'Try adjusting your search keywords or filter.',
                  );
                }

                return ListView.separated(
                  padding: const EdgeInsets.all(16.0),
                  itemCount: filtered.length,
                  separatorBuilder: (_, _) => const SizedBox(height: 12),
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
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(color: AppColors.divider),
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Container(
                                  padding: const EdgeInsets.symmetric(
                                    horizontal: 8,
                                    vertical: 3,
                                  ),
                                  decoration: BoxDecoration(
                                    color: AppColors.primaryContainer,
                                    borderRadius: BorderRadius.circular(6),
                                  ),
                                  child: Text(
                                    article.category ?? 'Compliance',
                                    style: const TextStyle(
                                      fontSize: 11,
                                      fontWeight: FontWeight.bold,
                                      color: AppColors.primaryDark,
                                    ),
                                  ),
                                ),
                                Row(
                                  children: [
                                    const Icon(
                                      Icons.timer_outlined,
                                      size: 13,
                                      color: AppColors.textMuted,
                                    ),
                                    const SizedBox(width: 4),
                                    Text(
                                      article.readTime ?? '5 min read',
                                      style: const TextStyle(
                                        fontSize: 11,
                                        color: AppColors.textMuted,
                                      ),
                                    ),
                                  ],
                                ),
                              ],
                            ),
                            const SizedBox(height: 10),
                            Text(
                              article.title,
                              style: const TextStyle(
                                fontSize: 15,
                                fontWeight: FontWeight.bold,
                                color: AppColors.textPrimary,
                                height: 1.3,
                              ),
                            ),
                            const SizedBox(height: 6),
                            Text(
                              article.content,
                              maxLines: 2,
                              overflow: TextOverflow.ellipsis,
                              style: const TextStyle(
                                fontSize: 13,
                                color: AppColors.textSecondary,
                                height: 1.4,
                              ),
                            ),
                            const SizedBox(height: 12),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                const Text(
                                  'Read full guideline',
                                  style: TextStyle(
                                    fontSize: 12,
                                    fontWeight: FontWeight.bold,
                                    color: AppColors.primary,
                                  ),
                                ),
                                const Icon(
                                  Icons.arrow_forward_rounded,
                                  size: 16,
                                  color: AppColors.primary,
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _filterChip(String label, int? categoryId) {
    final isSelected = _selectedCategory == categoryId;
    return Padding(
      padding: const EdgeInsets.only(right: 8.0),
      child: FilterChip(
        label: Text(label),
        selected: isSelected,
        onSelected: (val) {
          setState(() {
            _selectedCategory = val ? categoryId : null;
          });
        },
        selectedColor: AppColors.primaryContainer,
        labelStyle: TextStyle(
          fontSize: 12,
          fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
          color: isSelected ? AppColors.primaryDark : AppColors.textPrimary,
        ),
        side: BorderSide(
          color: isSelected ? AppColors.primary : AppColors.divider,
        ),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      ),
    );
  }
}
