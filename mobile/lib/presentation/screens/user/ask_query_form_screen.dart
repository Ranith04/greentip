import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/category_model.dart';

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
      body: SafeArea(
        child: Column(
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
                    'Ask Your Query',
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      color: Colors.black,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),

            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(horizontal: 20),
                child: Form(
                  key: _formKey,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Category badge
                      if (widget.initialCategory != null) ...[
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                          decoration: BoxDecoration(
                            color: const Color(0xFFE8F5E9),
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: Text(
                            widget.initialCategory!.name,
                            style: const TextStyle(
                              fontSize: 13,
                              fontWeight: FontWeight.w600,
                              color: Color(0xFF0F6B35),
                            ),
                          ),
                        ),
                        const SizedBox(height: 24),
                      ],

                      _buildLabel('Query Title'),
                      const SizedBox(height: 8),
                      _buildTextField(
                        controller: _titleController,
                        hint: 'e.g. Do I require EC for my project?',
                        validator: (v) => v == null || v.isEmpty ? 'Title is required' : null,
                      ),
                      const SizedBox(height: 20),

                      _buildLabel('Describe your question'),
                      const SizedBox(height: 8),
                      _buildTextField(
                        controller: _descriptionController,
                        hint: 'Describe your query in detail...',
                        maxLines: 4,
                        validator: (v) => v == null || v.isEmpty ? 'Description is required' : null,
                      ),
                      const SizedBox(height: 20),

                      _buildLabel('Project Type'),
                      const SizedBox(height: 8),
                      Container(
                        decoration: BoxDecoration(
                          color: const Color(0xFFF9FAFB),
                          borderRadius: BorderRadius.circular(14),
                          border: Border.all(color: const Color(0xFFE2E8F0)),
                        ),
                        padding: const EdgeInsets.symmetric(horizontal: 14),
                        child: DropdownButtonFormField<String>(
                          value: _selectedProjectType,
                          items: _projectTypes
                              .map((s) => DropdownMenuItem(
                                    value: s,
                                    child: Text(s, style: const TextStyle(fontSize: 14)),
                                  ))
                              .toList(),
                          onChanged: (val) => setState(() => _selectedProjectType = val ?? _selectedProjectType),
                          decoration: const InputDecoration(
                            border: InputBorder.none,
                          ),
                          dropdownColor: Colors.white,
                        ),
                      ),
                      const SizedBox(height: 20),

                      _buildLabel('Location'),
                      const SizedBox(height: 8),
                      _buildTextField(
                        controller: _locationController,
                        hint: 'e.g. Hyderabad, Telangana',
                        validator: (v) => v == null || v.isEmpty ? 'Location is required' : null,
                      ),
                      const SizedBox(height: 20),

                      _buildLabel('Project Area (sq m)'),
                      const SizedBox(height: 8),
                      _buildTextField(
                        controller: _areaController,
                        hint: 'e.g. 21000',
                        keyboardType: TextInputType.number,
                        validator: (v) => v == null || v.isEmpty ? 'Project area is required' : null,
                      ),
                      const SizedBox(height: 24),

                      // Attachments
                      _buildLabel('Attachments (Optional)'),
                      const SizedBox(height: 8),
                      GestureDetector(
                        onTap: _pickFiles,
                        child: Container(
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(14),
                            border: Border.all(
                              color: const Color(0xFFD1D5DB),
                              style: BorderStyle.solid,
                            ),
                          ),
                          child: const Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Icon(Icons.add, color: Color(0xFF6B7280), size: 20),
                              SizedBox(width: 8),
                              Text(
                                'Add Documents',
                                style: TextStyle(
                                  fontSize: 14,
                                  fontWeight: FontWeight.w600,
                                  color: Color(0xFF6B7280),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 12),

                      if (_selectedFiles.isNotEmpty)
                        ...List.generate(_selectedFiles.length, (index) {
                          final file = _selectedFiles[index];
                          return Container(
                            margin: const EdgeInsets.only(bottom: 8),
                            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                            decoration: BoxDecoration(
                              color: const Color(0xFFF9FAFB),
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: const Color(0xFFE2E8F0)),
                            ),
                            child: Row(
                              children: [
                                const Icon(Icons.description_outlined, color: Color(0xFF0F6B35), size: 22),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Text(
                                    file.name,
                                    maxLines: 1,
                                    overflow: TextOverflow.ellipsis,
                                    style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
                                  ),
                                ),
                                GestureDetector(
                                  onTap: () => setState(() => _selectedFiles.removeAt(index)),
                                  child: const Icon(Icons.close_rounded, size: 18, color: Color(0xFF9CA3AF)),
                                ),
                              ],
                            ),
                          );
                        }),
                      const SizedBox(height: 16),
                    ],
                  ),
                ),
              ),
            ),

            // Bottom button
            Container(
              padding: const EdgeInsets.fromLTRB(20, 12, 20, 20),
              child: SizedBox(
                width: double.infinity,
                height: 56,
                child: ElevatedButton(
                  onPressed: _onNext,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF0F6B35),
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(28),
                    ),
                    elevation: 0,
                  ),
                  child: const Text(
                    'Next',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildLabel(String text) {
    return Text(
      text,
      style: const TextStyle(
        fontSize: 14,
        fontWeight: FontWeight.w600,
        color: Colors.black,
      ),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String hint,
    int maxLines = 1,
    TextInputType? keyboardType,
    String? Function(String?)? validator,
  }) {
    return TextFormField(
      controller: controller,
      maxLines: maxLines,
      keyboardType: keyboardType,
      validator: validator,
      style: const TextStyle(fontSize: 14, color: Colors.black),
      decoration: InputDecoration(
        hintText: hint,
        hintStyle: const TextStyle(color: Color(0xFF9CA3AF), fontSize: 14),
        filled: true,
        fillColor: const Color(0xFFF9FAFB),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: Color(0xFF0F6B35), width: 1.5),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: AppColors.error),
        ),
      ),
    );
  }
}
