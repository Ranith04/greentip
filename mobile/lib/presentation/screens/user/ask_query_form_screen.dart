import 'dart:io';
import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/category_model.dart';
import '../../blocs/query/query_bloc.dart';
import '../../widgets/buttons/app_buttons.dart';
import '../../widgets/inputs/app_text_field.dart';

class AskQueryFormScreen extends StatefulWidget {
  final CategoryModel? initialCategory;

  const AskQueryFormScreen({super.key, this.initialCategory});

  @override
  State<AskQueryFormScreen> createState() => _AskQueryFormScreenState();
}

class _AskQueryFormScreenState extends State<AskQueryFormScreen> {
  int _currentStep = 0;

  // Form Step 1: Industry & Plant
  final _plantNameController = TextEditingController();
  final _consentNumberController = TextEditingController();
  String _selectedSector = 'Chemicals & Petrochemicals';
  String _selectedState = 'Maharashtra (MPCB)';

  // Form Step 2: Query Title & Description
  final _titleController = TextEditingController();
  final _descriptionController = TextEditingController();

  // Form Step 3: Files
  final List<PlatformFile> _selectedFiles = [];

  // Form Step 4: Urgency
  String _urgency = 'Normal';

  final List<String> _sectors = [
    'Chemicals & Petrochemicals',
    'Textiles & Dyeing',
    'Pharmaceuticals & APIs',
    'Automotive & Engineering',
    'Food Processing & Dairy',
    'Mining & Metallurgy',
    'Pulp & Paper',
    'Thermal Power Plants',
    'General Manufacturing',
  ];

  final List<String> _states = [
    'Maharashtra (MPCB)',
    'Gujarat (GPCB)',
    'Tamil Nadu (TNPCB)',
    'Karnataka (KSPCB)',
    'Telangana (TSPCB)',
    'Uttar Pradesh (UPPCB)',
    'Rajasthan (RSPCB)',
    'Central Pollution Control Board (CPCB)',
  ];

  @override
  void dispose() {
    _plantNameController.dispose();
    _consentNumberController.dispose();
    _titleController.dispose();
    _descriptionController.dispose();
    super.dispose();
  }

  Future<void> _pickFiles() async {
    final result = await FilePickerPlatform.instance.pickFiles();
    if (result.isNotEmpty) {
      setState(() {
        _selectedFiles.addAll(result);
      });
    }
  }

  void _onSubmit() {
    final paths = _selectedFiles
        .map((f) => f.path)
        .whereType<String>()
        .toList();

    context.read<QueryBloc>().add(
      SubmitQueryEvent(
        title: _titleController.text.trim(),
        description: _descriptionController.text.trim(),
        urgency: _urgency,
        categoryId: widget.initialCategory?.id,
        industryName: _plantNameController.text.trim(),
        sector: _selectedSector,
        state: _selectedState,
        consentNumber: _consentNumberController.text.trim(),
        filePaths: paths.isNotEmpty ? paths : null,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
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
          title: Text('Step ${_currentStep + 1} of 4'),
          leading: IconButton(
            icon: const Icon(Icons.arrow_back_ios_new_rounded, size: 20),
            onPressed: () {
              if (_currentStep > 0) {
                setState(() {
                  _currentStep--;
                });
              } else {
                context.pop();
              }
            },
          ),
        ),
        body: SafeArea(
          child: Column(
            children: [
              // Top Progress Line
              LinearProgressIndicator(
                value: (_currentStep + 1) / 4,
                backgroundColor: Colors.grey.shade200,
                valueColor: const AlwaysStoppedAnimation<Color>(
                  AppColors.primary,
                ),
                minHeight: 4,
              ),

              // Step Content
              Expanded(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 24.0,
                    vertical: 20.0,
                  ),
                  child: _buildCurrentStep(),
                ),
              ),

              // Bottom Button Bar
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  border: Border(top: BorderSide(color: AppColors.divider)),
                ),
                child: Row(
                  children: [
                    if (_currentStep > 0) ...[
                      Expanded(
                        flex: 1,
                        child: AppOutlineButton(
                          text: 'Back',
                          borderColor: AppColors.border,
                          textColor: AppColors.textPrimary,
                          onPressed: () {
                            setState(() {
                              _currentStep--;
                            });
                          },
                        ),
                      ),
                      const SizedBox(width: 14),
                    ],
                    Expanded(
                      flex: 2,
                      child: BlocBuilder<QueryBloc, QueryState>(
                        builder: (context, state) {
                          if (_currentStep == 3) {
                            return AppPrimaryButton(
                              text: 'Submit Query',
                              isLoading: state is QueryLoading,
                              onPressed: _onSubmit,
                            );
                          }
                          return AppPrimaryButton(
                            text: 'Continue',
                            onPressed: _validateAndProceed,
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

  void _validateAndProceed() {
    if (_currentStep == 0) {
      if (_plantNameController.text.trim().isEmpty) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Please enter plant / manufacturing unit name'),
          ),
        );
        return;
      }
    } else if (_currentStep == 1) {
      if (_titleController.text.trim().isEmpty) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Please enter query title')),
        );
        return;
      }
      if (_descriptionController.text.trim().length < 20) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Please provide more description (min 20 chars)'),
          ),
        );
        return;
      }
    }
    setState(() {
      _currentStep++;
    });
  }

  Widget _buildCurrentStep() {
    switch (_currentStep) {
      case 0:
        return _buildStep1PlantDetails();
      case 1:
        return _buildStep2TitleDescription();
      case 2:
        return _buildStep3FileUpload();
      case 3:
        return _buildStep4UrgencyReview();
      default:
        return const SizedBox.shrink();
    }
  }

  Widget _buildStep1PlantDetails() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        if (widget.initialCategory != null) ...[
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            decoration: BoxDecoration(
              color: AppColors.primaryContainer,
              borderRadius: BorderRadius.circular(8),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                const Icon(
                  Icons.category_rounded,
                  size: 16,
                  color: AppColors.primaryDark,
                ),
                const SizedBox(width: 8),
                Text(
                  widget.initialCategory!.name,
                  style: const TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.bold,
                    color: AppColors.primaryDark,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
        ],
        const Text(
          'Industry & Facility Details',
          style: TextStyle(
            fontSize: 22,
            fontWeight: FontWeight.bold,
            color: AppColors.textPrimary,
          ),
        ),
        const SizedBox(height: 6),
        const Text(
          'Specify your manufacturing site and pollution control jurisdiction.',
          style: TextStyle(fontSize: 14, color: AppColors.textSecondary),
        ),
        const SizedBox(height: 24),
        AppTextField(
          controller: _plantNameController,
          label: 'Manufacturing Unit / Plant Name *',
          hint: 'e.g. Unit 3 - Kurkumbh MIDC Plant',
          prefixIcon: Icons.factory_outlined,
        ),
        const SizedBox(height: 18),
        const Text(
          'Industrial Sector *',
          style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
        ),
        const SizedBox(height: 6),
        DropdownButtonFormField<String>(
          initialValue: _selectedSector,
          items: _sectors
              .map(
                (s) => DropdownMenuItem(
                  value: s,
                  child: Text(s, style: const TextStyle(fontSize: 14)),
                ),
              )
              .toList(),
          onChanged: (val) =>
              setState(() => _selectedSector = val ?? _selectedSector),
          decoration: const InputDecoration(
            prefixIcon: Icon(
              Icons.domain_outlined,
              size: 20,
              color: AppColors.textSecondary,
            ),
          ),
        ),
        const SizedBox(height: 18),
        const Text(
          'Pollution Control Board / State *',
          style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
        ),
        const SizedBox(height: 6),
        DropdownButtonFormField<String>(
          initialValue: _selectedState,
          items: _states
              .map(
                (s) => DropdownMenuItem(
                  value: s,
                  child: Text(s, style: const TextStyle(fontSize: 14)),
                ),
              )
              .toList(),
          onChanged: (val) =>
              setState(() => _selectedState = val ?? _selectedState),
          decoration: const InputDecoration(
            prefixIcon: Icon(
              Icons.location_on_outlined,
              size: 20,
              color: AppColors.textSecondary,
            ),
          ),
        ),
        const SizedBox(height: 18),
        AppTextField(
          controller: _consentNumberController,
          label: 'Consent to Operate (CTO) / CTE Number',
          hint: 'e.g. MPCB/RO/PUN-12093/2023',
          prefixIcon: Icons.description_outlined,
        ),
      ],
    );
  }

  Widget _buildStep2TitleDescription() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Query & Legal Context',
          style: TextStyle(
            fontSize: 22,
            fontWeight: FontWeight.bold,
            color: AppColors.textPrimary,
          ),
        ),
        const SizedBox(height: 6),
        const Text(
          'Describe the technical or regulatory guidance you require from our experts.',
          style: TextStyle(fontSize: 14, color: AppColors.textSecondary),
        ),
        const SizedBox(height: 24),
        AppTextField(
          controller: _titleController,
          label: 'Query Subject / Title *',
          hint: 'e.g. ZLD implementation timeline for discharge into CETP',
          prefixIcon: Icons.title_rounded,
        ),
        const SizedBox(height: 18),
        AppTextField(
          controller: _descriptionController,
          label: 'Detailed Compliance Inquiry *',
          hint:
              'Explain your current plant process, effluent parameters, legal notice dates, or specific SPCB condition you need guidance on...',
          maxLines: 6,
        ),
      ],
    );
  }

  Widget _buildStep3FileUpload() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Supporting Documents',
          style: TextStyle(
            fontSize: 22,
            fontWeight: FontWeight.bold,
            color: AppColors.textPrimary,
          ),
        ),
        const SizedBox(height: 6),
        const Text(
          'Upload SPCB show-cause notices, lab analysis reports, or consent letters.',
          style: TextStyle(fontSize: 14, color: AppColors.textSecondary),
        ),
        const SizedBox(height: 24),

        // Dropzone box
        InkWell(
          onTap: _pickFiles,
          borderRadius: BorderRadius.circular(16),
          child: Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(vertical: 36, horizontal: 20),
            decoration: BoxDecoration(
              color: AppColors.primaryContainer.withValues(alpha: 0.3),
              borderRadius: BorderRadius.circular(16),
              border: Border.all(
                color: AppColors.primary.withValues(alpha: 0.5),
                style: BorderStyle.solid,
                width: 1.5,
              ),
            ),
            child: Column(
              children: [
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: const BoxDecoration(
                    color: Colors.white,
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.cloud_upload_outlined,
                    size: 32,
                    color: AppColors.primary,
                  ),
                ),
                const SizedBox(height: 12),
                const Text(
                  'Tap to Browse & Upload Documents',
                  style: TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.bold,
                    color: AppColors.primaryDark,
                  ),
                ),
                const SizedBox(height: 4),
                const Text(
                  'Supported: PDF, JPG, PNG, DOCX (Max 20MB per file)',
                  style: TextStyle(fontSize: 12, color: AppColors.textMuted),
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 24),

        // Selected files list
        if (_selectedFiles.isNotEmpty) ...[
          Text(
            'Attached Files (${_selectedFiles.length})',
            style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 10),
          ListView.separated(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: _selectedFiles.length,
            separatorBuilder: (_, _) => const SizedBox(height: 8),
            itemBuilder: (context, index) {
              final file = _selectedFiles[index];
              final sizeKb = file.path != null
                  ? (File(file.path!).lengthSync() / 1024).toStringAsFixed(1)
                  : '0.0';

              return Container(
                padding: const EdgeInsets.symmetric(
                  horizontal: 14,
                  vertical: 10,
                ),
                decoration: BoxDecoration(
                  color: AppColors.background,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: AppColors.divider),
                ),
                child: Row(
                  children: [
                    const Icon(
                      Icons.picture_as_pdf_outlined,
                      color: AppColors.error,
                      size: 24,
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            file.name,
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: const TextStyle(
                              fontSize: 13,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          Text(
                            '$sizeKb KB',
                            style: const TextStyle(
                              fontSize: 11,
                              color: AppColors.textMuted,
                            ),
                          ),
                        ],
                      ),
                    ),
                    IconButton(
                      icon: const Icon(
                        Icons.close_rounded,
                        size: 18,
                        color: Colors.grey,
                      ),
                      onPressed: () {
                        setState(() {
                          _selectedFiles.removeAt(index);
                        });
                      },
                    ),
                  ],
                ),
              );
            },
          ),
        ],
      ],
    );
  }

  Widget _buildStep4UrgencyReview() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Review & Statutory Priority',
          style: TextStyle(
            fontSize: 22,
            fontWeight: FontWeight.bold,
            color: AppColors.textPrimary,
          ),
        ),
        const SizedBox(height: 6),
        const Text(
          'Select statutory urgency level before final submission.',
          style: TextStyle(fontSize: 14, color: AppColors.textSecondary),
        ),
        const SizedBox(height: 24),

        // Urgency Radio Selection
        InkWell(
          onTap: () => setState(() => _urgency = 'Normal'),
          borderRadius: BorderRadius.circular(14),
          child: Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: _urgency == 'Normal'
                  ? AppColors.primaryContainer.withValues(alpha: 0.4)
                  : Colors.white,
              borderRadius: BorderRadius.circular(14),
              border: Border.all(
                color: _urgency == 'Normal'
                    ? AppColors.primary
                    : AppColors.divider,
                width: _urgency == 'Normal' ? 1.8 : 1,
              ),
            ),
            child: Row(
              children: [
                const Icon(
                  Icons.schedule_rounded,
                  color: AppColors.primary,
                  size: 24,
                ),
                const SizedBox(width: 14),
                const Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Standard Assessment (Normal)',
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          fontSize: 14,
                        ),
                      ),
                      SizedBox(height: 2),
                      Text(
                        'Response turnaround within 48-72 hours SLA',
                        style: TextStyle(
                          fontSize: 12,
                          color: AppColors.textSecondary,
                        ),
                      ),
                    ],
                  ),
                ),
                Icon(
                  _urgency == 'Normal'
                      ? Icons.radio_button_checked
                      : Icons.radio_button_off,
                  color: _urgency == 'Normal' ? AppColors.primary : Colors.grey,
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 12),

        InkWell(
          onTap: () => setState(() => _urgency = 'Urgent'),
          borderRadius: BorderRadius.circular(14),
          child: Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: _urgency == 'Urgent'
                  ? AppColors.errorLight.withValues(alpha: 0.4)
                  : Colors.white,
              borderRadius: BorderRadius.circular(14),
              border: Border.all(
                color: _urgency == 'Urgent'
                    ? AppColors.error
                    : AppColors.divider,
                width: _urgency == 'Urgent' ? 1.8 : 1,
              ),
            ),
            child: Row(
              children: [
                const Icon(
                  Icons.notification_important_rounded,
                  color: AppColors.error,
                  size: 24,
                ),
                const SizedBox(width: 14),
                const Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Urgent Statutory Notice (< 24 Hours)',
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          fontSize: 14,
                          color: AppColors.error,
                        ),
                      ),
                      SizedBox(height: 2),
                      Text(
                        'For court/tribunal deadlines, closure notices, or immediate SPCB compliance hearings.',
                        style: TextStyle(
                          fontSize: 12,
                          color: AppColors.textSecondary,
                        ),
                      ),
                    ],
                  ),
                ),
                Icon(
                  _urgency == 'Urgent'
                      ? Icons.radio_button_checked
                      : Icons.radio_button_off,
                  color: _urgency == 'Urgent' ? AppColors.error : Colors.grey,
                ),
              ],
            ),
          ),
        ),
        const SizedBox(height: 24),

        // Summary Card
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: AppColors.background,
            borderRadius: BorderRadius.circular(14),
            border: Border.all(color: AppColors.divider),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Submission Summary',
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
              ),
              const Divider(height: 20),
              _summaryRow('Plant Unit', _plantNameController.text),
              _summaryRow('Sector', _selectedSector),
              _summaryRow('PCB Jurisdiction', _selectedState),
              _summaryRow('Title', _titleController.text),
              _summaryRow(
                'Attachments',
                '${_selectedFiles.length} file(s) attached',
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _summaryRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6.0),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 110,
            child: Text(
              label,
              style: const TextStyle(fontSize: 12, color: AppColors.textMuted),
            ),
          ),
          Expanded(
            child: Text(
              value.isNotEmpty ? value : 'N/A',
              style: const TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.w600,
                color: AppColors.textPrimary,
              ),
            ),
          ),
        ],
      ),
    );
  }
}
