import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/utils/date_formatter.dart';
import '../../blocs/query/query_bloc.dart';
import '../../widgets/feedback/empty_state_widget.dart';

class MyQueriesScreen extends StatefulWidget {
  const MyQueriesScreen({super.key});

  @override
  State<MyQueriesScreen> createState() => _MyQueriesScreenState();
}

class _MyQueriesScreenState extends State<MyQueriesScreen> {
  final TextEditingController _searchController = TextEditingController();
  int _selectedFilterIndex = 0;
  int? _statusFilter;
  String? _searchQuery;

  final List<String> _filterLabels = ['All', 'Pending', 'Responded', 'Closed'];

  @override
  void initState() {
    super.initState();
    _loadQueries();
  }

  void _onFilterChanged(int index) {
    setState(() {
      _selectedFilterIndex = index;
      switch (index) {
        case 0:
          _statusFilter = null;
          break;
        case 1:
          _statusFilter = 0;
          break;
        case 2:
          _statusFilter = 2;
          break;
        case 3:
          _statusFilter = 3;
          break;
      }
    });
    _loadQueries();
  }

  void _loadQueries() {
    context.read<QueryBloc>().add(
      LoadMyQueriesEvent(status: _statusFilter, search: _searchQuery),
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
                    'My Queries',
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

            // Filter pills
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Row(
                children: List.generate(_filterLabels.length, (index) {
                  final isSelected = _selectedFilterIndex == index;
                  return Padding(
                    padding: const EdgeInsets.only(right: 10),
                    child: GestureDetector(
                      onTap: () => _onFilterChanged(index),
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                        decoration: BoxDecoration(
                          color: isSelected ? const Color(0xFF0F6B35) : Colors.transparent,
                          borderRadius: BorderRadius.circular(24),
                          border: isSelected
                              ? null
                              : Border.all(color: const Color(0xFFE2E8F0)),
                        ),
                        child: Text(
                          _filterLabels[index],
                          style: TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.w600,
                            color: isSelected ? Colors.white : const Color(0xFF6B7280),
                          ),
                        ),
                      ),
                    ),
                  );
                }),
              ),
            ),
            const SizedBox(height: 16),

            // Search bar
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Container(
                decoration: BoxDecoration(
                  color: const Color(0xFFF5F5F5),
                  borderRadius: BorderRadius.circular(28),
                ),
                child: TextField(
                  controller: _searchController,
                  onSubmitted: (val) {
                    setState(() {
                      _searchQuery = val.trim().isEmpty ? null : val.trim();
                    });
                    _loadQueries();
                  },
                  decoration: InputDecoration(
                    hintText: 'Search queries...',
                    hintStyle: const TextStyle(
                      color: Color(0xFF9CA3AF),
                      fontSize: 15,
                    ),
                    prefixIcon: const Icon(
                      Icons.search,
                      color: Color(0xFF9CA3AF),
                      size: 22,
                    ),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear, size: 18),
                            onPressed: () {
                              _searchController.clear();
                              setState(() => _searchQuery = null);
                              _loadQueries();
                            },
                          )
                        : null,
                    border: InputBorder.none,
                    contentPadding: const EdgeInsets.symmetric(
                      horizontal: 20,
                      vertical: 14,
                    ),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 16),

            // Query list
            Expanded(
              child: RefreshIndicator(
                onRefresh: () async => _loadQueries(),
                child: BlocBuilder<QueryBloc, QueryState>(
                  builder: (context, state) {
                    if (state is QueryLoading) {
                      return const Center(
                        child: CircularProgressIndicator(
                          color: Color(0xFF0F6B35),
                        ),
                      );
                    }

                    if (state is QueryError) {
                      return Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(Icons.error_outline, size: 48, color: AppColors.error),
                            const SizedBox(height: 12),
                            Text(state.message, style: const TextStyle(color: AppColors.textSecondary)),
                            const SizedBox(height: 16),
                            ElevatedButton(
                              onPressed: _loadQueries,
                              child: const Text('Retry'),
                            ),
                          ],
                        ),
                      );
                    }

                    if (state is QueriesLoaded) {
                      final queries = state.queries;

                      if (queries.isEmpty) {
                        return EmptyStateWidget(
                          icon: Icons.assignment_outlined,
                          title: 'No Queries Found',
                          description: 'You don\'t have any queries matching this filter.',
                          actionText: 'Ask New Query',
                          onAction: () => context.push('/user/select-category'),
                        );
                      }

                      return ListView.separated(
                        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
                        itemCount: queries.length,
                        separatorBuilder: (_, __) => const SizedBox(height: 12),
                        itemBuilder: (context, index) {
                          final query = queries[index];
                          return _buildQueryCard(query, context);
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
      ),
    );
  }

  Widget _buildQueryCard(dynamic query, BuildContext context) {
    // Status badge colors matching reference
    Color statusBgColor;
    Color statusTextColor;
    String statusLabel = query.statusName ?? 'Pending';

    switch (statusLabel.toLowerCase()) {
      case 'responded':
      case 'answered':
        statusBgColor = const Color(0xFF0F6B35);
        statusTextColor = Colors.white;
        statusLabel = 'Responded';
        break;
      case 'pending':
      case 'submitted':
        statusBgColor = const Color(0xFFEA580C);
        statusTextColor = Colors.white;
        statusLabel = 'Pending';
        break;
      case 'in progress':
      case 'assigned':
        statusBgColor = const Color(0xFF1D4ED8);
        statusTextColor = Colors.white;
        statusLabel = 'In Progress';
        break;
      case 'closed':
        statusBgColor = const Color(0xFF475569);
        statusTextColor = Colors.white;
        statusLabel = 'Closed';
        break;
      default:
        statusBgColor = const Color(0xFF6B7280);
        statusTextColor = Colors.white;
    }

    return GestureDetector(
      onTap: () => context.push('/user/query-details/${query.id}'),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: const Color(0xFFE2E8F0)),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  child: Text(
                    query.title,
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                      color: Colors.black,
                      height: 1.3,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
                const SizedBox(width: 12),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                  decoration: BoxDecoration(
                    color: statusBgColor,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    statusLabel,
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                      color: statusTextColor,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 6),
            Text(
              query.categoryName ?? 'General',
              style: const TextStyle(
                fontSize: 14,
                color: Color(0xFF6B7280),
              ),
            ),
            const SizedBox(height: 4),
            Text(
              DateFormatter.formatShort(query.createdAt),
              style: const TextStyle(
                fontSize: 13,
                color: Color(0xFF9CA3AF),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
