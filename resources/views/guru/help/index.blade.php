@extends('layouts.guru')

@section('title', 'Bantuan')
@section('header', 'Pusat Bantuan')

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
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                    <span class="text-sm font-medium text-indigo-600">Pusat Bantuan</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-white to-blue-50">
            <h2 class="text-2xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-question-circle text-blue-500 mr-3"></i>
                Pusat Bantuan
            </h2>
            <p class="text-gray-600 mt-1">Temukan jawaban untuk pertanyaan umum dan panduan penggunaan sistem</p>
        </div>
        
        <div class="p-6">
            <!-- Search Bar -->
            <div class="mb-8">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="help-search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Cari bantuan...">
                </div>
            </div>
            
            <!-- Help Categories -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                        <i class="fas fa-book text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Panduan Mengajar</h3>
                    <p class="text-sm text-gray-600 mb-4">Pelajari cara mengelola kelas, materi, dan tugas</p>
                    <a href="{{ route('guru.help.tutorial') }}#material-creation" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat panduan <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                
                <div class="bg-green-50 rounded-lg p-6 border border-green-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-4">
                        <i class="fas fa-graduation-cap text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Kelola Materi</h3>
                    <p class="text-sm text-gray-600 mb-4">Cara mengunggah, mengatur dan membagikan materi pelajaran</p>
                    <a href="{{ route('guru.help.tutorial') }}#material-creation" class="text-green-600 hover:text-green-800 text-sm font-medium">Lihat panduan <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                
                <div class="bg-purple-50 rounded-lg p-6 border border-purple-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mb-4">
                        <i class="fas fa-clipboard-check text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pemberian Tugas</h3>
                    <p class="text-sm text-gray-600 mb-4">Mengelola pemberian dan penilaian tugas</p>
                    <a href="{{ route('guru.help.tutorial') }}#assignment-management" class="text-purple-600 hover:text-purple-800 text-sm font-medium">Lihat panduan <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
            
            <!-- FAQs -->
            <div class="mb-8">
                <h3 class="text-xl font-medium text-gray-900 mb-4">Pertanyaan Umum</h3>
                
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-md overflow-hidden">
                        <button class="faq-question w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 flex justify-between items-center" data-target="faq-1">
                            <span class="font-medium">Bagaimana cara mengubah profil saya?</span>
                            <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                        </button>
                        <div id="faq-1" class="faq-answer px-4 py-3 bg-white hidden">
                            <p class="text-gray-600">Untuk mengubah profil Anda:</p>
                            <ol class="list-decimal list-inside mt-2 text-gray-600 space-y-2">
                                <li>Buka halaman <a href="{{ route('guru.profile.show') }}" class="text-blue-600 hover:underline">Profil</a></li>
                                <li>Klik tombol "Edit Profil" di bagian atas halaman</li>
                                <li>Ubah informasi yang diinginkan</li>
                                <li>Klik "Simpan Perubahan"</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-md overflow-hidden">
                        <button class="faq-question w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 flex justify-between items-center" data-target="faq-2">
                            <span class="font-medium">Bagaimana cara mengunggah materi pelajaran?</span>
                            <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                        </button>
                        <div id="faq-2" class="faq-answer px-4 py-3 bg-white hidden">
                            <p class="text-gray-600">Untuk mengunggah materi pelajaran baru:</p>
                            <ol class="list-decimal list-inside mt-2 text-gray-600 space-y-2">
                                <li>Buka menu <a href="{{ route('guru.materials.index') }}" class="text-blue-600 hover:underline">Materi</a></li>
                                <li>Klik tombol "Tambah Materi"</li>
                                <li>Isi formulir dengan informasi materi yang diperlukan</li>
                                <li>Unggah file materi (PDF, DOCX, PPTX, dll)</li>
                                <li>Pilih kelas yang dapat mengakses materi tersebut</li>
                                <li>Klik "Simpan" untuk menyelesaikan</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-md overflow-hidden">
                        <button class="faq-question w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 flex justify-between items-center" data-target="faq-3">
                            <span class="font-medium">Bagaimana cara melihat tugas yang telah dikumpulkan siswa?</span>
                            <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                        </button>
                        <div id="faq-3" class="faq-answer px-4 py-3 bg-white hidden">
                            <p class="text-gray-600">Untuk melihat tugas yang telah dikumpulkan siswa:</p>
                            <ol class="list-decimal list-inside mt-2 text-gray-600 space-y-2">
                                <li>Buka menu <a href="{{ route('guru.assignments.index') }}" class="text-blue-600 hover:underline">Tugas</a></li>
                                <li>Pilih tugas yang ingin Anda lihat pengumpulannya</li>
                                <li>Klik tombol "Lihat Pengumpulan" atau tab "Pengumpulan"</li>
                                <li>Di sini Anda dapat melihat daftar siswa yang telah mengumpulkan beserta file pengumpulannya</li>
                                <li>Anda dapat mengunduh, memeriksa, dan memberikan nilai pada setiap pengumpulan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Support -->
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Butuh bantuan lebih lanjut?</h3>
                <p class="text-gray-600 mb-4">Jika Anda memiliki pertanyaan atau masalah yang tidak tercakup dalam panduan ini, silakan hubungi tim dukungan kami.</p>
                <div class="flex space-x-4">
                    <a href="mailto:support@sman1-girsip.sch.id" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-envelope mr-2 text-gray-500"></i>
                        Email Support
                    </a>
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-comments mr-2"></i>
                        Live Chat
                    </a>
                </div>            </div>
        </div>
    </div>
    
    <!-- Contact Support -->
    <div class="mt-8 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-xl shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="p-8 md:w-2/3">
                <h3 class="text-xl font-bold text-white mb-2">Masih membutuhkan bantuan?</h3>
                <p class="text-indigo-100 mb-6">Tim dukungan kami siap membantu Anda dengan pertanyaan atau masalah yang Anda hadapi.</p>
                <div class="space-y-3 md:space-y-0 md:space-x-3 md:flex">
                    <a href="mailto:support@sman1girsip.edu" class="w-full md:w-auto inline-flex justify-center items-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 shadow-sm">
                        <i class="fas fa-envelope mr-2"></i>
                        Kontak Email
                    </a>
                    <a href="#" class="w-full md:w-auto inline-flex justify-center items-center px-5 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-indigo-700 hover:bg-opacity-30">
                        <i class="fas fa-phone-alt mr-2"></i>
                        021-555-7890
                    </a>
                </div>
            </div>
            <div class="hidden md:block md:w-1/3">
                <img src="{{ asset('assets/support-team.svg') }}" alt="Support Team" class="h-full w-full object-cover" onerror="this.src='https://via.placeholder.com/400x300?text=Tim+Dukungan'; this.onerror=null;">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ toggle functionality
        const faqQuestions = document.querySelectorAll('.faq-question');
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const answer = document.getElementById(targetId);
                
                // Toggle answer visibility
                answer.classList.toggle('hidden');
                
                // Toggle icon rotation
                const icon = this.querySelector('.fa-chevron-down');
                icon.classList.toggle('transform');
                icon.classList.toggle('rotate-180');
            });
        });
        
        // Help search functionality
        const searchInput = document.getElementById('help-search');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const helpTexts = document.querySelectorAll('.faq-question, .faq-answer p, .faq-answer li');
                
                if (searchTerm.length > 2) {
                    helpTexts.forEach(element => {
                        const content = element.textContent.toLowerCase();
                        const parent = element.closest('.border');
                        
                        if (content.includes(searchTerm)) {
                            parent.classList.add('bg-yellow-50');
                            parent.classList.add('border-yellow-200');
                            
                            // If it's in the answer, make sure it's visible
                            if (element.closest('.faq-answer')) {
                                element.closest('.faq-answer').classList.remove('hidden');
                                const questionIcon = parent.querySelector('.fa-chevron-down');
                                questionIcon.classList.add('transform', 'rotate-180');
                            }
                        } else {
                            if (!Array.from(parent.querySelectorAll('.faq-question, .faq-answer p, .faq-answer li'))
                                    .some(el => el.textContent.toLowerCase().includes(searchTerm))) {
                                parent.classList.remove('bg-yellow-50');
                                parent.classList.remove('border-yellow-200');
                            }
                        }
                    });
                } else {
                    document.querySelectorAll('.border').forEach(el => {
                        el.classList.remove('bg-yellow-50');
                        el.classList.remove('border-yellow-200');
                    });
                }
            });
        }
    });
</script>
@endsection
