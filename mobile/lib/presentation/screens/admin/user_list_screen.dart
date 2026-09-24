import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../data/models/user_model.dart';
import '../../blocs/user_management/user_management_bloc.dart';
import '../../widgets/feedback/empty_state_widget.dart';

class UserListScreen extends StatefulWidget {
  const UserListScreen({super.key});

  @override
  State<UserListScreen> createState() => _UserListScreenState();
}

class _UserListScreenState extends State<UserListScreen> {
  final TextEditingController _searchController = TextEditingController();
  String? _searchQuery;

  final List<UserModel> _fallbackUsers = const [
    UserModel(
      id: 1,
      name: 'Rajesh Sharma',
      email: 'rajesh@apexchem.com',
      mobile: '9820011223',
      organization: 'Apex Chemicals Ltd (Unit 2)',
      designation: 'EHS General Manager',
      roleId: 2,
      roleName: 'Industry User',
      status: 1,
    ),
    UserModel(
      id: 2,
      name: 'Pooja Nair',
      email: 'pooja@vardhmantextiles.in',
      mobile: '9833445566',
      organization: 'Vardhman Textiles & Processing',
      designation: 'Environmental Compliance Officer',
      roleId: 2,
      roleName: 'Industry User',
      status: 1,
    ),
    UserModel(
      id: 3,
      name: 'Amit Patel',
      email: 'amit@bharatpharma.com',
      mobile: '9876501234',
      organization: 'Bharat Pharmaceuticals Pvt Ltd',
      designation: 'Plant Head',
      roleId: 2,
      roleName: 'Industry User',
      status: 1,
    ),
  ];

  @override
  void initState() {
    super.initState();
    _loadUsers();
  }

  void _loadUsers() {
    context.read<UserManagementBloc>().add(
      LoadUsersEvent(search: _searchQuery),
    );
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('Registered Industrial Units'),
      ),
      body: Column(
        children: [
          // Search
          Container(
            color: Colors.white,
            padding: const EdgeInsets.symmetric(
              horizontal: 16.0,
              vertical: 8.0,
            ),
            child: TextField(
              controller: _searchController,
              onSubmitted: (val) {
                setState(
                  () => _searchQuery = val.trim().isEmpty ? null : val.trim(),
                );
                _loadUsers();
              },
              decoration: InputDecoration(
                hintText: 'Search by facility, contact name, or email...',
                prefixIcon: const Icon(
                  Icons.search,
                  size: 20,
                  color: AppColors.textMuted,
                ),
                contentPadding: const EdgeInsets.symmetric(vertical: 10),
                filled: true,
                fillColor: AppColors.background,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                  borderSide: BorderSide.none,
                ),
              ),
            ),
          ),
          const Divider(height: 1),

          // User List
          Expanded(
            child: RefreshIndicator(
              onRefresh: () async => _loadUsers(),
              child: BlocBuilder<UserManagementBloc, UserManagementState>(
                builder: (context, state) {
                  List<UserModel> users = _fallbackUsers;
                  if (state is UsersLoaded && state.users.isNotEmpty) {
                    users = state.users;
                  }

                  if (users.isEmpty) {
                    return const EmptyStateWidget(
                      icon: Icons.business_outlined,
                      title: 'No Facilities Found',
                      description:
                          'No industrial facilities match the search criteria.',
                    );
                  }

                  return ListView.separated(
                    padding: const EdgeInsets.all(16.0),
                    itemCount: users.length,
                    separatorBuilder: (_, _) => const SizedBox(height: 12),
                    itemBuilder: (context, index) {
                      final user = users[index];

                      return InkWell(
                        onTap: () => context.push(
                          '/admin/user-details/${user.id}',
                          extra: user,
                        ),
                        borderRadius: BorderRadius.circular(16),
                        child: Container(
                          padding: const EdgeInsets.all(16),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(16),
                            border: Border.all(color: AppColors.divider),
                          ),
                          child: Row(
                            children: [
                              CircleAvatar(
                                radius: 24,
                                backgroundColor: AppColors.primaryContainer,
                                child: Text(
                                  user.name.isNotEmpty
                                      ? user.name[0].toUpperCase()
                                      : 'U',
                                  style: const TextStyle(
                                    fontWeight: FontWeight.bold,
                                    color: AppColors.primaryDark,
                                  ),
                                ),
                              ),
                              const SizedBox(width: 14),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      user.name,
                                      style: const TextStyle(
                                        fontSize: 15,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                    const SizedBox(height: 2),
                                    Text(
                                      user.organization ??
                                          'Industrial Manufacturing Unit',
                                      style: const TextStyle(
                                        fontSize: 12,
                                        color: AppColors.primaryDark,
                                        fontWeight: FontWeight.w600,
                                      ),
                                    ),
                                    const SizedBox(height: 2),
                                    Text(
                                      user.email,
                                      style: const TextStyle(
                                        fontSize: 11,
                                        color: AppColors.textMuted,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              Switch(
                                value: user.status == 1,
                                activeThumbColor: AppColors.primary,
                                onChanged: (val) {
                                  context.read<UserManagementBloc>().add(
                                    ToggleUserStatusEvent(
                                      userId: user.id,
                                      status: val ? 1 : 0,
                                    ),
                                  );
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
          ),
        ],
      ),
    );
  }
}
