import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../blocs/auth/auth_bloc.dart';
import '../../blocs/auth/auth_event.dart';
import '../../widgets/dialogs/logout_dialog.dart';

class AdminSettingsScreen extends StatefulWidget {
  const AdminSettingsScreen({super.key});

  @override
  State<AdminSettingsScreen> createState() => _AdminSettingsScreenState();
}

class _AdminSettingsScreenState extends State<AdminSettingsScreen> {
  bool _autoEscalation = true;
  bool _maintenanceMode = false;
  bool _emailAlerts = true;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('Portal Settings & Configuration'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Admin Security Banner
            Container(
              padding: const EdgeInsets.all(18),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: AppColors.divider),
              ),
              child: Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: AppColors.primaryContainer,
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: const Icon(
                      Icons.admin_panel_settings_rounded,
                      color: AppColors.primaryDark,
                      size: 30,
                    ),
                  ),
                  const SizedBox(width: 14),
                  const Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'System Administrator Console',
                          style: TextStyle(
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        SizedBox(height: 2),
                        Text(
                          'Full governance access • GreenTIP .NET 8 Core',
                          style: TextStyle(
                            fontSize: 12,
                            color: AppColors.textSecondary,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Modules
            const Text(
              'Administrative Modules',
              style: TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.bold,
                color: AppColors.textPrimary,
              ),
            ),
            const SizedBox(height: 10),

            Container(
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: AppColors.divider),
              ),
              child: Column(
                children: [
                  _navTile(
                    icon: Icons.business_outlined,
                    title: 'Industrial Facilities Directory',
                    subtitle:
                        'Manage plant units, consent data & state PCB links',
                    onTap: () => context.push('/admin/users'),
                  ),
                  const Divider(height: 1),
                  _navTile(
                    icon: Icons.category_outlined,
                    title: 'Statutory Categories & Domains',
                    subtitle:
                        'Water, Air, Hazardous, Forest, and Safety modules',
                    onTap: () {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text(
                            'All 6 statutory compliance domains active.',
                          ),
                        ),
                      );
                    },
                  ),
                  const Divider(height: 1),
                  _navTile(
                    icon: Icons.insights_rounded,
                    title: 'SLA Analytics & Monthly Reports',
                    subtitle:
                        'Resolution turnaround KPIs and compliance trends',
                    onTap: () {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text(
                            'Average SLA response time: 26.4 hours.',
                          ),
                        ),
                      );
                    },
                  ),
                  const Divider(height: 1),
                  _navTile(
                    icon: Icons.security_rounded,
                    title: 'System Security & Audit Trail',
                    subtitle:
                        'Authentication logs, role changes, and IP audits',
                    onTap: () {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text(
                            'All database transactions signed and encrypted.',
                          ),
                        ),
                      );
                    },
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Governance & SLA Policies
            const Text(
              'Governance & SLA Rules',
              style: TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.bold,
                color: AppColors.textPrimary,
              ),
            ),
            const SizedBox(height: 10),

            Container(
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: AppColors.divider),
              ),
              child: Column(
                children: [
                  SwitchListTile(
                    value: _autoEscalation,
                    activeThumbColor: AppColors.primary,
                    title: const Text(
                      'Auto-Escalate Unassigned Queries',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    subtitle: const Text(
                      'Notify supervisor if unassigned > 24 hours',
                      style: TextStyle(fontSize: 12),
                    ),
                    onChanged: (val) => setState(() => _autoEscalation = val),
                  ),
                  const Divider(height: 1),
                  SwitchListTile(
                    value: _emailAlerts,
                    activeThumbColor: AppColors.primary,
                    title: const Text(
                      'Real-time Dispatch Notifications',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    subtitle: const Text(
                      'Trigger immediate email to experts upon ticket assignment',
                      style: TextStyle(fontSize: 12),
                    ),
                    onChanged: (val) => setState(() => _emailAlerts = val),
                  ),
                  const Divider(height: 1),
                  SwitchListTile(
                    value: _maintenanceMode,
                    activeThumbColor: AppColors.error,
                    title: const Text(
                      'Portal Maintenance Mode',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                    subtitle: const Text(
                      'Temporarily pause client submissions for scheduled upgrades',
                      style: TextStyle(fontSize: 12),
                    ),
                    onChanged: (val) => setState(() => _maintenanceMode = val),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 28),

            // Logout
            InkWell(
              onTap: () {
                LogoutDialog.show(
                  context,
                  isAdmin: true,
                  onConfirm: () {
                    context.read<AuthBloc>().add(LogoutEvent());
                    context.go('/login');
                  },
                );
              },
              borderRadius: BorderRadius.circular(16),
              child: Container(
                padding: const EdgeInsets.symmetric(vertical: 16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: AppColors.errorLight),
                ),
                child: const Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.logout_rounded,
                      color: AppColors.error,
                      size: 20,
                    ),
                    SizedBox(width: 8),
                    Text(
                      'Secure Admin Sign Out',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: AppColors.error,
                      ),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),
          ],
        ),
      ),
    );
  }

  Widget _navTile({
    required IconData icon,
    required String title,
    required String subtitle,
    required VoidCallback onTap,
  }) {
    return ListTile(
      onTap: onTap,
      leading: Container(
        padding: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: AppColors.primaryContainer.withValues(alpha: 0.5),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Icon(icon, color: AppColors.primaryDark, size: 20),
      ),
      title: Text(
        title,
        style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600),
      ),
      subtitle: Text(
        subtitle,
        style: const TextStyle(fontSize: 11, color: AppColors.textSecondary),
      ),
      trailing: const Icon(
        Icons.chevron_right_rounded,
        size: 20,
        color: AppColors.textMuted,
      ),
    );
  }
}
