import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../blocs/bulk_email/bulk_email_bloc.dart';
import '../../widgets/buttons/app_buttons.dart';
import '../../widgets/inputs/app_text_field.dart';

class ComposeEmailScreen extends StatefulWidget {
  const ComposeEmailScreen({super.key});

  @override
  State<ComposeEmailScreen> createState() => _ComposeEmailScreenState();
}

class _ComposeEmailScreenState extends State<ComposeEmailScreen> {
  final _formKey = GlobalKey<FormState>();
  final _subjectController = TextEditingController();
  final _messageController = TextEditingController();
  String _targetAudience = 'All Registered Facilities';
  final List<String> _attachedFiles = [];

  final List<Map<String, String>> _audienceOptions = [
    {'name': 'All Registered Facilities', 'count': '142 units'},
    {'name': 'Units with Pending Inquiries', 'count': '34 units'},
    {'name': 'Chemical & Petrochemical Sector', 'count': '58 units'},
    {'name': 'Textile & Processing Units', 'count': '41 units'},
    {'name': 'Facilities with Overdue Returns', 'count': '19 units'},
  ];

  @override
  void dispose() {
    _subjectController.dispose();
    _messageController.dispose();
    super.dispose();
  }

  void _showPreviewDialog() {
    if (!(_formKey.currentState?.validate() ?? false)) return;

    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: const Row(
          children: [
            Icon(Icons.preview_rounded, color: AppColors.primary),
            SizedBox(width: 8),
            Text(
              'Broadcast Preview',
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
          ],
        ),
        content: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 4,
                ),
                decoration: BoxDecoration(
                  color: AppColors.primaryContainer,
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Text(
                  'Audience: $_targetAudience',
                  style: const TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                    color: AppColors.primaryDark,
                  ),
                ),
              ),
              const SizedBox(height: 14),
              const Text(
                'Subject:',
                style: TextStyle(fontSize: 12, color: AppColors.textMuted),
              ),
              Text(
                _subjectController.text,
                style: const TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const Divider(height: 20),
              const Text(
                'Message Body:',
                style: TextStyle(fontSize: 12, color: AppColors.textMuted),
              ),
              const SizedBox(height: 4),
              Text(
                _messageController.text,
                style: const TextStyle(
                  fontSize: 13,
                  color: AppColors.textPrimary,
                  height: 1.4,
                ),
              ),
              if (_attachedFiles.isNotEmpty) ...[
                const Divider(height: 20),
                const Text(
                  'Attachments:',
                  style: TextStyle(fontSize: 12, color: AppColors.textMuted),
                ),
                const SizedBox(height: 4),
                Wrap(
                  spacing: 4,
                  children: _attachedFiles.map((f) => Chip(
                    label: Text(f, style: const TextStyle(fontSize: 10)),
                    padding: EdgeInsets.zero,
                    visualDensity: VisualDensity.compact,
                  )).toList(),
                ),
              ],
            ],
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Edit Draft'),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary),
            onPressed: () {
              Navigator.pop(ctx);
              context.read<BulkEmailBloc>().add(
                SendBulkEmailEvent(
                  subject: _subjectController.text.trim(),
                  message: _messageController.text.trim(),
                  targetAudience: _targetAudience,
                ),
              );
            },
            child: const Text(
              'Send Broadcast Now',
              style: TextStyle(
                color: Colors.white,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocListener<BulkEmailBloc, BulkEmailState>(
      listener: (context, state) {
        if (state is BulkEmailSentSuccess) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(state.message),
              backgroundColor: AppColors.success,
            ),
          );
          _subjectController.clear();
          _messageController.clear();
        } else if (state is BulkEmailError) {
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
          title: const Text('Statutory Bulk Communication'),
          actions: [
            TextButton.icon(
              icon: const Icon(
                Icons.history_rounded,
                size: 18,
                color: AppColors.primary,
              ),
              label: const Text(
                'Logs',
                style: TextStyle(
                  color: AppColors.primary,
                  fontWeight: FontWeight.bold,
                ),
              ),
              onPressed: () => context.push('/admin/bulk-email-logs'),
            ),
            const SizedBox(width: 8),
          ],
        ),
        body: SafeArea(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(
              horizontal: 24.0,
              vertical: 16.0,
            ),
            child: Form(
              key: _formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Compose Official Broadcast',
                    style: TextStyle(
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                      color: AppColors.textPrimary,
                    ),
                  ),
                  const SizedBox(height: 6),
                  const Text(
                    'Send compliance directives, statutory circulars, or deadline reminders to industrial units.',
                    style: TextStyle(
                      fontSize: 14,
                      color: AppColors.textSecondary,
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Target Audience Dropdown
                  const Text(
                    'Target Audience / Sector *',
                    style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
                  ),
                  const SizedBox(height: 6),
                  DropdownButtonFormField<String>(
                    initialValue: _targetAudience,
                    items: _audienceOptions.map((opt) {
                      return DropdownMenuItem(
                        value: opt['name'],
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text(
                              opt['name']!,
                              style: const TextStyle(fontSize: 13),
                            ),
                            const SizedBox(width: 8),
                            Text(
                              '(${opt['count']})',
                              style: const TextStyle(
                                fontSize: 11,
                                color: AppColors.textMuted,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
                        ),
                      );
                    }).toList(),
                    onChanged: (val) {
                      if (val != null) setState(() => _targetAudience = val);
                    },
                    decoration: InputDecoration(
                      prefixIcon: const Icon(
                        Icons.groups_outlined,
                        size: 20,
                        color: AppColors.textSecondary,
                      ),
                    ),
                  ),
                  const SizedBox(height: 18),

                  AppTextField(
                    controller: _subjectController,
                    label: 'Email Subject *',
                    hint:
                        'e.g. Urgent SPCB Notice: Annual Environmental Return Submission',
                    prefixIcon: Icons.subject_rounded,
                    validator: (v) => v?.isEmpty ?? true
                        ? 'Enter broadcast subject line'
                        : null,
                  ),
                  const SizedBox(height: 18),

                  AppTextField(
                    controller: _messageController,
                    label: 'Official Message Body *',
                    hint:
                        'Enter circular text, statutory reference, deadlines, or compliance instructions...',
                    maxLines: 8,
                    validator: (v) => (v?.length ?? 0) < 15
                        ? 'Message body too short (min 15 chars)'
                        : null,
                  ),
                  const SizedBox(height: 24),

                  const Text(
                    'Attachments',
                    style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
                  ),
                  const SizedBox(height: 8),
                  if (_attachedFiles.isNotEmpty)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 12),
                      child: Wrap(
                        spacing: 8,
                        runSpacing: 8,
                        children: _attachedFiles.map((file) {
                          return Chip(
                            label: Text(
                              file,
                              style: const TextStyle(fontSize: 12),
                            ),
                            onDeleted: () {
                              setState(() {
                                _attachedFiles.remove(file);
                              });
                            },
                            backgroundColor: AppColors.surface,
                            deleteIconColor: AppColors.error,
                            side: const BorderSide(color: AppColors.divider),
                          );
                        }).toList(),
                      ),
                    ),
                  SizedBox(
                    width: double.infinity,
                    child: OutlinedButton.icon(
                      onPressed: () {
                        // Mock adding an attachment
                        setState(() {
                          _attachedFiles.add('circular_document_${_attachedFiles.length + 1}.pdf');
                        });
                      },
                      style: OutlinedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        side: const BorderSide(color: AppColors.primary),
                      ),
                      icon: const Icon(
                        Icons.attach_file,
                        color: AppColors.primary,
                        size: 20,
                      ),
                      label: const Text(
                        'Upload Document',
                        style: TextStyle(
                          color: AppColors.primary,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 28),

                  BlocBuilder<BulkEmailBloc, BulkEmailState>(
                    builder: (context, state) {
                      return AppPrimaryButton(
                        text: 'Preview & Dispatch Broadcast',
                        icon: Icons.send_rounded,
                        isLoading: state is BulkEmailLoading,
                        onPressed: _showPreviewDialog,
                      );
                    },
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
