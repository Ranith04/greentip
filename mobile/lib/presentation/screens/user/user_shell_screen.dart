import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/constants/app_strings.dart';

class UserShellScreen extends StatelessWidget {
  final StatefulNavigationShell navigationShell;

  const UserShellScreen({super.key, required this.navigationShell});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: navigationShell,
      floatingActionButton: FloatingActionButton(
        backgroundColor: const Color(0xFF288B46), // Active green
        elevation: 4,
        shape: const CircleBorder(),
        onPressed: () {
          context.push('/user/select-category');
        },
        child: const Icon(Icons.add, color: Colors.white, size: 32),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.05),
              blurRadius: 10,
              offset: const Offset(0, -5),
            ),
          ],
        ),
        child: BottomAppBar(
          shape: const CircularNotchedRectangle(),
          notchMargin: 8.0,
          color: Colors.white,
          elevation: 0,
          child: SizedBox(
            height: 60,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _buildNavItem(
                  icon: Icons.home_filled,
                  label: AppStrings.navHome,
                  isSelected: navigationShell.currentIndex == 0,
                  onTap: () => _onTap(0),
                ),
                _buildNavItem(
                  icon: Icons.chat_bubble_rounded,
                  label: AppStrings.navQueries,
                  isSelected: navigationShell.currentIndex == 1,
                  onTap: () => _onTap(1),
                ),
                const SizedBox(width: 48), // Space for FAB
                _buildNavItem(
                  icon: Icons.menu_book_rounded,
                  label: AppStrings.navKnowledge,
                  isSelected: navigationShell.currentIndex == 2,
                  onTap: () => _onTap(2),
                ),
                _buildNavItem(
                  icon: Icons.person_rounded,
                  label: AppStrings.navProfile,
                  isSelected: navigationShell.currentIndex == 3,
                  onTap: () => _onTap(3),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  void _onTap(int index) {
    navigationShell.goBranch(
      index,
      initialLocation: index == navigationShell.currentIndex,
    );
  }

  Widget _buildNavItem({
    required IconData icon,
    required String label,
    required bool isSelected,
    required VoidCallback onTap,
  }) {
    return GestureDetector(
      onTap: onTap,
      behavior: HitTestBehavior.opaque,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            icon,
            color: isSelected ? const Color(0xFF288B46) : const Color(0xFF9CA3AF),
            size: 24,
          ),
          const SizedBox(height: 4),
          Text(
            label,
            style: TextStyle(
              fontSize: 10,
              fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
              color: isSelected ? const Color(0xFF288B46) : const Color(0xFF9CA3AF),
            ),
          ),
        ],
      ),
    );
  }
}
