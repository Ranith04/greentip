import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import '../../../core/constants/app_colors.dart';
import '../../../core/utils/date_formatter.dart';
import '../../blocs/notification/notification_bloc.dart';
import '../../widgets/feedback/empty_state_widget.dart';

class NotificationsScreen extends StatefulWidget {
  const NotificationsScreen({super.key});

  @override
  State<NotificationsScreen> createState() => _NotificationsScreenState();
}

class _NotificationsScreenState extends State<NotificationsScreen> {
  @override
  void initState() {
    super.initState();
    context.read<NotificationBloc>().add(LoadNotificationsEvent());
  }

  IconData _getIconForType(String? type) {
    switch (type) {
      case 'Response':
        return Icons.edit_note_rounded;
      case 'Assigned':
        return Icons.person_add_alt_1_rounded;
      case 'Article':
        return Icons.article_outlined;
      case 'StatusUpdate':
        return Icons.autorenew_rounded;
      default:
        return Icons.campaign_outlined;
    }
  }

  Color _getIconBgColor(String? type) {
    switch (type) {
      case 'Response':
        return const Color(0xFFFFF3E0);
      case 'Assigned':
        return const Color(0xFFEDE7F6);
      case 'Article':
        return const Color(0xFFFFF8E1);
      case 'StatusUpdate':
        return const Color(0xFFE8F5E9);
      default:
        return const Color(0xFFFCE4EC);
    }
  }

  Color _getIconColor(String? type) {
    switch (type) {
      case 'Response':
        return const Color(0xFFE65100);
      case 'Assigned':
        return const Color(0xFF6A1B9A);
      case 'Article':
        return const Color(0xFFF57F17);
      case 'StatusUpdate':
        return const Color(0xFF2E7D32);
      default:
        return const Color(0xFFC62828);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
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
                    'Notifications',
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      color: Colors.black,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Notification list
            Expanded(
              child: RefreshIndicator(
                onRefresh: () async {
                  context.read<NotificationBloc>().add(LoadNotificationsEvent());
                },
                child: BlocBuilder<NotificationBloc, NotificationState>(
                  builder: (context, state) {
                    if (state is NotificationLoading) {
                      return const Center(
                        child: CircularProgressIndicator(color: Color(0xFF0F6B35)),
                      );
                    }

                    if (state is NotificationError) {
                      return Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            const Icon(Icons.error_outline, size: 48, color: AppColors.error),
                            const SizedBox(height: 12),
                            Text(state.message, style: const TextStyle(color: AppColors.textSecondary)),
                            const SizedBox(height: 16),
                            ElevatedButton(
                              onPressed: () => context.read<NotificationBloc>().add(LoadNotificationsEvent()),
                              child: const Text('Retry'),
                            ),
                          ],
                        ),
                      );
                    }

                    if (state is NotificationsLoaded) {
                      final notifications = state.notifications;

                      if (notifications.isEmpty) {
                        return const EmptyStateWidget(
                          icon: Icons.notifications_none_rounded,
                          title: 'No Notifications',
                          description: 'You are all caught up! New query updates will appear here.',
                        );
                      }

                      return Container(
                        margin: const EdgeInsets.symmetric(horizontal: 20),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(color: const Color(0xFFE2E8F0)),
                        ),
                        child: ListView.separated(
                          padding: EdgeInsets.zero,
                          itemCount: notifications.length,
                          separatorBuilder: (_, __) => const Divider(height: 1, indent: 70),
                          itemBuilder: (context, index) {
                            final notif = notifications[index];
                            final isUnread = !notif.isRead;

                            return InkWell(
                              onTap: () {
                                context.read<NotificationBloc>().add(
                                  MarkNotificationAsReadEvent(notif.id),
                                );
                                if (notif.relatedQueryId != null) {
                                  context.push('/user/query-details/${notif.relatedQueryId}');
                                }
                              },
                              child: Padding(
                                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
                                child: Row(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    // Icon
                                    Container(
                                      width: 44,
                                      height: 44,
                                      decoration: BoxDecoration(
                                        color: _getIconBgColor(notif.type),
                                        borderRadius: BorderRadius.circular(12),
                                      ),
                                      child: Icon(
                                        _getIconForType(notif.type),
                                        size: 22,
                                        color: _getIconColor(notif.type),
                                      ),
                                    ),
                                    const SizedBox(width: 14),
                                    // Content
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            notif.title,
                                            style: TextStyle(
                                              fontSize: 15,
                                              fontWeight: isUnread ? FontWeight.w700 : FontWeight.w600,
                                              color: Colors.black,
                                            ),
                                          ),
                                          const SizedBox(height: 3),
                                          Text(
                                            notif.message,
                                            style: const TextStyle(
                                              fontSize: 13,
                                              color: Color(0xFF6B7280),
                                            ),
                                          ),
                                          const SizedBox(height: 4),
                                          Text(
                                            DateFormatter.formatRelative(notif.createdAt),
                                            style: const TextStyle(
                                              fontSize: 12,
                                              color: Color(0xFF9CA3AF),
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                    if (isUnread)
                                      Container(
                                        width: 8,
                                        height: 8,
                                        margin: const EdgeInsets.only(top: 6),
                                        decoration: const BoxDecoration(
                                          color: Color(0xFF2563EB),
                                          shape: BoxShape.circle,
                                        ),
                                      ),
                                  ],
                                ),
                              ),
                            );
                          },
                        ),
                      );
                    }

                    return const SizedBox.shrink();
                  },
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
