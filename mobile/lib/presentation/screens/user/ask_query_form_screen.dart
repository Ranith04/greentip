import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/category_model.dart';
import '../../widgets/buttons/app_buttons.dart';
import '../../widgets/inputs/app_text_field.dart';

class AskQueryFormScreen extends StatefulWidget {
  final CategoryModel? initialCategory;

  const AskQueryFormScreen({super.key, this.initialCategory});

  @override
  State<AskQueryFormScreen> createState() => _AskQueryFormScreenState();
}

class _AskQueryFormScreenState extends State<AskQueryFormScreen> {
  final _formKey = GlobalKey<FormState>();
  
  final _titleController = TextEditingController();
  final _descriptionController = TextEditingController();
  final _locationController = TextEditingController();
  final _areaController = TextEditingController();
  
  String _selectedProjectType = 'Residential';
  final List<String> _projectTypes = [
    'Residential',
    'Commercial',
    'Industrial',
    'Infrastructure',
    'Mining',
    'Other',
  ];

  final List<PlatformFile> _selectedFiles = [];

  @override
  void dispose() {
    _titleController.dispose();
    _descriptionController.dispose();
    _locationController.dispose();
    _areaController.dispose();
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

  void _onNext() {
    if (_formKey.currentState?.validate() ?? false) {
      final formData = {
        'categoryId': widget.initialCategory?.id,
        'categoryName': widget.initialCategory?.name,
        'title': _titleController.text.trim(),
        'description': _descriptionController.text.trim(),
        'projectType': _selectedProjectType,
        'location': _locationController.text.trim(),
        'projectArea': _areaController.text.trim(),
        'filePaths': _selectedFiles.map((f) => f.path).whereType<String>().toList(),
      };
      context.push('/user/review-query', extra: formData);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('Ask Your Query'),
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
                child: Form(
                  key: _formKey,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (widget.initialCategory != null) ...[
                        Text(
                          widget.initialCategory!.name,
                          style: const TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                            color: AppColors.primary,
                          ),
                        ),
                        const SizedBox(height: 24),
                      ],
                      
                      AppTextField(
                        controller: _titleController,
                        label: 'Query Title *',
                        hint: 'e.g. Do I require EC for my project?',
                        validator: (v) => v == null || v.isEmpty ? 'Title is required' : null,
                      ),
                      const SizedBox(height: 18),
                      
                      AppTextField(
                        controller: _descriptionController,
                        label: 'Describe your question *',
                        hint: 'I have a land where BUA is 21000 sqm. Do I require Environmental Clearance...',
                        maxLines: 4,
                        validator: (v) => v == null || v.isEmpty ? 'Description is required' : null,
                      ),
                      const SizedBox(height: 18),
                      
                      const Text(
                        'Project Type *',
                        style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
                      ),
                      const SizedBox(height: 6),
                      DropdownButtonFormField<String>(
                        initialValue: _selectedProjectType,
                        items: _projectTypes
                            .map((s) => DropdownMenuItem(
                                  value: s,
                                  child: Text(s, style: const TextStyle(fontSize: 14)),
                                ))
                            .toList(),
                        onChanged: (val) => setState(() => _selectedProjectType = val ?? _selectedProjectType),
                        decoration: InputDecoration(
                          filled: true,
                          fillColor: AppColors.background,
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                            borderSide: const BorderSide(color: AppColors.border),
                          ),
                          enabledBorder: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12),
                            borderSide: const BorderSide(color: AppColors.border),
                          ),
                        ),
                      ),
                      const SizedBox(height: 18),
                      
                      AppTextField(
                        controller: _locationController,
                        label: 'Location *',
                        hint: 'e.g. Hyderabad, Telangana',
                        validator: (v) => v == null || v.isEmpty ? 'Location is required' : null,
                      ),
                      const SizedBox(height: 18),
                      
                      AppTextField(
                        controller: _areaController,
                        label: 'Project Area (sq m) *',
                        hint: 'e.g. 21000',
                        keyboardType: TextInputType.number,
                        validator: (v) => v == null || v.isEmpty ? 'Project Area is required' : null,
                      ),
                      const SizedBox(height: 24),
                      
                      const Text(
                        'Attachments (Optional)',
                        style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
                      ),
                      const SizedBox(height: 8),
                      AppOutlineButton(
                        text: '+ Add Documents',
                        borderColor: AppColors.primary,
                        textColor: AppColors.primary,
                        onPressed: _pickFiles,
                      ),
                      const SizedBox(height: 16),
                      
                      if (_selectedFiles.isNotEmpty)
                        ListView.separated(
                          shrinkWrap: true,
                          physics: const NeverScrollableScrollPhysics(),
                          itemCount: _selectedFiles.length,
                          separatorBuilder: (_, _) => const SizedBox(height: 8),
                          itemBuilder: (context, index) {
                            final file = _selectedFiles[index];
                            return Container(
                              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                              decoration: BoxDecoration(
                                color: AppColors.background,
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(color: AppColors.divider),
                              ),
                              child: Row(
                                children: [
                                  const Icon(Icons.description_outlined, color: AppColors.primary, size: 24),
                                  const SizedBox(width: 12),
                                  Expanded(
                                    child: Text(
                                      file.name,
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                      style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
                                    ),
                                  ),
                                  IconButton(
                                    icon: const Icon(Icons.close_rounded, size: 18, color: Colors.grey),
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
                  ),
                ),
              ),
            ),
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                border: Border(top: BorderSide(color: AppColors.divider)),
              ),
              child: AppPrimaryButton(
                text: 'Next',
                onPressed: _onNext,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
