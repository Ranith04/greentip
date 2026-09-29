# GreenTIP Mobile App

Welcome to the GreenTIP Mobile Application! This is a modern, scalable, and responsive Flutter application built to seamlessly interface with the GreenTIP API.

## 📱 About The Project

GreenTIP is a mobile application designed to provide intuitive and feature-rich experiences. It leverages a clean architecture approach, predictable state management using BLoC, robust routing with GoRouter, and seamless API communication using Dio.

### 🛠️ Tech Stack & Key Libraries

- **Framework:** [Flutter](https://flutter.dev/) (SDK ^3.12.2)
- **Language:** [Dart](https://dart.dev/)
- **State Management:** [flutter_bloc](https://pub.dev/packages/flutter_bloc) & [equatable](https://pub.dev/packages/equatable)
- **Routing:** [go_router](https://pub.dev/packages/go_router)
- **Dependency Injection:** [get_it](https://pub.dev/packages/get_it)
- **Networking:** [dio](https://pub.dev/packages/dio)
- **Local Storage:** [flutter_secure_storage](https://pub.dev/packages/flutter_secure_storage)
- **UI & Styling:** [google_fonts](https://pub.dev/packages/google_fonts), [shimmer](https://pub.dev/packages/shimmer), [cached_network_image](https://pub.dev/packages/cached_network_image), [pinput](https://pub.dev/packages/pinput)
- **Media & Files:** [image_picker](https://pub.dev/packages/image_picker), [file_picker](https://pub.dev/packages/file_picker)

---

## 🚀 Getting Started

Follow these detailed instructions to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

Before you begin, ensure you have the following installed and configured on your system:
- **[Flutter SDK](https://docs.flutter.dev/get-started/install)** (version 3.12.2 or higher)
- **[Dart SDK](https://dart.dev/get-dart)**
- **IDE:** Android Studio, IntelliJ IDEA, or Visual Studio Code (with Flutter & Dart plugins installed)
- An active Android Emulator, iOS Simulator, or a physical device connected with USB debugging enabled.
- **Backend:** Ensure the GreenTIP backend (`GreenTIP.Api` C# .NET project) is running locally or configured to a staging environment for the mobile app to communicate with.

### Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd greentip/mobile
   ```

2. **Install Dependencies:**
   Fetch all the packages specified in `pubspec.yaml` by running:
   ```bash
   flutter pub get
   ```

3. **Configure the Environment (If applicable):**
   If the app requires specific environment variables or API base URLs, configure your endpoint constants within the network core files to point to your local backend (`localhost` / `10.0.2.2` for emulators) or the production server.

4. **Run the App:**
   Run the application on your connected device or emulator:
   ```bash
   flutter run
   ```

---

## 🏗️ Architecture & Project Structure

The project follows a modular, feature-based architecture pattern to ensure scalability, maintainability, and testability. 

- **`lib/core/`**: Contains core configurations like routing (`go_router_refresh_stream.dart`), network clients, theme, error handling, and common utilities.
- **`lib/presentation/`**: Contains the UI layers including `screens` (e.g., `admin_shell_screen.dart`), `widgets`, and `blocs` for state management (e.g., `expert_bloc.dart`).
- **`lib/data/`**: Manages API calls, repositories, data models (DTOs), and local storage logic.
- **`lib/domain/`**: Houses the core business logic, entities, and use cases.

---

## 🧪 Testing

To run the automated tests for this project to ensure code reliability:

```bash
flutter test
```

## 🤝 Contribution Guidelines

1. **Fork** the project.
2. **Create your feature branch:** `git checkout -b feature/AmazingFeature`
3. **Commit your changes:** `git commit -m 'Add some AmazingFeature'`
4. **Push to the branch:** `git push origin feature/AmazingFeature`
5. **Open a Pull Request** for review.

---
*For any issues or further help getting started with Flutter development, please view the [official online documentation](https://docs.flutter.dev/).*
