import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/expert_model.dart';
import '../../blocs/expert/expert_bloc.dart';
import '../../widgets/feedback/empty_state_widget.dart';

class ExpertListScreen extends StatefulWidget {
  const ExpertListScreen({super.key});

  @override
  State<ExpertListScreen> createState() => _ExpertListScreenState();
}

class _ExpertListScreenState extends State<ExpertListScreen> {
  final TextEditingController _searchController = TextEditingController();
  String? _searchQuery;

  final List<ExpertModel> _fallbackExperts = const [
    ExpertModel(
      id: 1,
      name: 'Dr. Suresh K. Mukherjee',
      email: 'mukherjee@greentip.gov.in',
      mobile: '9820123456',
      specialization: 'Water Pollution & ETP/ZLD Compliance',
      activeAssignedQueriesCount: 2,
      totalResolvedQueriesCount: 48,
      averageRating: 4.9,
      status: 1,
    ),
    ExpertModel(
      id: 2,
      name: 'Er. Ananya Sengupta',
      email: 'ananya@greentip.gov.in',
      mobile: '9830654321',
      specialization: 'Air Pollution & CEMS Continuous Monitoring',
      activeAssignedQueriesCount: 1,
      totalResolvedQueriesCount: 36,
      averageRating: 4.8,
      status: 1,
    ),
    ExpertModel(
      id: 3,
      name: 'Adv. Vikramaditya Rao',
      email: 'vikram@greentip.gov.in',
      mobile: '9845112233',
      specialization: 'SPCB Directives, NGT Appeals & Legal Notices',
      activeAssignedQueriesCount: 3,
      totalResolvedQueriesCount: 62,
      averageRating: 4.9,
      status: 1,
    ),
    ExpertModel(
      id: 4,
      name: 'Dr. Meenakshi Sundaram',
      email: 'meenakshi@greentip.gov.in',
      mobile: '9871223344',
      specialization: 'Hazardous Waste & TSDF Manifest Compliance',
      activeAssignedQueriesCount: 0,
      totalResolvedQueriesCount: 29,
      averageRating: 4.7,
      status: 1,
    ),
  ];

  Timer? _debounceTimer;

  @override
  void initState() {
    super.initState();
    _loadExperts();
  }

  void _loadExperts() {
    context.read<ExpertBloc>().add(LoadExpertsEvent(search: _searchQuery));
  }

  void _onSearchChanged(String val) {
    if (_debounceTimer?.isActive ?? false) _debounceTimer!.cancel();
    _debounceTimer = Timer(const Duration(seconds: 1), () {
      setState(() {
        _searchQuery = val.trim().isEmpty ? null : val.trim();
      });
      _loadExperts();
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
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
        title: const Text('Technical Expert Roster'),
        actions: [
          IconButton(
            icon: const Icon(
              Icons.person_add_alt_1_rounded,
              color: AppColors.primary,
            ),
            onPressed: () => context.push('/admin/experts/new'),
          ),
          const SizedBox(width: 8),
        ],
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
                hintText: 'Search expert by name, specialization, or email...',
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

          // Expert List
          Expanded(
            child: RefreshIndicator(
              onRefresh: () async => _loadExperts(),
              child: BlocBuilder<ExpertBloc, ExpertState>(
                builder: (context, state) {
                  List<ExpertModel> experts = _fallbackExperts;
                  if (state is ExpertsLoaded && state.experts.isNotEmpty) {
                    experts = state.experts;
                  }

                  if (experts.isEmpty) {
                    return const EmptyStateWidget(
                      icon: Icons.people_outline_rounded,
                      title: 'No Experts Found',
                      description:
                          'No technical experts registered in this category.',
                    );
                  }

                  return ListView.separated(
                    padding: const EdgeInsets.all(16.0),
                    itemCount: experts.length,
                    separatorBuilder: (_, _) => const SizedBox(height: 12),
                    itemBuilder: (context, index) {
                      final expert = experts[index];

                      return InkWell(
                        onTap: () => context.push(
                          '/admin/expert-details/${expert.id}',
                          extra: expert,
                        ),
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
                                children: [
                                  CircleAvatar(
                                    radius: 26,
                                    backgroundColor: AppColors.primaryContainer,
                                    child: const Icon(
                                      Icons.person,
                                      color: AppColors.primaryDark,
                                      size: 28,
                                    ),
                                  ),
                                  const SizedBox(width: 14),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Row(
                                          children: [
                                            Text(
                                              expert.name,
                                              style: const TextStyle(
                                                fontSize: 15,
                                                fontWeight: FontWeight.bold,
                                              ),
                                            ),
                                            const SizedBox(width: 6),
                                            const Icon(
                                              Icons.verified,
                                              size: 14,
                                              color: AppColors.primary,
                                            ),
                                          ],
                                        ),
                                        const SizedBox(height: 2),
                                        Text(
                                          expert.specialization ??
                                              'Environmental Specialist',
                                          style: const TextStyle(
                                            fontSize: 12,
                                            color: AppColors.textSecondary,
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                  Switch(
                                    value: expert.isActive,
                                    activeThumbColor: AppColors.primary,
                                    onChanged: (val) {
                                      context.read<ExpertBloc>().add(
                                        ToggleExpertStatusEvent(
                                          expertId: expert.id,
                                          status: val ? 1 : 0,
                                        ),
                                      );
                                    },
                                  ),
                                ],
                              ),
                              const Divider(height: 20),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Row(
                                    children: [
                                      const Icon(
                                        Icons.assignment_outlined,
                                        size: 14,
                                        color: AppColors.textMuted,
                                      ),
                                      const SizedBox(width: 4),
                                      Text(
                                        '${expert.activeAssignedQueriesCount} active / ${expert.totalResolvedQueriesCount} resolved',
                                        style: const TextStyle(
                                          fontSize: 12,
                                          color: AppColors.textSecondary,
                                          fontWeight: FontWeight.w500,
                                        ),
                                      ),
                                    ],
                                  ),
                                  Row(
                                    children: [
                                      const Icon(
                                        Icons.star_rounded,
                                        size: 16,
                                        color: Color(0xFFFFB300),
                                      ),
                                      const SizedBox(width: 4),
                                      Text(
                                        expert.averageRating.toStringAsFixed(1),
                                        style: const TextStyle(
                                          fontSize: 12,
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                      const SizedBox(width: 4),
                                      const Icon(
                                        Icons.chevron_right,
                                        size: 16,
                                        color: AppColors.textMuted,
                                      ),
                                    ],
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
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: AppColors.primaryDark,
        foregroundColor: Colors.white,
        icon: const Icon(Icons.person_add_rounded, size: 20),
        label: const Text(
          'Add Expert',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        onPressed: () => context.push('/admin/experts/new'),
      ),
    );
  }
}
