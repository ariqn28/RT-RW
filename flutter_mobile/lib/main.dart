import 'package:flutter/material.dart';

import 'warga_login_page.dart';

void main() {
  runApp(const RtRwMobileApp());
}

class RtRwMobileApp extends StatelessWidget {
  const RtRwMobileApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'RT/RW Warga',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.indigo),
        useMaterial3: true,
      ),
      home: const WargaLoginPage(),
    );
  }
}
