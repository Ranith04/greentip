import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../blocs/query/query_bloc.dart';
import '../../widgets/cards/query_card.dart';
import '../../widgets/feedback/empty_state_widget.dart';

class MyQueriesScreen extends StatefulWidget {
  const MyQueriesScreen({super.key});

  @override
  State<MyQueriesScreen> createState() => _MyQueriesScreenState();
}

class _MyQueriesScreenState extends State<MyQueriesScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final TextEditingController _searchController = TextEditingController();
  int? _statusFilter;
  String? _searchQuery;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    _tabController.addListener(_onTabChanged);
    _loadQueries();
  }

  void _onTabChanged() {
    if (_tabController.indexIsChanging) return;
    setState(() {
      switch (_tabController.index) {
        case 0:
          _statusFilter = null; // All
          break;
        case 1:
          _statusFilter = 0; // Pending (status 0)
          break;
        case 2:
          _statusFilter = 2; // Responded (status 2)
          break;
        case 3:
          _statusFilter = 3; // Closed (status 3)
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
    _tabController.removeListener(_onTabChanged);
    _tabController.dispose();
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
        title: const Text('My Compliance Queries'),
        bottom: TabBar(
          controller: _tabController,
          labelColor: AppColors.primary,
          unselectedLabelColor: AppColors.textSecondary,
          indicatorColor: AppColors.primary,
          indicatorWeight: 3,
          labelStyle: const TextStyle(
            fontWeight: FontWeight.bold,
            fontSize: 13,
          ),
          tabs: const [
            Tab(text: 'All'),
            Tab(text: 'Pending'),
            Tab(text: 'Responded'),
            Tab(text: 'Closed'),
          ],
        ),
      ),
      body: Column(
        children: [
          // Search input
          Container(
            color: Colors.white,
            padding: const EdgeInsets.symmetric(
              horizontal: 16.0,
              vertical: 10.0,
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
                hintText: 'Search queries by keyword or #QT ID...',
                prefixIcon: const Icon(
                  Icons.search,
                  size: 20,
                  color: AppColors.textMuted,
                ),
                suffixIcon: _searchController.text.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear, size: 18),
                        onPressed: () {
                          _searchController.clear();
                          setState(() {
                            _searchQuery = null;
                          });
                          _loadQueries();
                        },
                      )
                    : null,
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

          // Query list
          Expanded(
            child: RefreshIndicator(
              onRefresh: () async {
                _loadQueries();
              },
              child: BlocBuilder<QueryBloc, QueryState>(
                builder: (context, state) {
                  if (state is QueryLoading) {
                    return const Center(
                      child: CircularProgressIndicator(
                        color: AppColors.primary,
                      ),
                    );
                  }

                  if (state is QueryError) {
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

                  if (state is QueriesLoaded) {
                    final queries = state.queries;

                    if (queries.isEmpty) {
                      return EmptyStateWidget(
                        icon: Icons.assignment_outlined,
                        title: 'No Queries in this Category',
                        description:
                            'You do not have any inquiries matching the active filter.',
                        actionText: 'Ask New Query',
                        onAction: () => context.push('/user/select-category'),
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
                              context.push('/user/query-details/${query.id}'),
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
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: AppColors.primary,
        foregroundColor: Colors.white,
        icon: const Icon(Icons.add, size: 22),
        label: const Text(
          'Ask Query',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        onPressed: () => context.push('/user/select-category'),
      ),
    );
  }
}
