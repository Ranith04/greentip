import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../blocs/admin_query/admin_query_bloc.dart';
import '../../widgets/cards/query_card.dart';
import '../../widgets/feedback/empty_state_widget.dart';

class AllQueriesScreen extends StatefulWidget {
  const AllQueriesScreen({super.key});

  @override
  State<AllQueriesScreen> createState() => _AllQueriesScreenState();
}

class _AllQueriesScreenState extends State<AllQueriesScreen> {
  final TextEditingController _searchController = TextEditingController();
  int? _selectedStatus;
  String? _searchQuery;

  final List<Map<String, dynamic>> _statusFilters = [
    {'label': 'All Inquiries', 'status': null},
    {'label': 'Unassigned', 'status': 0},
    {'label': 'Assigned', 'status': 1},
    {'label': 'Under Review', 'status': 2},
    {'label': 'Resolved', 'status': 3},
    {'label': 'Closed', 'status': 4},
  ];

  @override
  void initState() {
    super.initState();
    _loadQueries();
  }

  void _loadQueries() {
    context.read<AdminQueryBloc>().add(
      LoadAdminQueriesEvent(status: _selectedStatus, search: _searchQuery),
    );
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
        title: const Text('Compliance Inquiry Triage'),
      ),
      body: Column(
        children: [
          // Search
          Container(
            color: Colors.white,
            padding: const EdgeInsets.symmetric(
              horizontal: 16.0,
              vertical: 8.0,
            ),
            child: TextField(
              controller: _searchController,
              onSubmitted: (val) {
                setState(
                  () => _searchQuery = val.trim().isEmpty ? null : val.trim(),
                );
                _loadQueries();
              },
              decoration: InputDecoration(
                hintText: 'Search by ticket ID, plant unit, or keyword...',
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

          // Horizontal Filter Chips
          Container(
            color: Colors.white,
            height: 48,
            child: ListView.separated(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              itemCount: _statusFilters.length,
              separatorBuilder: (_, _) => const SizedBox(width: 8),
              itemBuilder: (context, index) {
                final item = _statusFilters[index];
                final isSelected = _selectedStatus == item['status'];

                return FilterChip(
                  label: Text(item['label'] as String),
                  selected: isSelected,
                  onSelected: (val) {
                    setState(() {
                      _selectedStatus = item['status'] as int?;
                    });
                    _loadQueries();
                  },
                  selectedColor: AppColors.primaryContainer,
                  labelStyle: TextStyle(
                    fontSize: 12,
                    fontWeight: isSelected
                        ? FontWeight.bold
                        : FontWeight.normal,
                    color: isSelected
                        ? AppColors.primaryDark
                        : AppColors.textPrimary,
                  ),
                  side: BorderSide(
                    color: isSelected ? AppColors.primary : AppColors.divider,
                  ),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(20),
                  ),
                );
              },
            ),
          ),
          const Divider(height: 1),

          // Query List
          Expanded(
            child: RefreshIndicator(
              onRefresh: () async => _loadQueries(),
              child: BlocBuilder<AdminQueryBloc, AdminQueryState>(
                builder: (context, state) {
                  if (state is AdminQueryLoading) {
                    return const Center(
                      child: CircularProgressIndicator(
                        color: AppColors.primary,
                      ),
                    );
                  }

                  if (state is AdminQueryError) {
                    return Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(
                            Icons.error_outline,
                            size: 48,
                            color: AppColors.error,
                          ),
                          const SizedBox(height: 12),
                          Text(
                            state.message,
                            style: const TextStyle(
                              color: AppColors.textSecondary,
                            ),
                          ),
                          const SizedBox(height: 16),
                          ElevatedButton(
                            onPressed: _loadQueries,
                            child: const Text('Retry'),
                          ),
                        ],
                      ),
                    );
                  }

                  if (state is AdminQueriesLoaded) {
                    final queries = state.queries;

                    if (queries.isEmpty) {
                      return const EmptyStateWidget(
                        icon: Icons.assignment_turned_in_outlined,
                        title: 'No Matching Inquiries',
                        description:
                            'No compliance tickets found under the selected filter.',
                      );
                    }

                    return ListView.separated(
                      padding: const EdgeInsets.all(16.0),
                      itemCount: queries.length,
                      separatorBuilder: (_, _) => const SizedBox(height: 12),
                      itemBuilder: (context, index) {
                        final query = queries[index];
                        return QueryCard(
                          query: query,
                          onTap: () =>
                              context.push('/admin/query-details/${query.id}'),
                        );
                      },
                    );
                  }

                  return const SizedBox.shrink();
                },
              ),
            ),
          ),
        ],
      ),
    );
  }
}
