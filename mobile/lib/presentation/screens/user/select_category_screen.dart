import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/category_model.dart';
import '../../blocs/query/query_bloc.dart';

class SelectCategoryScreen extends StatefulWidget {
  const SelectCategoryScreen({super.key});

  @override
  State<SelectCategoryScreen> createState() => _SelectCategoryScreenState();
}

class _SelectCategoryScreenState extends State<SelectCategoryScreen> {
  CategoryModel? _selectedCategory;

  final List<Map<String, dynamic>> _categories = [
    {
      'id': 1,
      'name': 'Environmental Clearance',
      'description': 'EIA notification, EC conditions and compliance',
      'icon': Icons.eco,
      'color': Color(0xFF2E7D32),
    },
    {
      'id': 2,
      'name': 'Construction',
      'description': 'Building permissions, RERA environmental norms',
      'icon': Icons.home_work_outlined,
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
  ];

  @override
  void initState() {
    super.initState();
    context.read<QueryBloc>().add(LoadCategoriesEvent());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
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
                    'Select Category',
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      color: Colors.black,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 8),

            // Subtitle
            const Padding(
              padding: EdgeInsets.symmetric(horizontal: 20),
              child: Text(
                'Choose the category that best fits your query',
                style: TextStyle(
                  fontSize: 15,
                  color: Color(0xFF6B7280),
                ),
              ),
            ),
            const SizedBox(height: 24),

            // Categories Grid (2 columns)
            Expanded(
              child: GridView.builder(
                padding: const EdgeInsets.symmetric(horizontal: 20),
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  crossAxisSpacing: 14,
                  mainAxisSpacing: 14,
                  childAspectRatio: 1.0,
                ),
                itemCount: _categories.length,
                itemBuilder: (context, index) {
                  final cat = _categories[index];
                  final id = cat['id'] as int;
                  final name = cat['name'] as String;
                  final desc = cat['description'] as String;
                  final icon = cat['icon'] as IconData;
                  final color = cat['color'] as Color;
                  final isSelected = _selectedCategory?.id == id;

                  return GestureDetector(
                    onTap: () {
                      setState(() {
                        _selectedCategory = CategoryModel(
                          id: id,
                          name: name,
                          description: desc,
                        );
                      });
                    },
                    child: AnimatedContainer(
                      duration: const Duration(milliseconds: 200),
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: isSelected
                            ? const Color(0xFFE8F5E9)
                            : Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(
                          color: isSelected
                              ? const Color(0xFF0F6B35)
                              : const Color(0xFFE2E8F0),
                          width: isSelected ? 2 : 1,
                        ),
                      ),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Container(
                            width: 52,
                            height: 52,
                            decoration: BoxDecoration(
                              color: color.withValues(alpha: 0.12),
                              borderRadius: BorderRadius.circular(14),
                            ),
                            child: Icon(icon, color: color, size: 26),
                          ),
                          const SizedBox(height: 12),
                          Text(
                            name,
                            textAlign: TextAlign.center,
                            style: TextStyle(
                              fontSize: 14,
                              fontWeight: FontWeight.w700,
                              color: isSelected
                                  ? const Color(0xFF0F6B35)
                                  : Colors.black,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            desc,
                            textAlign: TextAlign.center,
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                            style: const TextStyle(
                              fontSize: 11,
                              color: Color(0xFF9CA3AF),
                              height: 1.3,
                            ),
                          ),
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),

            // Bottom button
            Container(
              padding: const EdgeInsets.fromLTRB(20, 12, 20, 20),
              child: SizedBox(
                width: double.infinity,
                height: 56,
                child: ElevatedButton(
                  onPressed: _selectedCategory == null
                      ? null
                      : () {
                          context.push(
                            '/user/ask-query',
                            extra: _selectedCategory,
                          );
                        },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF0F6B35),
                    foregroundColor: Colors.white,
                    disabledBackgroundColor: const Color(0xFFD1D5DB),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(28),
                    ),
                    elevation: 0,
                  ),
                  child: const Text(
                    'Continue',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
