# API Integration Guide for SMA Mobile App

This guide provides instructions on how to integrate the Laravel API with your Flutter application to access student and teacher accounts.

## API Endpoints

### Authentication

-   **Login**: `POST /api/login`

    -   Request Body:
        ```json
        {
            "email": "student@example.com",
            "password": "password",
            "device_name": "Flutter Mobile App"
        }
        ```
    -   Response:
        ```json
        {
            "user": {
                "id": 1,
                "name": "Student Name",
                "email": "student@example.com",
                "role": "student",
                "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
                // other user attributes
            },
            "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
            "message": "Login berhasil"
        }
        ```

-   **Logout**: `POST /api/logout` (Requires authentication)
    -   Headers: `Authorization: Bearer YOUR_TOKEN`
    -   Response:
        ```json
        {
            "message": "Logout berhasil"
        }
        ```

### Student Data

-   **Get Current Student**: `GET /api/students` (For students to get their own data)

    -   Headers: `Authorization: Bearer YOUR_TOKEN`
    -   Response:
        ```json
        {
            "students": [
                {
                    "id": 1,
                    "name": "Student Name",
                    "email": "student@example.com",
                    "role": "student",
                    "nis": "123456",
                    "nisn": "123456789",
                    "class": "XI-A",
                    "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
                    // other student attributes
                }
            ],
            "count": 1
        }
        ```

-   **Get Student by ID**: `GET /api/students/{id}` (Requires authentication)

    -   Headers: `Authorization: Bearer YOUR_TOKEN`
    -   Response:
        ```json
        {
            "student": {
                "id": 1,
                "name": "Student Name",
                "email": "student@example.com",
                "role": "student",
                "nis": "123456",
                "nisn": "123456789",
                "class": "XI-A",
                "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
                // other student attributes
            }
        }
        ```

-   **Update Student**: `PUT /api/students/{id}` (Requires authentication)

    -   Headers: `Authorization: Bearer YOUR_TOKEN`
    -   Request Body (include only fields you want to update):
        ```json
        {
            "name": "Updated Name",
            "phone_number": "08123456789",
            "address": "Updated Address"
        }
        ```
    -   Response:
        ```json
        {
            "message": "Data siswa berhasil diperbarui",
            "student": {
                // updated student data
            }
        }
        ```

-   **Update Profile Photo**: `POST /api/students/profile-photo` (Requires authentication)
    -   Headers: `Authorization: Bearer YOUR_TOKEN`
    -   Form Data:
        -   `profile_photo`: File (image)
        -   `id`: User ID
    -   Response:
        ```json
        {
            "message": "Foto profil siswa berhasil diperbarui",
            "profile_photo": "profile-photos/image.jpg",
            "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
        }
        ```

### Teacher Data

-   **Get Current Teacher**: `GET /api/teachers` (For teachers to get their own data)

    -   Headers: `Authorization: Bearer YOUR_TOKEN`
    -   Response:
        ```json
        {
            "teachers": [
                {
                    "id": 2,
                    "name": "Teacher Name",
                    "email": "teacher@example.com",
                    "role": "teacher",
                    "nip": "987654",
                    "nuptk": "987654321",
                    "subject": "Mathematics",
                    "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
                    // other teacher attributes
                }
            ],
            "count": 1
        }
        ```

-   **Get Teacher by ID**: `GET /api/teachers/{id}` (Requires authentication)

    -   Headers: `Authorization: Bearer YOUR_TOKEN`
    -   Response:
        ```json
        {
            "teacher": {
                "id": 2,
                "name": "Teacher Name",
                "email": "teacher@example.com",
                "role": "teacher",
                "nip": "987654",
                "nuptk": "987654321",
                "subject": "Mathematics",
                "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
                // other teacher attributes
            }
        }
        ```

-   **Update Teacher**: `PUT /api/teachers/{id}` (Requires authentication)

    -   Headers: `Authorization: Bearer YOUR_TOKEN`
    -   Request Body (include only fields you want to update):
        ```json
        {
            "name": "Updated Name",
            "phone_number": "08123456789",
            "address": "Updated Address"
        }
        ```
    -   Response:
        ```json
        {
            "message": "Data guru berhasil diperbarui",
            "teacher": {
                // updated teacher data
            }
        }
        ```

-   **Update Profile Photo**: `POST /api/teachers/profile-photo` (Requires authentication)
    -   Headers: `Authorization: Bearer YOUR_TOKEN`
    -   Form Data:
        -   `profile_photo`: File (image)
        -   `id`: User ID
    -   Response:
        ```json
        {
            "message": "Foto profil guru berhasil diperbarui",
            "profile_photo": "profile-photos/image.jpg",
            "profile_photo_url": "https://example.com/storage/profile-photos/image.jpg"
        }
        ```

## Flutter Integration Example

Below is a simple example of how to integrate this API with your Flutter application:

### 1. Add Dependencies to `pubspec.yaml`

```yaml
dependencies:
    flutter:
        sdk: flutter
    http: ^0.13.5
    shared_preferences: ^2.1.0
    provider: ^6.0.5
    image_picker: ^0.8.7+1
```

### 2. Create API Service

```dart
// lib/services/api_service.dart
import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  // Base URL of your Laravel API
  final String baseUrl = 'https://your-api-domain.com/api';

  // Get token from shared preferences
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  // Save token to shared preferences
  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', token);
  }

  // Remove token from shared preferences (logout)
  Future<void> removeToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
  }

  // Login API call
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'email': email,
        'password': password,
        'device_name': 'Flutter Mobile App'
      }),
    );

    final data = jsonDecode(response.body);

    if (response.statusCode == 200) {
      // Save token if login is successful
      await saveToken(data['token']);
    }

    return data;
  }

  // Logout API call
  Future<Map<String, dynamic>> logout() async {
    final token = await getToken();
    final response = await http.post(
      Uri.parse('$baseUrl/logout'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json'
      },
    );

    if (response.statusCode == 200) {
      await removeToken();
    }

    return jsonDecode(response.body);
  }

  // Get user profile (student/teacher)
  Future<Map<String, dynamic>> getUserProfile() async {
    final token = await getToken();
    final response = await http.get(
      Uri.parse('$baseUrl/user'),
      headers: {'Authorization': 'Bearer $token'},
    );

    return jsonDecode(response.body);
  }

  // Get student data
  Future<Map<String, dynamic>> getStudentData() async {
    final token = await getToken();
    final response = await http.get(
      Uri.parse('$baseUrl/students'),
      headers: {'Authorization': 'Bearer $token'},
    );

    return jsonDecode(response.body);
  }

  // Get teacher data
  Future<Map<String, dynamic>> getTeacherData() async {
    final token = await getToken();
    final response = await http.get(
      Uri.parse('$baseUrl/teachers'),
      headers: {'Authorization': 'Bearer $token'},
    );

    return jsonDecode(response.body);
  }

  // Update profile photo
  Future<Map<String, dynamic>> updateProfilePhoto(File photo, int userId, String role) async {
    final token = await getToken();

    var request = http.MultipartRequest(
      'POST',
      Uri.parse('$baseUrl/${role == 'student' ? 'students' : 'teachers'}/profile-photo'),
    );

    request.headers['Authorization'] = 'Bearer $token';
    request.fields['id'] = userId.toString();
    request.files.add(await http.MultipartFile.fromPath(
      'profile_photo',
      photo.path,
    ));

    var streamedResponse = await request.send();
    var response = await http.Response.fromStream(streamedResponse);

    return jsonDecode(response.body);
  }
}
```

### 3. Create User Model

```dart
// lib/models/user_model.dart
class User {
  final int id;
  final String name;
  final String email;
  final String role;
  final String? profilePhotoUrl;

  // Student specific fields
  final String? nis;
  final String? nisn;
  final String? className;

  // Teacher specific fields
  final String? nip;
  final String? nuptk;
  final String? subject;

  // Common fields
  final String? phoneNumber;
  final String? address;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    this.profilePhotoUrl,
    this.nis,
    this.nisn,
    this.className,
    this.nip,
    this.nuptk,
    this.subject,
    this.phoneNumber,
    this.address,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      role: json['role'],
      profilePhotoUrl: json['profile_photo_url'],
      nis: json['nis'],
      nisn: json['nisn'],
      className: json['class'],
      nip: json['nip'],
      nuptk: json['nuptk'],
      subject: json['subject'],
      phoneNumber: json['phone_number'],
      address: json['address'],
    );
  }
}
```

### 4. Create Auth Provider

```dart
// lib/providers/auth_provider.dart
import 'package:flutter/foundation.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  User? _user;
  bool _isLoading = false;
  final ApiService _apiService = ApiService();

  User? get user => _user;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _user != null;

  Future<bool> checkAuthStatus() async {
    final token = await _apiService.getToken();
    if (token == null) return false;

    try {
      final userData = await _apiService.getUserProfile();
      _user = User.fromJson(userData);
      notifyListeners();
      return true;
    } catch (e) {
      await _apiService.removeToken();
      return false;
    }
  }

  Future<Map<String, dynamic>> login(String email, String password) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.login(email, password);

      if (response.containsKey('user') && response.containsKey('token')) {
        _user = User.fromJson(response['user']);
      }

      _isLoading = false;
      notifyListeners();
      return response;
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      return {'error': e.toString()};
    }
  }

  Future<void> logout() async {
    _isLoading = true;
    notifyListeners();

    try {
      await _apiService.logout();
    } catch (e) {
      // Even if API call fails, remove token locally
    }

    await _apiService.removeToken();
    _user = null;
    _isLoading = false;
    notifyListeners();
  }

  // Fetch user specific data based on role
  Future<void> fetchUserData() async {
    if (_user == null) return;

    _isLoading = true;
    notifyListeners();

    try {
      Map<String, dynamic> userData;

      if (_user!.role == 'student') {
        userData = await _apiService.getStudentData();
        if (userData.containsKey('students') && userData['students'].isNotEmpty) {
          _user = User.fromJson(userData['students'][0]);
        }
      } else if (_user!.role == 'teacher') {
        userData = await _apiService.getTeacherData();
        if (userData.containsKey('teachers') && userData['teachers'].isNotEmpty) {
          _user = User.fromJson(userData['teachers'][0]);
        }
      }

      _isLoading = false;
      notifyListeners();
    } catch (e) {
      _isLoading = false;
      notifyListeners();
    }
  }
}
```

### 5. Create Login Screen

```dart
// lib/screens/login_screen.dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class LoginScreen extends StatefulWidget {
  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscureText = true;
  String? _errorMessage;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _errorMessage = null;
    });

    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final response = await authProvider.login(
      _emailController.text.trim(),
      _passwordController.text,
    );

    if (response.containsKey('error') || !authProvider.isAuthenticated) {
      setState(() {
        _errorMessage = response.containsKey('error')
            ? response['error']
            : 'Email atau password salah';
      });
      return;
    }

    // Navigate to home page
    Navigator.of(context).pushReplacementNamed('/home');
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);

    return Scaffold(
      backgroundColor: Colors.blue[50],
      body: Center(
        child: SingleChildScrollView(
          padding: EdgeInsets.all(20),
          child: Card(
            elevation: 4,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(15),
            ),
            child: Padding(
              padding: EdgeInsets.all(24),
              child: Form(
                key: _formKey,
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    CircleAvatar(
                      radius: 50,
                      backgroundColor: Colors.blue[700],
                      child: Icon(Icons.school, size: 60, color: Colors.white),
                    ),
                    SizedBox(height: 24),
                    Text(
                      'SMA Mobile App',
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: Colors.blue[700],
                      ),
                    ),
                    SizedBox(height: 36),
                    TextFormField(
                      controller: _emailController,
                      decoration: InputDecoration(
                        labelText: 'Email',
                        prefixIcon: Icon(Icons.email),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                      keyboardType: TextInputType.emailAddress,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Email tidak boleh kosong';
                        }
                        return null;
                      },
                    ),
                    SizedBox(height: 16),
                    TextFormField(
                      controller: _passwordController,
                      obscureText: _obscureText,
                      decoration: InputDecoration(
                        labelText: 'Password',
                        prefixIcon: Icon(Icons.lock),
                        suffixIcon: IconButton(
                          icon: Icon(
                            _obscureText ? Icons.visibility : Icons.visibility_off,
                          ),
                          onPressed: () {
                            setState(() {
                              _obscureText = !_obscureText;
                            });
                          },
                        ),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Password tidak boleh kosong';
                        }
                        return null;
                      },
                    ),
                    if (_errorMessage != null) ...[
                      SizedBox(height: 16),
                      Text(
                        _errorMessage!,
                        style: TextStyle(color: Colors.red),
                      ),
                    ],
                    SizedBox(height: 24),
                    SizedBox(
                      width: double.infinity,
                      height: 50,
                      child: ElevatedButton(
                        onPressed: authProvider.isLoading ? null : _login,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.blue[700],
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        child: authProvider.isLoading
                            ? CircularProgressIndicator(color: Colors.white)
                            : Text(
                                'Login',
                                style: TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
```

### 6. Main App Setup

```dart
// lib/main.dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/auth_provider.dart';
import 'screens/login_screen.dart';
import 'screens/home_screen.dart';
import 'screens/splash_screen.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
      ],
      child: MaterialApp(
        title: 'SMA Mobile App',
        theme: ThemeData(
          primarySwatch: Colors.blue,
          primaryColor: Color(0xFF0066b3),
          scaffoldBackgroundColor: Colors.white,
          fontFamily: 'Inter',
        ),
        home: SplashScreen(),
        routes: {
          '/login': (context) => LoginScreen(),
          '/home': (context) => HomeScreen(),
        },
      ),
    );
  }
}

// lib/screens/splash_screen.dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class SplashScreen extends StatefulWidget {
  @override
  _SplashScreenState createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _checkAuthStatus();
  }

  Future<void> _checkAuthStatus() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final isAuthenticated = await authProvider.checkAuthStatus();

    if (isAuthenticated) {
      await authProvider.fetchUserData();
      Navigator.of(context).pushReplacementNamed('/home');
    } else {
      Navigator.of(context).pushReplacementNamed('/login');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).primaryColor,
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.school,
              size: 80,
              color: Colors.white,
            ),
            SizedBox(height: 20),
            Text(
              'SMA Mobile App',
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
                color: Colors.white,
              ),
            ),
            SizedBox(height: 40),
            CircularProgressIndicator(
              valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
            ),
          ],
        ),
      ),
    );
  }
}
```

## Security Considerations

1. All API endpoints (except login) require authentication via Laravel Sanctum tokens.
2. Students can only access their own data, not other students' data.
3. Teachers can only access their own data, not other teachers' data.
4. The registration endpoint is disabled by default to ensure that accounts can only be created through the admin dashboard.
5. All data updates are validated on the server side to prevent invalid inputs.

## API Error Handling

The API returns appropriate error responses with corresponding status codes:

-   401: Unauthorized (invalid or expired token)
-   403: Forbidden (trying to access data you don't have permission for)
-   404: Not Found (resource doesn't exist)
-   422: Validation Error (invalid input)
-   500: Server Error

Each error response includes a message field explaining the error.

## Deployment Considerations

1. Ensure the API is served over HTTPS to secure data transmission.
2. Set appropriate CORS headers if your Flutter app runs on a different domain.
3. Consider setting up rate limiting to prevent abuse.
4. Implement proper logging for debugging and security monitoring.

## Implementing Authentication with Laravel Sanctum

The latest updates to the API now use Laravel Sanctum for authentication. Here's how it's implemented in the Flutter app:

### API Service Class

```dart
// File: lib/services/api_service.dart

import 'dart:convert';
import 'dart:io';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user_model.dart';

class ApiService {
  final Dio _dio = Dio();
  final String _baseUrl = 'http://10.0.2.2:8000/api'; // Android emulator localhost
  // Use 'http://localhost:8000/api' for iOS simulator or web
  // Use your actual server IP for physical devices

  ApiService() {
    _dio.options.headers['Accept'] = 'application/json';
    _dio.options.headers['Content-Type'] = 'application/json';
    _initializeToken(); // Initialize token from storage on start
  }

  // Initialize token from storage if available
  Future<void> _initializeToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    if (token != null) {
      _dio.options.headers['Authorization'] = 'Bearer $token';
    }
  }

  // Set the auth token for subsequent requests
  Future<void> setAuthToken(String token) async {
    _dio.options.headers['Authorization'] = 'Bearer $token';

    // Save token to SharedPreferences
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  // Login method example
  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await _dio.post(
        '$_baseUrl/login',
        data: {
          'email': email,
          'password': password,
          'device_name': 'Flutter Mobile App',
        },
      );

      if (response.statusCode == 200) {
        final data = response.data;
        final token = data['token'];

        // Set token for subsequent requests
        await setAuthToken(token);

        return {
          'success': true,
          'user': User.fromJson(data['user']),
          'message': data['message'],
        };
      }

      return {
        'success': false,
        'message': 'Login gagal. Coba lagi.',
      };
    } catch (e) {
      // Error handling
      return {
        'success': false,
        'message': 'Terjadi kesalahan. Coba lagi nanti.',
      };
    }
  }
}
```

### User Model

```dart
// File: lib/models/user_model.dart

class User {
  final int id;
  final String name;
  final String email;
  final String role;
  final String? profilePhotoUrl;
  final String? address;
  final String? phoneNumber;
  final String? gender;
  final String? dateOfBirth;
  final String? createdAt;
  final String? updatedAt;

  // Additional fields for students
  final String? nisn;
  final String? className;

  // Additional fields for teachers
  final String? nip;
  final String? subject;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    this.profilePhotoUrl,
    this.address,
    this.phoneNumber,
    this.gender,
    this.dateOfBirth,
    this.createdAt,
    this.updatedAt,
    // Student specific fields
    this.nisn,
    this.className,
    // Teacher specific fields
    this.nip,
    this.subject,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      role: json['role'] ?? 'user',
      profilePhotoUrl: json['profile_photo_url'],
      address: json['address'],
      phoneNumber: json['phone_number'],
      gender: json['gender'],
      dateOfBirth: json['date_of_birth'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
      // Student specific fields
      nisn: json['nisn'],
      className: json['class_name'],
      // Teacher specific fields
      nip: json['nip'],
      subject: json['subject'],
    );
  }
}
```

### Auth Provider

```dart
// File: lib/providers/auth_provider.dart

import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();

  User? _user;
  bool _isLoading = false;
  String? _errorMessage;

  // Getters
  User? get user => _user;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  bool get isLoggedIn => _user != null;

  // Constructor that checks for existing login
  AuthProvider() {
    _checkCurrentAuth();
  }

  // Automatically check if user is already logged in
  Future<void> _checkCurrentAuth() async {
    _isLoading = true;
    notifyListeners();

    try {
      final isLoggedIn = await _apiService.isLoggedIn();

      if (isLoggedIn) {
        _user = await _apiService.getCurrentUser();
      }
    } catch (e) {
      print('Error checking authentication: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Login
  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final result = await _apiService.login(email, password);

      if (result['success']) {
        _user = result['user'];
        _errorMessage = null;
        notifyListeners();
        return true;
      } else {
        _errorMessage = result['message'];
        notifyListeners();
        return false;
      }
    } catch (e) {
      _errorMessage = 'Terjadi kesalahan. Silakan coba lagi nanti.';
      print('Login error in provider: $e');
      notifyListeners();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
```

### Login Screen

```dart
// File: lib/screens/login_screen.dart

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({Key? key}) : super(key: key);

  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;
  bool _obscurePassword = true;
  String _errorMessage = '';

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      final result = await authProvider.login(
        _emailController.text.trim(),
        _passwordController.text,
      );

      if (!result) {
        setState(() {
          _errorMessage = authProvider.errorMessage ?? 'Login gagal. Coba lagi.';
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Terjadi kesalahan. Silakan coba lagi nanti.';
      });
      print('Login error: $e');
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    // UI code for login form
  }
}
```

## Testing the API Integration

To test your integration, follow these steps:

1. Make sure your Laravel app is running: `php artisan serve`
2. Update the `_baseUrl` in `ApiService` to match your development server
3. Try logging in with valid credentials from your admin dashboard
4. Check if the API request is successful and you receive a token
5. Use the authenticated endpoints to fetch data

## Troubleshooting

If you encounter issues with authentication:

1. Check that Sanctum is properly configured in your Laravel app
2. Ensure CORS is correctly set up in `config/cors.php`
3. Verify that your `SANCTUM_STATEFUL_DOMAINS` in `.env` includes your app URLs
4. For local development, set your API URL to `10.0.2.2:8000` for Android emulator
5. Check your API routes to make sure they exist and are properly protected
6. Check that your user model uses the `HasApiTokens` trait
