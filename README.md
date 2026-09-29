# GreenTIP Complete Project

Welcome to the GreenTIP platform! This repository contains the complete end-to-end solution including the backend API, the web portal frontend, the mobile application, and the database schema.

## 🏗️ System Architecture & Tech Stack

- **Backend**: C# .NET 8 (ASP.NET Core Web API)
- **Web Portal (Frontend)**: PHP (CodeIgniter)
- **Mobile App**: Flutter (Dart SDK ^3.12.2)
- **Database**: MySQL 8.0 (MySQL 5.7 for web preview)
- **Containerization**: Docker & Docker Compose

## 📁 Repository Structure
- **`/backend`**: The ASP.NET Core API backend.
- **`/GreenTIP-webportalcode/GreenTIP-webportalcode`**: The PHP web portal frontend codebase.
- **`/mobile`**: The Flutter mobile application.
- **`docker-compose.yml`**: Root Docker Compose file to orchestrate the Backend and Database.

---

## 🚀 Complete Setup & Deployment Guide

### 1. Database Integration & Initialization

The project relies on a MySQL database. The schema and initial data are provided in `GreenTIP-webportalcode/GreenTIP-webportalcode/greentip.sql`.

**Using Docker Compose (Recommended):**
The root `docker-compose.yml` automatically spins up the MySQL 8.0 database and initializes it.
1. Ensure Docker is installed and running on your system.
2. From the root directory (`greentip`), run:
   ```bash
   docker-compose up -d mysql
   ```
3. The database will be accessible on `localhost:3306` with the following credentials:
   - **Database Name:** `greentip`
   - **User:** `root`
   - **Password:** `root_password`

### 2. Backend Setup (.NET 8 API)

The backend provides the API layer consumed by the mobile application (and optionally the web portal).

**Running with Docker:**
1. From the root directory, start the backend service:
   ```bash
   docker-compose up -d backend
   ```
2. The backend will be available at `http://localhost:5000`.

**Running Locally (Without Docker):**
1. Navigate to the backend directory:
   ```bash
   cd backend
   ```
2. Restore dependencies and run the project:
   ```bash
   dotnet restore GreenTIP.sln
   dotnet run --project src/GreenTIP.Api
   ```
   *Note: Update the connection string in your development settings or environment variables if you are not using the default Docker MySQL credentials.*

### 3. Web Portal Setup (PHP/CodeIgniter)

The web portal is a robust PHP application. You can easily preview it using the provided Docker setup.

**Running with Docker Preview:**
1. Navigate to the web portal source directory:
   ```bash
   cd GreenTIP-webportalcode/GreenTIP-webportalcode
   ```
2. Start the web portal using the preview compose file:
   ```bash
   docker-compose -f docker-compose.preview.yml up -d
   ```
3. The web portal will be accessible at `http://localhost` (Port 80).

**Manual Deployment (Apache/Nginx):**
1. Copy the contents of `GreenTIP-webportalcode/GreenTIP-webportalcode` to your server's web root (e.g., `/var/www/html/greentip`).
2. Ensure the server supports PHP and has required extensions enabled.
3. Update the database configuration in the CodeIgniter config directory to point to your central MySQL instance.

### 4. Mobile Application Setup (Flutter)

The mobile application is built with Flutter and communicates securely with the .NET backend.

**Prerequisites:**
- Flutter SDK (v3.12.2+)
- Dart SDK
- IDE (Android Studio / VS Code with Flutter plugin)
- Android Emulator / iOS Simulator

**Setup Instructions:**
1. Navigate to the mobile directory:
   ```bash
   cd mobile
   ```
2. Install Dart dependencies:
   ```bash
   flutter pub get
   ```
3. **Configure Environment:** Ensure that the API base URL in the app's network client points to your local backend. For an Android Emulator, this is typically `http://10.0.2.2:5000` (mapping to `localhost:5000`).
4. Run the application:
   ```bash
   flutter run
   ```

---

## ⚙️ Production Server Deployment & Configuration

For full production deployment, follow these best practices:

1. **Database Deployment:** Deploy a managed MySQL database instance (e.g., AWS RDS, Azure Database for MySQL) for high availability. Ensure regular automated backups. Execute `greentip.sql` to apply the schema.
2. **Backend (API) Deployment:**
   - Publish the .NET API application in Release mode: `dotnet publish -c Release`.
   - Host on a reliable cloud provider using a Linux VM with Nginx as a reverse proxy, AWS ECS, or Azure App Service.
   - Inject production environment variables, notably a secure `Jwt__Secret` and the production `ConnectionStrings__DefaultConnection`.
3. **Web Portal Deployment:**
   - Deploy the PHP code to a production-grade LAMP/LEMP stack server.
   - Secure the application using SSL/TLS certificates (e.g., Let's Encrypt) to force HTTPS.
4. **Mobile App Release:**
   - Build a production App Bundle for Android: `flutter build appbundle`.
   - Build an archive for iOS: `flutter build ipa`.
   - Distribute the builds via the Google Play Store and Apple App Store.

---

## 🧪 Testing

- **Backend:** Run automated C# tests using `dotnet test` within the `backend` directory.
- **Mobile:** Run Flutter widget and unit tests using `flutter test` within the `mobile` directory.
