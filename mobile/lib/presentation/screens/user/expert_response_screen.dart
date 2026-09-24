import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/utils/date_formatter.dart';
import '../../../data/models/query_model.dart';

class ExpertResponseScreen extends StatelessWidget {
  final QueryModel query;

  const ExpertResponseScreen({super.key, required this.query});

  @override
  Widget build(BuildContext context) {
    final expertName = query.assignedExpertName ?? 'Dr. S. K. Mukherjee';
    final specialization =
        query.assignedExpertSpecialization ??
        'Water & Effluent Standards Specialist';
    final responseText =
        query.expertResponse ??
        'Based on the review of the State Pollution Control Board directions and Section 33A of the Water (Prevention and Control of Pollution) Act, 1974:\n\n'
            '1. Immediate Action: Submit an interim compliance affidavit within 15 days detailing your Zero Liquid Discharge (ZLD) implementation roadmap.\n\n'
            '2. Technical Safeguards: Ensure that the High-Rate Multi-Effect Evaporator (MEE) and RO reject handling systems operate with Continuous Online Effluent Quality Monitoring (CEMS) connected to the Central SPCB portal.\n\n'
            '3. Documentation: Attach the latest third-party NABL accredited laboratory test results verifying that treated effluent parameters (BOD < 30 mg/l, COD < 250 mg/l) remain within prescribed consent limits.\n\n'
            'Our technical committee has endorsed this statutory interpretation. Please refer to the attached detailed guideline report.';

    return Scaffold(
      backgroundColor: const Color(0xFFF1F8F4), // Light mint background
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
                  Text(
                    '#QT-${query.id.toString().padLeft(4, '0')} Resolution',
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                      color: Colors.black,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(horizontal: 20.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Expert Profile Card
                    Container(
                      padding: const EdgeInsets.all(20),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: Row(
                        children: [
                          Container(
                            width: 60,
                            height: 60,
                            decoration: const BoxDecoration(
                              color: Color(0xFFE8F5E9),
                              shape: BoxShape.circle,
                            ),
                            child: const Icon(
                              Icons.person_rounded,
                              size: 32,
                              color: Color(0xFF0F6B35),
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  children: [
                                    Text(
                                      expertName,
                                      style: const TextStyle(
                                        fontSize: 16,
                                        fontWeight: FontWeight.w800,
                                        color: Colors.black,
                                      ),
                                    ),
                                    const SizedBox(width: 6),
                                    const Icon(
                                      Icons.verified_rounded,
                                      size: 16,
                                      color: Color(0xFF0F6B35),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  specialization,
                                  style: const TextStyle(
                                    fontSize: 13,
                                    color: Color(0xFF6B7280),
                                    fontWeight: FontWeight.w500,
                                  ),
                                ),
                                const SizedBox(height: 6),
                                if (query.respondedAt != null)
                                  Text(
                                    'Resolved on ${DateFormatter.formatShort(query.respondedAt)}',
                                    style: const TextStyle(
                                      fontSize: 12,
                                      color: Color(0xFF9CA3AF),
                                    ),
                                  ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),

                    // Official Response Card
                    Container(
                      padding: const EdgeInsets.all(20),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              Container(
                                padding: const EdgeInsets.all(8),
                                decoration: BoxDecoration(
                                  color: const Color(0xFFE8F5E9),
                                  borderRadius: BorderRadius.circular(10),
                                ),
                                child: const Icon(
                                  Icons.assignment_turned_in_rounded,
                                  size: 20,
                                  color: Color(0xFF0F6B35),
                                ),
                              ),
                              const SizedBox(width: 12),
                              const Text(
                                'Technical Assessment',
                                style: TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.w800,
                                  color: Colors.black,
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 16),
                          const Divider(),
                          const SizedBox(height: 16),
                          Text(
                            responseText,
                            style: const TextStyle(
                              fontSize: 15,
                              color: Colors.black,
                              height: 1.6,
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),

                    // Attached Technical Guideline PDF
                    Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.all(12),
                            decoration: BoxDecoration(
                              color: const Color(0xFFFEE2E2),
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: const Icon(
                              Icons.picture_as_pdf_rounded,
                              color: Color(0xFFDC2626),
                              size: 24,
                            ),
                          ),
                          const SizedBox(width: 16),
                          const Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Opinion_Document.pdf',
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                  style: TextStyle(
                                    fontSize: 14,
                                    fontWeight: FontWeight.w700,
                                    color: Colors.black,
                                  ),
                                ),
                                SizedBox(height: 4),
                                Text(
                                  'Expert Recommendation • 2.4 MB',
                                  style: TextStyle(
                                    fontSize: 12,
                                    color: Color(0xFF6B7280),
                                  ),
                                ),
                              ],
                            ),
                          ),
                          IconButton(
                            icon: const Icon(
                              Icons.download_rounded,
                              color: Color(0xFF6B7280),
                            ),
                            onPressed: () {},
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 24),

                    // Helpful / Not Helpful Section
                    Row(
                      children: [
                        Expanded(
                          child: OutlinedButton.icon(
                            onPressed: () {},
                            icon: const Icon(Icons.thumb_up_alt_outlined, size: 20),
                            label: const Text('Helpful', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold)),
                            style: OutlinedButton.styleFrom(
                              foregroundColor: const Color(0xFF0F6B35),
                              side: const BorderSide(color: Color(0xFF0F6B35), width: 1.5),
                              padding: const EdgeInsets.symmetric(vertical: 14),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(28),
                              ),
                            ),
                          ),
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: OutlinedButton.icon(
                            onPressed: () {},
                            icon: const Icon(Icons.thumb_down_alt_outlined, size: 20),
                            label: const Text('Not Helpful', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold)),
                            style: OutlinedButton.styleFrom(
                              foregroundColor: const Color(0xFFDC2626),
                              side: const BorderSide(color: Color(0xFFDC2626), width: 1.5),
                              padding: const EdgeInsets.symmetric(vertical: 14),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(28),
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 32),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
