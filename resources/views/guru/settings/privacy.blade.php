@extends('layouts.guru')

@section('title', 'Pengaturan Privasi')
@section('header', 'Pengaturan Privasi')

@section('content')
<div class="container px-6 py-8 mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                    <a href="{{ route('guru.settings.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Pengaturan</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                    <span class="text-sm font-medium text-indigo-600">Privasi</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50">
            <h2 class="text-2xl font-semibold text-gray-800">Pengaturan Privasi</h2>
            <p class="text-gray-600 mt-1">Kelola privasi data dan informasi akun Anda</p>
        </div>
        
        <div class="p-6">
            <form action="{{ route('guru.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Profil</h3>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-800">Tampilkan email</h4>
                            <p class="text-sm text-gray-600">Email Anda akan terlihat oleh admin dan sesama guru</p>
                        </div>
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_email" class="sr-only peer" {{ isset($user->preferences['show_email']) && $user->preferences['show_email'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-800">Tampilkan nomor telepon</h4>
                            <p class="text-sm text-gray-600">Nomor telepon Anda akan terlihat oleh admin</p>
                        </div>
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_phone" class="sr-only peer" {{ isset($user->preferences['show_phone']) && $user->preferences['show_phone'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
                  <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Data dan Aktivitas</h3>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-800">Lacak aktivitas login</h4>
                            <p class="text-sm text-gray-600">Sistem akan mencatat aktivitas login Anda untuk keamanan</p>
                        </div>
                        <div class="flex items-center">
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded mr-2">Diaktifkan Sistem</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-800">Notifikasi login baru</h4>
                            <p class="text-sm text-gray-600">Dapatkan pemberitahuan saat akun Anda diakses dari perangkat baru</p>
                        </div>
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="track_login" class="sr-only peer" {{ isset($user->preferences['track_login']) && $user->preferences['track_login'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-800">Catat riwayat perubahan materi</h4>
                            <p class="text-sm text-gray-600">Sistem akan mencatat perubahan yang Anda buat pada materi</p>
                        </div>
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="track_material_changes" class="sr-only peer" {{ isset($user->preferences['track_material_changes']) && $user->preferences['track_material_changes'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
            
            <div class="mt-10 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Data Privasi</h3>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Area ini berisi tindakan sensitif yang berhubungan dengan data Anda.
                            </p>
                        </div>
                    </div>
                </div>
                  <div class="flex justify-between items-center mt-4">
                    <div>
                        <h4 class="font-medium text-gray-800">Unduh Data Saya</h4>
                        <p class="text-sm text-gray-600">Dapatkan salinan data yang kami miliki tentang Anda</p>
                    </div>
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Unduh Data
                    </button>
                </div>
                
                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
                    <div>
                        <h4 class="font-medium text-gray-800">Keamanan Akun</h4>
                        <p class="text-sm text-gray-600">Pergi ke halaman untuk mengubah kata sandi dan keamanan akun</p>
                    </div>
                    <a href="{{ route('guru.profile.show') }}#update-password" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg shadow-sm text-sm font-medium hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 inline-flex items-center">
                        <i class="fas fa-lock mr-2"></i>
                        Pengaturan Keamanan
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Kebijakan Privasi -->
    <div class="mt-6 bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-shield-alt mr-3 text-indigo-500"></i>
                Kebijakan Privasi SMAN 1 GIRSIP
            </h2>
        </div>
        <div class="p-6">
            <div class="prose max-w-none">
                <p class="text-gray-600">
                    SMAN 1 GIRSIP berkomitmen penuh untuk menjaga privasi dan keamanan data pengguna. Kami hanya mengumpulkan informasi yang diperlukan untuk pengoperasian sistem pembelajaran digital.
                </p>
                
                <h4 class="text-md font-medium text-gray-800 mt-4">Cara Kami Menggunakan Data Anda:</h4>
                <ul class="list-disc pl-5 text-gray-600">
                    <li>Memberikan akses ke sistem pembelajaran</li>
                    <li>Memfasilitasi komunikasi antara guru dan siswa</li>
                    <li>Meningkatkan pengalaman pengguna berdasarkan umpan balik</li>
                    <li>Kepatuhan terhadap peraturan pendidikan</li>
                </ul>
                
                <p class="text-gray-600 mt-4">
                    Kami tidak akan menjual atau membagikan data pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan oleh hukum.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
