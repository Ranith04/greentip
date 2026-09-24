import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/di/injection.dart';
import '../../../core/storage/secure_storage_service.dart';

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  State<OnboardingScreen> createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  final List<_OnboardingItem> _items = [
    _OnboardingItem(
      image: 'assets/images/onboarding1.jpg',
      title: 'Ask Environmental\nQuestions',
      description: 'Get expert guidance on environmental\nand regulatory matters.',
    ),
    _OnboardingItem(
      image: 'assets/images/onboarding2.jpg',
      title: 'Connect With\nExperts',
      description: 'Submit your query and get responses\nfrom relevant domain experts.',
    ),
    _OnboardingItem(
      image: 'assets/images/onboarding3.jpg',
      title: 'Track Your Queries',
      description: 'Easily track submitted queries, expert\nresponses and previous discussions.',
    ),
  ];

  Future<void> _completeOnboarding() async {
    await getIt<SecureStorageService>().setOnboardingSeen();
    if (mounted) {
      context.go('/login');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF1F8F4), // Light greenish background
      body: SafeArea(
        child: Column(
          children: [
            Expanded(
              child: PageView.builder(
                controller: _pageController,
                itemCount: _items.length,
                onPageChanged: (index) {
                  setState(() {
                    _currentPage = index;
                  });
                },
                itemBuilder: (context, index) {
                  final item = _items[index];
                  return Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const SizedBox(height: 40),
                      Text(
                        item.title,
                        textAlign: TextAlign.center,
                        style: const TextStyle(
                          fontSize: 28,
                          fontWeight: FontWeight.w900,
                          color: Color(0xFF034D2A), // Dark Green
                          height: 1.2,
                        ),
                      ),
                      const Spacer(),
                      // Using a circular clip for the image since screenshots show a circular light background
                      Container(
                        width: 280,
                        height: 280,
                        decoration: const BoxDecoration(
                          shape: BoxShape.circle,
                        ),
                        clipBehavior: Clip.antiAlias,
                        child: Image.asset(
                          item.image,
                          fit: BoxFit.cover,
                        ),
                      ),
                      const Spacer(),
                      Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 40.0),
                        child: Text(
                          item.description,
                          textAlign: TextAlign.center,
                          style: const TextStyle(
                            fontSize: 15,
                            color: Color(0xFF5D7365), // Muted dark green/gray
                            height: 1.5,
                          ),
                        ),
                      ),
                      const SizedBox(height: 40),
                    ],
                  );
                },
              ),
            ),
            // Bottom controls
            Padding(
              padding: const EdgeInsets.only(left: 32.0, right: 32.0, bottom: 40.0, top: 16.0),
              child: Column(
                children: [
                  // Indicators
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: List.generate(_items.length, (index) {
                      final isActive = index == _currentPage;
                      return AnimatedContainer(
                        duration: const Duration(milliseconds: 250),
                        margin: const EdgeInsets.symmetric(horizontal: 4),
                        width: isActive ? 32 : 8,
                        height: 8,
                        decoration: BoxDecoration(
                          color: isActive
                              ? const Color(0xFF288B46) // Active green
                              : const Color(0xFFC8D5CE), // Inactive gray/green
                          borderRadius: BorderRadius.circular(4),
                        ),
                      );
                    }),
                  ),
                  const SizedBox(height: 32),
                  SizedBox(
                    width: double.infinity,
                    height: 56,
                    child: ElevatedButton(
                      onPressed: () {
                        if (_currentPage == _items.length - 1) {
                          _completeOnboarding();
                        } else {
                          _pageController.nextPage(
                            duration: const Duration(milliseconds: 300),
                            curve: Curves.easeInOut,
                          );
                        }
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF288B46),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(28),
                        ),
                        elevation: 0,
                      ),
                      child: Text(
                        _currentPage == _items.length - 1 ? 'Get Started' : 'Next',
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _OnboardingItem {
  final String image;
  final String title;
  final String description;

  _OnboardingItem({
    required this.image,
    required this.title,
    required this.description,
  });
}
