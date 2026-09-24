import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../blocs/query/query_bloc.dart';
import '../../widgets/buttons/app_buttons.dart';

class ReviewQueryScreen extends StatelessWidget {
  final Map<String, dynamic> formData;

  const ReviewQueryScreen({super.key, required this.formData});

  void _submitQuery(BuildContext context) {
    context.read<QueryBloc>().add(
      SubmitQueryEvent(
        title: formData['title'],
        description: formData['description'],
        urgency: 'Normal', // Default as per prompt 3 logic
        categoryId: formData['categoryId'],
        industryName: formData['projectType'], // Mapping projectType as there is no specific field for projectType in SubmitQueryEvent, or pass via sector
        sector: formData['projectType'], 
        state: formData['location'],
        consentNumber: formData['projectArea'], // Mapping projectArea 
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
        appBar: AppBar(
          backgroundColor: Colors.white,
          elevation: 0,
          title: const Text('Review Your Query'),
          leading: IconButton(
            icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
            onPressed: () => context.pop(),
          ),
        ),
        body: SafeArea(
          child: Column(
            children: [
              Expanded(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 24.0,
                    vertical: 20.0,
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Please check the details before submitting',
                        style: TextStyle(fontSize: 14, color: AppColors.textSecondary),
                      ),
                      const SizedBox(height: 24),
                      
                      _buildInfoRow('Category', formData['categoryName']?.toString() ?? 'General'),
                      _buildInfoRow('Title', formData['title']),
                      _buildInfoRow('Description', formData['description']),
                      _buildInfoRow('Project Type', formData['projectType']),
                      _buildInfoRow('Location', formData['location']),
                      _buildInfoRow('Project Area (sqm)', formData['projectArea']),
                      
                      const SizedBox(height: 16),
                      const Text(
                        'Attachments',
                        style: TextStyle(
                          fontSize: 13,
                          fontWeight: FontWeight.w600,
                          color: AppColors.textMuted,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.attach_file, size: 16, color: AppColors.primary),
                          const SizedBox(width: 4),
                          Text(
                            '${attachments.length} files attached',
                            style: const TextStyle(
                              fontSize: 14,
                              fontWeight: FontWeight.bold,
                              color: AppColors.primary,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  border: Border(top: BorderSide(color: AppColors.divider)),
                ),
                child: Row(
                  children: [
                    Expanded(
                      flex: 1,
                      child: AppOutlineButton(
                        text: 'Edit',
                        borderColor: AppColors.border,
                        textColor: AppColors.textPrimary,
                        onPressed: () => context.pop(),
                      ),
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      flex: 2,
                      child: BlocBuilder<QueryBloc, QueryState>(
                        builder: (context, state) {
                          return AppPrimaryButton(
                            text: 'Submit',
                            isLoading: state is QueryLoading,
                            onPressed: () => _submitQuery(context),
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

  Widget _buildInfoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 20.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w600,
              color: AppColors.textMuted,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            value.isNotEmpty ? value : 'N/A',
            style: const TextStyle(
              fontSize: 15,
              fontWeight: FontWeight.bold,
              color: AppColors.textPrimary,
              height: 1.4,
            ),
          ),
        ],
      ),
    );
  }
}
