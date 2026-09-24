import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../blocs/query/query_bloc.dart';

class ReviewQueryScreen extends StatelessWidget {
  final Map<String, dynamic> formData;

  const ReviewQueryScreen({super.key, required this.formData});

  void _submitQuery(BuildContext context) {
    context.read<QueryBloc>().add(
      SubmitQueryEvent(
        title: formData['title'],
        description: formData['description'],
        urgency: 'Normal',
        categoryId: formData['categoryId'],
        industryName: formData['projectType'],
        sector: formData['projectType'],
        state: formData['location'],
        consentNumber: formData['projectArea'],
        filePaths: formData['filePaths'] as List<String>?,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final attachments = formData['filePaths'] as List<String>? ?? [];

    return BlocListener<QueryBloc, QueryState>(
      listener: (context, state) {
        if (state is QuerySubmitSuccess) {
          context.go('/user/query-submitted', extra: state.query);
        } else if (state is QueryError) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(state.message),
              backgroundColor: AppColors.error,
              behavior: SnackBarBehavior.floating,
            ),
          );
        }
      },
      child: Scaffold(
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
                      'Review Your Query',
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
              const Padding(
                padding: EdgeInsets.symmetric(horizontal: 20),
                child: Text(
                  'Please check the details before submitting',
                  style: TextStyle(fontSize: 15, color: Color(0xFF6B7280)),
                ),
              ),
              const SizedBox(height: 24),

              Expanded(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.symmetric(horizontal: 20.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Category badge
                      if (formData['categoryName'] != null) ...[
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                          decoration: BoxDecoration(
                            color: const Color(0xFFE8F5E9),
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: Text(
                            formData['categoryName'].toString(),
                            style: const TextStyle(
                              fontSize: 13,
                              fontWeight: FontWeight.w600,
                              color: Color(0xFF0F6B35),
                            ),
                          ),
                        ),
                        const SizedBox(height: 24),
                      ],

                      _buildReviewField('Query Title', formData['title']),
                      const SizedBox(height: 20),
                      
                      _buildReviewField('Description', formData['description']),
                      const SizedBox(height: 20),
                      
                      _buildReviewField('Project Type', formData['projectType']),
                      const SizedBox(height: 20),
                      
                      _buildReviewField('Location', formData['location']),
                      const SizedBox(height: 20),
                      
                      _buildReviewField('Project Area (sq m)', formData['projectArea']),
                      const SizedBox(height: 20),

                      if (attachments.isNotEmpty) ...[
                        const Text(
                          'Attachments',
                          style: TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.w600,
                            color: Colors.black,
                          ),
                        ),
                        const SizedBox(height: 8),
                        ...List.generate(attachments.length, (index) {
                          final filePath = attachments[index];
                          final fileName = filePath.split('/').last.split('\\').last;
                          return Container(
                            margin: const EdgeInsets.only(bottom: 8),
                            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                            decoration: BoxDecoration(
                              color: const Color(0xFFF9FAFB),
                              borderRadius: BorderRadius.circular(14),
                              border: Border.all(color: const Color(0xFFE2E8F0)),
                            ),
                            child: Row(
                              children: [
                                const Icon(Icons.description_outlined, color: Color(0xFF0F6B35), size: 22),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Text(
                                    fileName,
                                    maxLines: 1,
                                    overflow: TextOverflow.ellipsis,
                                    style: const TextStyle(fontSize: 14, color: Colors.black),
                                  ),
                                ),
                              ],
                            ),
                          );
                        }),
                        const SizedBox(height: 16),
                      ],
                    ],
                  ),
                ),
              ),

              // Bottom buttons
              Container(
                padding: const EdgeInsets.all(20),
                decoration: const BoxDecoration(
                  color: Colors.white,
                  border: Border(top: BorderSide(color: Color(0xFFE2E8F0))),
                ),
                child: Row(
                  children: [
                    Expanded(
                      flex: 1,
                      child: SizedBox(
                        height: 56,
                        child: OutlinedButton(
                          onPressed: () => context.pop(),
                          style: OutlinedButton.styleFrom(
                            foregroundColor: Colors.black,
                            side: const BorderSide(color: Color(0xFFD1D5DB)),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(28),
                            ),
                          ),
                          child: const Text(
                            'Edit',
                            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      flex: 2,
                      child: BlocBuilder<QueryBloc, QueryState>(
                        builder: (context, state) {
                          return SizedBox(
                            height: 56,
                            child: ElevatedButton(
                              onPressed: state is QueryLoading ? null : () => _submitQuery(context),
                              style: ElevatedButton.styleFrom(
                                backgroundColor: const Color(0xFF0F6B35),
                                foregroundColor: Colors.white,
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(28),
                                ),
                                elevation: 0,
                              ),
                              child: state is QueryLoading
                                  ? const SizedBox(
                                      width: 24,
                                      height: 24,
                                      child: CircularProgressIndicator(
                                        strokeWidth: 2,
                                        color: Colors.white,
                                      ),
                                    )
                                  : const Text(
                                      'Submit',
                                      style: TextStyle(
                                        fontSize: 16,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                            ),
                          );
                        },
                      ),
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

  Widget _buildReviewField(String label, String? value) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(
            fontSize: 14,
            fontWeight: FontWeight.w600,
            color: Colors.black,
          ),
        ),
        const SizedBox(height: 8),
        Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          decoration: BoxDecoration(
            color: const Color(0xFFF9FAFB),
            borderRadius: BorderRadius.circular(14),
            border: Border.all(color: const Color(0xFFE2E8F0)),
          ),
          child: Text(
            (value == null || value.isEmpty) ? 'Not provided' : value,
            style: const TextStyle(
              fontSize: 15,
              color: Colors.black,
              height: 1.4,
            ),
          ),
        ),
      ],
    );
  }
}
