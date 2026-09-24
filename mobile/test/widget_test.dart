import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:greentip_app/core/utils/status_helper.dart';
import 'package:greentip_app/data/models/query_model.dart';
import 'package:greentip_app/data/models/user_model.dart';
import 'package:greentip_app/presentation/widgets/feedback/status_badge.dart';

void main() {
  group('GreenTIP Model Tests', () {
    test('UserModel serialization and role properties', () {
      final user = UserModel(
        id: 1,
        name: 'Test Officer',
        email: 'officer@industry.com',
        mobile: '9876543210',
        roleId: 2,
        roleName: 'EndUser',
        status: 1,
      );

      expect(user.isEndUser, isTrue);
      expect(user.isAdmin, isFalse);

      final json = user.toJson();
      final deserialized = UserModel.fromJson(json);

      expect(deserialized.id, equals(1));
      expect(deserialized.name, equals('Test Officer'));
      expect(deserialized.email, equals('officer@industry.com'));
    });

    test('UserModel parses backend AuthResponseDto correctly', () {
      final backendJson = {
        'userId': 19,
        'name': 'Umang Mathur',
        'email': 'umang@grc-india.com',
        'roleType': '0',
        'roleName': 'Admin',
        'accessToken': 'jwt.token.mock',
        'status': 1,
      };

      final user = UserModel.fromJson(backendJson);
      expect(user.id, equals(19));
      expect(user.isAdmin, isTrue);
      expect(user.token, equals('jwt.token.mock'));
    });

    test('QueryModel urgency and status handling', () {
      final query = QueryModel(
        id: 101,
        userId: 1,
        userName: 'Test Officer',
        title: 'Water ETP Limit Exceeded',
        description: 'Need guidance on chemical dosing',
        urgency: 'Urgent',
        status: 2,
        statusName: 'In Review',
        createdAt: DateTime.now(),
      );

      expect(query.isUrgent, isTrue);
      expect(query.status, equals(2));
      expect(StatusHelper.getStatusLabel(query.status), equals('In Review'));
    });

    test('StatusHelper handles all statuses correctly', () {
      expect(StatusHelper.getStatusLabel(0), equals('Submitted'));
      expect(StatusHelper.getStatusLabel(1), equals('Assigned'));
      expect(StatusHelper.getStatusLabel(2), equals('In Review'));
      expect(StatusHelper.getStatusLabel(3), equals('Answered'));
      expect(StatusHelper.getStatusLabel(4), equals('Closed'));
      expect(StatusHelper.getStatusLabel(-1), equals('Unknown'));
    });
  });

  group('GreenTIP Widget Tests', () {
    testWidgets('StatusBadge renders correct status and icon', (
      WidgetTester tester,
    ) async {
      await tester.pumpWidget(
        const MaterialApp(
          home: Scaffold(
            body: StatusBadge(status: 3), // Answered
          ),
        ),
      );

      expect(find.text('Answered'), findsOneWidget);
      expect(find.byIcon(Icons.check_circle_outline_rounded), findsOneWidget);
    });
  });
}
