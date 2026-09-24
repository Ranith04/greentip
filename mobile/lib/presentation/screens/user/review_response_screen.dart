import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/query_model.dart';
import '../../blocs/query/query_bloc.dart';
import '../../widgets/buttons/app_buttons.dart';
import '../../widgets/feedback/star_rating_bar.dart';

class ReviewResponseScreen extends StatefulWidget {
  final QueryModel query;

  const ReviewResponseScreen({super.key, required this.query});

  @override
  State<ReviewResponseScreen> createState() => _ReviewResponseScreenState();
}

class _ReviewResponseScreenState extends State<ReviewResponseScreen> {
  int _rating = 5;
  String? _selectedTag = 'Actionable Guidance';
  final TextEditingController _commentController = TextEditingController();

  final List<String> _feedbackTags = [
    'Actionable Guidance',
    'Timely Response',
    'Thorough Analysis',
    'Clear PCB Reference',
    'Statutory Precision',
    'Helpful Next Steps',
  ];

  String _getRatingText(int rating) {
    switch (rating) {
      case 1:
        return 'Needs Improvement';
      case 2:
        return 'Fair Assessment';
      case 3:
        return 'Good Guidance';
      case 4:
        return 'Very Good & Comprehensive';
      case 5:
        return 'Outstanding & Highly Actionable';
      default:
        return '';
    }
  }

  @override
  void dispose() {
    _commentController.dispose();
    super.dispose();
  }

  void _submitReview() {
    context.read<QueryBloc>().add(
      SubmitReviewEvent(
        queryId: widget.query.id,
        rating: _rating,
        feedbackTag: _selectedTag,
        comments: _commentController.text.trim(),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocListener<QueryBloc, QueryState>(
      listener: (context, state) {
        if (state is QueryReviewSuccess) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Thank you! Your feedback has been recorded.'),
              backgroundColor: AppColors.success,
            ),
          );
          context.pop();
        } else if (state is QueryError) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(state.message),
              backgroundColor: AppColors.error,
            ),
          );
        }
      },
      child: Scaffold(
        backgroundColor: Colors.white,
        appBar: AppBar(
          backgroundColor: Colors.white,
          elevation: 0,
          title: const Text('Rate Resolution'),
          leading: IconButton(
            icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
            onPressed: () => context.pop(),
          ),
        ),
        body: SafeArea(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(
              horizontal: 24.0,
              vertical: 16.0,
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                const SizedBox(height: 10),
                CircleAvatar(
                  radius: 36,
                  backgroundColor: AppColors.primaryContainer,
                  child: const Icon(
                    Icons.rate_review_rounded,
                    size: 40,
                    color: AppColors.primary,
                  ),
                ),
                const SizedBox(height: 20),
                const Text(
                  'How was the expert guidance?',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 22,
                    fontWeight: FontWeight.bold,
                    color: AppColors.textPrimary,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  'Your rating helps maintain high standards across the GreenTIP expert panel.',
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.textSecondary,
                  ),
                ),
                const SizedBox(height: 32),

                // Stars
                StarRatingBar(
                  rating: _rating,
                  size: 42,
                  onRatingChanged: (r) {
                    setState(() {
                      _rating = r;
                    });
                  },
                ),
                const SizedBox(height: 12),
                Text(
                  _getRatingText(_rating),
                  style: const TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.bold,
                    color: AppColors.primaryDark,
                  ),
                ),
                const SizedBox(height: 32),

                // Tags
                const Align(
                  alignment: Alignment.centerLeft,
                  child: Text(
                    'What went well? (Optional)',
                    style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold),
                  ),
                ),
                const SizedBox(height: 12),
                Wrap(
                  spacing: 8,
                  runSpacing: 8,
                  children: _feedbackTags.map((tag) {
                    final isSelected = _selectedTag == tag;
                    return FilterChip(
                      label: Text(tag),
                      selected: isSelected,
                      onSelected: (val) {
                        setState(() {
                          _selectedTag = val ? tag : null;
                        });
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
                        color: isSelected
                            ? AppColors.primary
                            : AppColors.divider,
                      ),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(20),
                      ),
                    );
                  }).toList(),
                ),
                const SizedBox(height: 28),

                // Comment input
                const Align(
                  alignment: Alignment.centerLeft,
                  child: Text(
                    'Additional Comments',
                    style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold),
                  ),
                ),
                const SizedBox(height: 8),
                TextField(
                  controller: _commentController,
                  maxLines: 4,
                  decoration: InputDecoration(
                    hintText:
                        'Share any specifics about the expert response, technical depth, or recommendations...',
                    filled: true,
                    fillColor: AppColors.background,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: const BorderSide(color: AppColors.divider),
                    ),
                  ),
                ),
                const SizedBox(height: 36),

                // Submit
                BlocBuilder<QueryBloc, QueryState>(
                  builder: (context, state) {
                    return AppPrimaryButton(
                      text: 'Submit Review',
                      isLoading: state is QueryLoading,
                      onPressed: _submitReview,
                    );
                  },
                ),
                const SizedBox(height: 16),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
