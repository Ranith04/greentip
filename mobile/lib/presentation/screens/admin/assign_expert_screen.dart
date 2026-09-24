import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/expert_model.dart';
import '../../../data/models/query_model.dart';
import '../../blocs/admin_query/admin_query_bloc.dart';
import '../../blocs/expert/expert_bloc.dart';
import '../../widgets/buttons/app_buttons.dart';

class AssignExpertScreen extends StatefulWidget {
  final int queryId;
  final QueryModel? query;

  const AssignExpertScreen({super.key, required this.queryId, this.query});

  @override
  State<AssignExpertScreen> createState() => _AssignExpertScreenState();
}

class _AssignExpertScreenState extends State<AssignExpertScreen> {
  int? _selectedExpertId;
  final TextEditingController _remarksController = TextEditingController();

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

  @override
  void initState() {
    super.initState();
    context.read<ExpertBloc>().add(const LoadExpertsEvent());
  }

  @override
  void dispose() {
    _remarksController.dispose();
    super.dispose();
  }

  void _onConfirmAssignment() {
    if (_selectedExpertId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Please select an expert from the roster'),
        ),
      );
      return;
    }

    context.read<AdminQueryBloc>().add(
      AssignExpertEvent(
        queryId: widget.queryId,
        expertId: _selectedExpertId!,
        remarks: _remarksController.text.trim(),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocListener<AdminQueryBloc, AdminQueryState>(
      listener: (context, state) {
        if (state is AdminQueryActionSuccess) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(state.message),
              backgroundColor: AppColors.success,
            ),
          );
          context.pop();
        } else if (state is AdminQueryError) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(state.message),
              backgroundColor: AppColors.error,
            ),
          );
        }
      },
      child: Scaffold(
        backgroundColor: AppColors.background,
        appBar: AppBar(
          backgroundColor: Colors.white,
          elevation: 0,
          title: const Text('Assign Technical Expert'),
          leading: IconButton(
            icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
            onPressed: () => context.pop(),
          ),
        ),
        body: SafeArea(
          child: Column(
            children: [
              // Ticket Header
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(16),
                color: Colors.white,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'TICKET #QT-${widget.queryId.toString().padLeft(4, '0')}',
                      style: const TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: AppColors.primary,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      widget.query?.title ?? 'Environmental Notice Triage',
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: const TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: AppColors.textPrimary,
                      ),
                    ),
                  ],
                ),
              ),
              const Divider(height: 1),

              // Expert List
              Expanded(
                child: BlocBuilder<ExpertBloc, ExpertState>(
                  builder: (context, state) {
                    List<ExpertModel> experts = _fallbackExperts;
                    if (state is ExpertsLoaded && state.experts.isNotEmpty) {
                      experts = state.experts;
                    }

                    return ListView.separated(
                      padding: const EdgeInsets.all(16.0),
                      itemCount: experts.length,
                      separatorBuilder: (_, _) => const SizedBox(height: 12),
                      itemBuilder: (context, index) {
                        final expert = experts[index];
                        final isSelected = _selectedExpertId == expert.id;
                        final isAvailable =
                            expert.activeAssignedQueriesCount == 0;

                        return InkWell(
                          onTap: () {
                            setState(() {
                              _selectedExpertId = expert.id;
                            });
                          },
                          borderRadius: BorderRadius.circular(16),
                          child: Container(
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(
                              color: isSelected
                                  ? AppColors.primaryContainer.withValues(
                                      alpha: 0.4,
                                    )
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
                                CircleAvatar(
                                  radius: 24,
                                  backgroundColor: isSelected
                                      ? AppColors.primary
                                      : AppColors.primaryContainer,
                                  child: Icon(
                                    Icons.person_rounded,
                                    color: isSelected
                                        ? Colors.white
                                        : AppColors.primaryDark,
                                    size: 26,
                                  ),
                                ),
                                const SizedBox(width: 14),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        expert.name,
                                        style: const TextStyle(
                                          fontSize: 15,
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                      const SizedBox(height: 3),
                                      Text(
                                        expert.specialization ??
                                            'Environmental Specialist',
                                        style: const TextStyle(
                                          fontSize: 12,
                                          color: AppColors.primaryDark,
                                          fontWeight: FontWeight.w500,
                                        ),
                                      ),
                                      const SizedBox(height: 6),
                                      Row(
                                        children: [
                                          Container(
                                            padding: const EdgeInsets.symmetric(
                                              horizontal: 8,
                                              vertical: 2,
                                            ),
                                            decoration: BoxDecoration(
                                              color: isAvailable
                                                  ? AppColors.successLight
                                                  : AppColors.warningLight,
                                              borderRadius:
                                                  BorderRadius.circular(6),
                                            ),
                                            child: Text(
                                              '${expert.activeAssignedQueriesCount} active task(s)',
                                              style: TextStyle(
                                                fontSize: 11,
                                                fontWeight: FontWeight.bold,
                                                color: isAvailable
                                                    ? AppColors.success
                                                    : AppColors.warning,
                                              ),
                                            ),
                                          ),
                                          const SizedBox(width: 10),
                                          const Icon(
                                            Icons.star_rounded,
                                            size: 14,
                                            color: Color(0xFFFFB300),
                                          ),
                                          const SizedBox(width: 2),
                                          Text(
                                            expert.averageRating
                                                .toStringAsFixed(1),
                                            style: const TextStyle(
                                              fontSize: 11,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ],
                                  ),
                                ),
                                // ignore: deprecated_member_use
                                Radio<int>(
                                  value: expert.id,
                                  // ignore: deprecated_member_use
                                  groupValue: _selectedExpertId,
                                  activeColor: AppColors.primary,
                                  // ignore: deprecated_member_use
                                  onChanged: (val) {
                                    setState(() {
                                      _selectedExpertId = val;
                                    });
                                  },
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

              // Remarks & Dispatch
              Container(
                padding: const EdgeInsets.all(20),
                decoration: const BoxDecoration(
                  color: Colors.white,
                  border: Border(top: BorderSide(color: AppColors.divider)),
                ),
                child: Column(
                  children: [
                    TextField(
                      controller: _remarksController,
                      decoration: InputDecoration(
                        hintText:
                            'Add internal assignment remarks or SLA instructions...',
                        contentPadding: const EdgeInsets.symmetric(
                          horizontal: 14,
                          vertical: 10,
                        ),
                        filled: true,
                        fillColor: AppColors.background,
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(10),
                          borderSide: BorderSide.none,
                        ),
                      ),
                    ),
                    const SizedBox(height: 14),
                    BlocBuilder<AdminQueryBloc, AdminQueryState>(
                      builder: (context, state) {
                        return AppPrimaryButton(
                          text: 'Confirm & Dispatch Assignment',
                          isLoading: state is AdminQueryLoading,
                          onPressed: _onConfirmAssignment,
                        );
                      },
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
