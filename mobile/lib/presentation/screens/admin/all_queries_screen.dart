import 'dart:async';
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

class _AllQueriesScreenState extends State<AllQueriesScreen>
    with SingleTickerProviderStateMixin {
  final TextEditingController _searchController = TextEditingController();
  final ScrollController _scrollController = ScrollController();
  late TabController _tabController;

  Timer? _debounceTimer;
  String? _searchQuery;
  int _currentPage = 1;

  final List<Map<String, dynamic>> _tabs = [
    {'label': 'All', 'status': null},
    {'label': 'Unassigned', 'status': 0},
    {'label': 'In Progress', 'status': 2},
    {'label': 'Resolved', 'status': 3},
  ];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: _tabs.length, vsync: this);
    _tabController.addListener(_onTabChanged);
    _scrollController.addListener(_onScroll);
    _loadQueries();
  }

  void _onTabChanged() {
    if (_tabController.indexIsChanging) return;
    _currentPage = 1;
    _loadQueries();
  }

  void _onSearchChanged(String val) {
    if (_debounceTimer?.isActive ?? false) _debounceTimer!.cancel();
    _debounceTimer = Timer(const Duration(seconds: 1), () {
      setState(() {
        _searchQuery = val.trim().isEmpty ? null : val.trim();
        _currentPage = 1;
      });
      _loadQueries();
    });
  }

  void _onScroll() {
    if (_scrollController.position.pixels >=
        _scrollController.position.maxScrollExtent - 200) {
      final state = context.read<AdminQueryBloc>().state;
      if (state is AdminQueriesLoaded && state.hasMoreData) {
        _currentPage++;
        _loadQueries(isLoadMore: true);
      }
    }
  }

  void _loadQueries({bool isLoadMore = false}) {
    final status = _tabs[_tabController.index]['status'] as int?;
    context.read<AdminQueryBloc>().add(
      LoadAdminQueriesEvent(
        status: status,
        search: _searchQuery,
        page: _currentPage,
        isLoadMore: isLoadMore,
      ),
    );
  }

  @override
  void dispose() {
    _searchController.dispose();
    _scrollController.dispose();
    _tabController.dispose();
    _debounceTimer?.cancel();
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
        bottom: TabBar(
          controller: _tabController,
          isScrollable: true,
          labelColor: AppColors.primaryDark,
          unselectedLabelColor: AppColors.textMuted,
          indicatorColor: AppColors.primary,
          indicatorWeight: 3,
          tabs: _tabs.map((t) => Tab(text: t['label'] as String)).toList(),
        ),
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
              onChanged: _onSearchChanged,
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
          const Divider(height: 1),

          // Query List
          Expanded(
            child: RefreshIndicator(
              onRefresh: () async {
                _currentPage = 1;
                _loadQueries();
              },
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
                            onPressed: () {
                              _currentPage = 1;
                              _loadQueries();
                            },
                            child: const Text('Retry'),
                          ),
                        ],
                      ),
                    );
                  }

                  if (state is AdminQueriesLoaded) {
                    final queries = state.queries;

                    if (queries.isEmpty) {
                      return ListView(
                        children: const [
                          SizedBox(height: 100),
                          EmptyStateWidget(
                            icon: Icons.assignment_turned_in_outlined,
                            title: 'No Matching Inquiries',
                            description:
                                'No compliance tickets found under the selected filter.',
                          ),
                        ],
                      );
                    }

                    return ListView.separated(
                      controller: _scrollController,
                      padding: const EdgeInsets.all(16.0),
                      itemCount: queries.length + (state.hasMoreData ? 1 : 0),
                      separatorBuilder: (_, _) => const SizedBox(height: 12),
                      itemBuilder: (context, index) {
                        if (index == queries.length) {
                          return const Padding(
                            padding: EdgeInsets.all(16.0),
                            child: Center(
                              child: CircularProgressIndicator(
                                color: AppColors.primary,
                              ),
                            ),
                          );
                        }
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
