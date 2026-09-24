import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/category_model.dart';
import '../../blocs/query/query_bloc.dart';
import '../../widgets/buttons/app_buttons.dart';

class SelectCategoryScreen extends StatefulWidget {
  const SelectCategoryScreen({super.key});

  @override
  State<SelectCategoryScreen> createState() => _SelectCategoryScreenState();
}

class _SelectCategoryScreenState extends State<SelectCategoryScreen> {
  final TextEditingController _searchController = TextEditingController();
  CategoryModel? _selectedCategory;
  String _searchQuery = '';

  final List<Map<String, dynamic>> _fallbackCategories = [
    {
      'id': 1,
      'name': 'Environmental Clearance',
      'description': 'EIA notification, EC conditions and compliance',
      'icon': Icons.verified_outlined,
      'color': Color(0xFF16A34A),
    },
    {
      'id': 2,
      'name': 'Construction',
      'description': 'Building permissions, RERA environmental norms',
      'icon': Icons.construction_outlined,
      'color': Color(0xFFD97706),
    },
    {
      'id': 3,
      'name': 'Real Estate',
      'description': 'Townships, area development projects compliance',
      'icon': Icons.apartment_outlined,
      'color': Color(0xFF4338CA),
    },
    {
      'id': 4,
      'name': 'Mining',
      'description': 'Mining leases, forest clearance, restoration',
      'icon': Icons.landscape_outlined,
      'color': Color(0xFFDC2626),
    },
    {
      'id': 5,
      'name': 'Enviro-Legal',
      'description': 'NGT cases, SPCB show-cause notices, hearings',
      'icon': Icons.gavel_rounded,
      'color': Color(0xFF0D9488),
    },
    {
      'id': 6,
      'name': 'Industry',
      'description': 'Manufacturing units, CTO/CTE renewals, inspections',
      'icon': Icons.factory_outlined,
      'color': Color(0xFF0284C7),
    },
    {
      'id': 7,
      'name': 'CRZ & Coastal Regulation',
      'description': 'Coastal zone projects and shoreline approvals',
      'icon': Icons.water_drop_outlined,
      'color': Color(0xFF0284C7),
    },
  ];

  @override
  void initState() {
    super.initState();
    context.read<QueryBloc>().add(LoadCategoriesEvent());
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('Select Compliance Domain'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
          onPressed: () => context.pop(),
        ),
      ),
      body: SafeArea(
        child: Column(
          children: [
            // Search Input
            Padding(
              padding: const EdgeInsets.symmetric(
                horizontal: 20.0,
                vertical: 8.0,
              ),
              child: TextField(
                controller: _searchController,
                onChanged: (val) {
                  setState(() {
                    _searchQuery = val.toLowerCase();
                  });
                },
                decoration: InputDecoration(
                  hintText: 'Search domain, legal provision, or waste type...',
                  prefixIcon: const Icon(
                    Icons.search,
                    size: 20,
                    color: AppColors.textMuted,
                  ),
                  filled: true,
                  fillColor: AppColors.background,
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: BorderSide.none,
                  ),
                ),
              ),
            ),
            const SizedBox(height: 8),

            // Categories List
            Expanded(
              child: ListView.separated(
                padding: const EdgeInsets.symmetric(
                  horizontal: 20.0,
                  vertical: 12.0,
                ),
                itemCount: _fallbackCategories.length,
                separatorBuilder: (_, _) => const SizedBox(height: 12),
                itemBuilder: (context, index) {
                  final cat = _fallbackCategories[index];
                  final name = cat['name'] as String;
                  final desc = cat['description'] as String;
                  final icon = cat['icon'] as IconData;
                  final color = cat['color'] as Color;
                  final id = cat['id'] as int;

                  if (_searchQuery.isNotEmpty &&
                      !name.toLowerCase().contains(_searchQuery) &&
                      !desc.toLowerCase().contains(_searchQuery)) {
                    return const SizedBox.shrink();
                  }

                  final isSelected = _selectedCategory?.id == id;

                  return InkWell(
                    onTap: () {
                      setState(() {
                        _selectedCategory = CategoryModel(
                          id: id,
                          name: name,
                          description: desc,
                        );
                      });
                    },
                    borderRadius: BorderRadius.circular(16),
                    child: Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: isSelected
                            ? AppColors.primaryContainer.withValues(alpha: 0.5)
                            : Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(
                          color: isSelected
                              ? AppColors.primary
                              : AppColors.divider,
                          width: isSelected ? 2 : 1,
                        ),
                      ),
                      child: Row(
                        children: [
                          Container(
                            width: 48,
                            height: 48,
                            decoration: BoxDecoration(
                              color: color.withValues(alpha: 0.12),
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: Icon(icon, color: color, size: 24),
                          ),
                          const SizedBox(width: 14),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  name,
                                  style: const TextStyle(
                                    fontSize: 15,
                                    fontWeight: FontWeight.bold,
                                    color: AppColors.textPrimary,
                                  ),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  desc,
                                  style: const TextStyle(
                                    fontSize: 12,
                                    color: AppColors.textSecondary,
                                    height: 1.3,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          const SizedBox(width: 8),
                          Icon(
                            isSelected
                                ? Icons.radio_button_checked
                                : Icons.radio_button_off_rounded,
                            color: isSelected
                                ? AppColors.primary
                                : Colors.grey.shade400,
                            size: 22,
                          ),
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),

            // Bottom Continue
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                border: Border(top: BorderSide(color: AppColors.divider)),
              ),
              child: AppPrimaryButton(
                text: 'Continue with Selected Domain',
                onPressed: _selectedCategory == null
                    ? null
                    : () {
                        context.push(
                          '/user/ask-query',
                          extra: _selectedCategory,
                        );
                      },
              ),
            ),
          ],
        ),
      ),
    );
  }
}
