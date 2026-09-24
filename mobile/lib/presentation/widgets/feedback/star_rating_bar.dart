import 'package:flutter/material.dart';

class StarRatingBar extends StatelessWidget {
  final int rating;
  final ValueChanged<int>? onRatingChanged;
  final double size;
  final Color filledColor;
  final Color emptyColor;

  const StarRatingBar({
    super.key,
    required this.rating,
    this.onRatingChanged,
    this.size = 32,
    this.filledColor = const Color(0xFFFFB300),
    this.emptyColor = const Color(0xFFE0E0E0),
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: List.generate(5, (index) {
        final starIndex = index + 1;
        final isFilled = starIndex <= rating;

        return GestureDetector(
          onTap: onRatingChanged != null
              ? () => onRatingChanged!(starIndex)
              : null,
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 4.0),
            child: Icon(
              isFilled ? Icons.star_rounded : Icons.star_outline_rounded,
              size: size,
              color: isFilled ? filledColor : emptyColor,
            ),
          ),
        );
      }),
    );
  }
}
