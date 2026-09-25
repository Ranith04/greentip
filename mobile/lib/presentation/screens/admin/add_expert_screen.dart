import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../blocs/expert/expert_bloc.dart';
import '../../widgets/buttons/app_buttons.dart';
import '../../widgets/inputs/app_text_field.dart';

class AddExpertScreen extends StatefulWidget {
  const AddExpertScreen({super.key});

  @override
  State<AddExpertScreen> createState() => _AddExpertScreenState();
}

class _AddExpertScreenState extends State<AddExpertScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _mobileController = TextEditingController();
  final _specializationController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isActive = true;

  final List<String> _specializations = [
    'Water Pollution & ETP/ZLD Compliance',
    'Air Emissions & CEMS Continuous Monitoring',
    'Hazardous Waste & TSDF Manifest Compliance',
    'Environmental Clearance & EIA Approvals',
    'SPCB Directives, NGT Appeals & Legal Notices',
    'Forest, Coastal & Biodiversity Clearance',
    'Factory Safety & Industrial Hygiene',
  ];

  @override
  void initState() {
    super.initState();
    _specializationController.text = _specializations.first;
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _mobileController.dispose();
    _specializationController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _onSave() {
    if (_formKey.currentState?.validate() ?? false) {
      context.read<ExpertBloc>().add(
        AddExpertEvent(
          name: _nameController.text.trim(),
          email: _emailController.text.trim(),
          mobile: _mobileController.text.trim(),
          password: _passwordController.text,
          specialization: _specializationController.text.trim(),
          status: _isActive ? 1 : 0,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return BlocListener<ExpertBloc, ExpertState>(
      listener: (context, state) {
        if (state is ExpertActionSuccess) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(state.message),
              backgroundColor: AppColors.success,
            ),
          );
          context.pop();
        } else if (state is ExpertError) {
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
          title: const Text('Add Compliance Specialist'),
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
            child: Form(
              key: _formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Onboard Technical Expert',
                    style: TextStyle(
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                      color: AppColors.textPrimary,
                    ),
                  ),
                  const SizedBox(height: 6),
                  const Text(
                    'Registered specialists are eligible to evaluate statutory notices and inquiries.',
                    style: TextStyle(
                      fontSize: 14,
                      color: AppColors.textSecondary,
                    ),
                  ),
                  const SizedBox(height: 24),

                  AppTextField(
                    controller: _nameController,
                    label: 'Full Name & Honorific *',
                    hint: 'e.g. Dr. Rajesh Kumar / Adv. P. K. Rao',
                    prefixIcon: Icons.badge_outlined,
                    validator: (v) => v?.isEmpty ?? true
                        ? 'Enter specialist full name'
                        : null,
                  ),
                  const SizedBox(height: 18),

                  AppTextField(
                    controller: _emailController,
                    label: 'Official Email *',
                    hint: 'expert@greentip.gov.in',
                    prefixIcon: Icons.email_outlined,
                    keyboardType: TextInputType.emailAddress,
                    validator: (v) =>
                        v?.isEmpty ?? true ? 'Enter official email' : null,
                  ),
                  const SizedBox(height: 18),

                  AppTextField(
                    controller: _mobileController,
                    label: 'Mobile Contact *',
                    hint: '9876543210',
                    prefixIcon: Icons.phone_outlined,
                    keyboardType: TextInputType.phone,
                    validator: (v) =>
                        v?.isEmpty ?? true ? 'Enter phone number' : null,
                  ),
                  const SizedBox(height: 18),

                  const Text(
                    'Primary Compliance Domain *',
                    style: TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
                  ),
                  const SizedBox(height: 6),
                  DropdownButtonFormField<String>(
                    initialValue: _specializationController.text,
                    items: _specializations
                        .map(
                          (s) => DropdownMenuItem(
                            value: s,
                            child: Text(
                              s,
                              style: const TextStyle(fontSize: 13),
                            ),
                          ),
                        )
                        .toList(),
                    onChanged: (val) {
                      if (val != null) {
                        setState(() => _specializationController.text = val);
                      }
                    },
                    decoration: const InputDecoration(
                      prefixIcon: Icon(
                        Icons.psychology_outlined,
                        size: 20,
                        color: AppColors.textSecondary,
                      ),
                    ),
                  ),
                  const SizedBox(height: 18),

                  AppTextField(
                    controller: _passwordController,
                    label: 'Temporary Portal Password *',
                    hint: '••••••••',
                    prefixIcon: Icons.lock_outline_rounded,
                    isPassword: true,
                    validator: (v) => (v?.length ?? 0) < 6
                        ? 'Password must be >= 6 chars'
                        : null,
                  ),
                  const SizedBox(height: 18),

                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'Active Status',
                        style: TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.w600,
                          color: AppColors.textPrimary,
                        ),
                      ),
                      Switch(
                        value: _isActive,
                        activeThumbColor: AppColors.primary,
                        onChanged: (val) {
                          setState(() {
                            _isActive = val;
                          });
                        },
                      ),
                    ],
                  ),
                  const SizedBox(height: 36),

                  BlocBuilder<ExpertBloc, ExpertState>(
                    builder: (context, state) {
                      return AppPrimaryButton(
                        text: 'Register Expert to Roster',
                        isLoading: state is ExpertLoading,
                        onPressed: _onSave,
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
