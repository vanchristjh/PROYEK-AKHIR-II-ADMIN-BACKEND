@extends('layouts.guru')

@section('title', 'Tutorial Pengajaran')
@section('header', 'Tutorial Pengajaran')

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
                    <a href="{{ route('guru.help') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Pusat Bantuan</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                    <span class="text-sm font-medium text-indigo-600">Tutorial Pengajaran</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-white to-blue-50">
            <h2 class="text-2xl font-semibold text-gray-800">Tutorial Pengajaran</h2>
            <p class="text-gray-600 mt-1">Panduan langkah demi langkah untuk meningkatkan efektivitas pengajaran</p>
        </div>
        
        <div class="p-6">
            <!-- Tutorial Navigation -->
            <div class="flex flex-wrap gap-2 mb-6 bg-gray-50 p-4 rounded-lg overflow-x-auto whitespace-nowrap">
                <a href="#material-creation" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">Pembuatan Materi</a>
                <a href="#assignment-management" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">Pengelolaan Tugas</a>
                <a href="#grading" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">Penilaian</a>
                <a href="#classroom-management" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">Manajemen Kelas</a>
                <a href="#student-engagement" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">Keterlibatan Siswa</a>
            </div>
            
            <!-- Tutorial Sections -->
            <div class="space-y-10">
                <!-- Material Creation -->
                <div id="material-creation" class="scroll-mt-20">
                    <div class="border-l-4 border-blue-500 pl-4 mb-4">
                        <h3 class="text-xl font-medium text-gray-900">Pembuatan Materi Pembelajaran</h3>
                        <p class="text-gray-600">Cara membuat dan mengatur materi pembelajaran yang efektif</p>
                    </div>
                    
                    <div class="prose max-w-none">
                        <h4>Menyiapkan Materi Pembelajaran yang Menarik</h4>
                        <p>Materi pembelajaran yang efektif harus dirancang dengan mempertimbangkan kebutuhan siswa dan tujuan pembelajaran. Berikut langkah-langkah untuk menyiapkan materi yang menarik:</p>
                        
                        <ol>
                            <li><strong>Tentukan tujuan pembelajaran</strong> - Pastikan tujuan pembelajaran jelas dan dapat diukur.</li>
                            <li><strong>Susun konten dengan struktur yang jelas</strong> - Gunakan heading, subheading, dan bullet points untuk mengorganisasi informasi.</li>
                            <li><strong>Sertakan visual yang mendukung</strong> - Tambahkan gambar, grafik, atau diagram untuk memperjelas konsep.</li>
                            <li><strong>Berikan contoh kontekstual</strong> - Kaitkan materi dengan situasi dunia nyata yang relevan bagi siswa.</li>
                            <li><strong>Sediakan latihan interaktif</strong> - Tambahkan quiz atau pertanyaan refleksi untuk mengukur pemahaman.</li>
                        </ol>
                        
                        <h4>Cara Mengunggah Materi ke Sistem</h4>
                        <p>Untuk mengunggah materi pembelajaran ke sistem, ikuti langkah-langkah berikut:</p>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                            <h5 class="font-medium mb-2">Langkah 1: Persiapan File</h5>
                            <p>Siapkan file materi dalam format yang didukung (PDF, DOCX, PPTX, MP4, dll). Pastikan ukuran file tidak melebihi 50MB.</p>
                            
                            <h5 class="font-medium mt-4 mb-2">Langkah 2: Navigasi ke Halaman Materi</h5>
                            <p>Buka menu <a href="{{ route('guru.materials.index') }}" class="text-blue-600 hover:underline">Materi</a> dan klik tombol "Tambah Materi Baru".</p>
                            
                            <h5 class="font-medium mt-4 mb-2">Langkah 3: Isi Formulir</h5>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Masukkan judul materi</li>
                                <li>Pilih mata pelajaran terkait</li>
                                <li>Pilih kelas yang dapat mengakses</li>
                                <li>Berikan deskripsi singkat tentang materi</li>
                                <li>Unggah file materi</li>
                                <li>Tetapkan tanggal publikasi (opsional)</li>
                            </ul>
                            
                            <h5 class="font-medium mt-4 mb-2">Langkah 4: Publikasikan</h5>
                            <p>Klik tombol "Simpan" untuk mempublikasikan materi. Materi akan segera tersedia untuk siswa sesuai pengaturan yang dipilih.</p>
                        </div>
                        
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <h5 class="font-medium mb-2 flex items-center">
                                <i class="fas fa-lightbulb text-blue-500 mr-2"></i>
                                Tips Praktis
                            </h5>
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                <li>Gunakan nama file yang deskriptif untuk memudahkan pencarian</li>
                                <li>Tambahkan tag/kategori untuk membantu siswa menemukan materi dengan mudah</li>
                                <li>Sertakan poin-poin penting atau ringkasan dalam deskripsi materi</li>
                                <li>Gunakan format yang konsisten untuk semua materi pembelajaran Anda</li>
                                <li>Perbarui materi secara berkala untuk memastikan kontennya tetap relevan</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Assignment Management -->
                <div id="assignment-management" class="scroll-mt-20">
                    <div class="border-l-4 border-green-500 pl-4 mb-4">
                        <h3 class="text-xl font-medium text-gray-900">Pengelolaan Tugas</h3>
                        <p class="text-gray-600">Panduan membuat, mendistribusikan, dan menilai tugas</p>
                    </div>
                    
                    <div class="prose max-w-none">
                        <p>Pengelolaan tugas yang efektif membantu Anda mengukur pemahaman siswa dan memberikan umpan balik yang konstruktif. Sistem ini memudahkan Anda untuk membuat, mendistribusikan, dan menilai tugas secara efisien.</p>
                        
                        <h4>Membuat Tugas Baru</h4>
                        <p>Untuk membuat tugas baru, akses menu <a href="{{ route('guru.assignments.index') }}" class="text-blue-600 hover:underline">Tugas</a> dan ikuti panduan berikut:</p>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                            <ol class="list-decimal pl-5 space-y-3">
                                <li>
                                    <strong>Klik "Tambah Tugas Baru"</strong>
                                    <p class="text-sm text-gray-600">Tombol ini berada di pojok kanan atas halaman tugas.</p>
                                </li>
                                <li>
                                    <strong>Isi informasi tugas</strong>
                                    <ul class="list-disc pl-5 mt-1 space-y-1 text-sm text-gray-600">
                                        <li>Judul tugas yang deskriptif</li>
                                        <li>Petunjuk/instruksi yang jelas</li>
                                        <li>Mata pelajaran terkait</li>
                                        <li>Kelas yang dituju</li>
                                        <li>Tanggal mulai</li>
                                        <li>Tenggat waktu pengumpulan</li>
                                        <li>Poin/nilai maksimum</li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Lampirkan file pendukung (opsional)</strong>
                                    <p class="text-sm text-gray-600">Unggah file template, petunjuk tambahan, atau materi referensi.</p>
                                </li>
                                <li>
                                    <strong>Pilih opsi tambahan</strong>
                                    <ul class="list-disc pl-5 mt-1 space-y-1 text-sm text-gray-600">
                                        <li>Izinkan pengumpulan terlambat</li>
                                        <li>Aktifkan deteksi plagiarisme</li>
                                        <li>Tetapkan nilai minimum untuk lulus</li>
                                        <li>Tampilkan/sembunyikan nilai setelah penilaian</li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Publikasikan tugas</strong>
                                    <p class="text-sm text-gray-600">Klik "Simpan" untuk membuat tugas dan membuatnya terlihat oleh siswa sesuai pengaturan yang dipilih.</p>
                                </li>
                            </ol>
                        </div>
                        
                        <h4>Memeriksa Pengumpulan Tugas</h4>
                        <p>Langkah-langkah untuk memeriksa dan menilai tugas yang dikumpulkan:</p>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <ol class="list-decimal pl-5 space-y-3">
                                <li>
                                    <strong>Akses halaman detail tugas</strong>
                                    <p class="text-sm text-gray-600">Klik pada tugas yang ingin Anda periksa pengumpulannya.</p>
                                </li>
                                <li>
                                    <strong>Buka tab "Pengumpulan"</strong>
                                    <p class="text-sm text-gray-600">Di sini Anda dapat melihat daftar siswa dan status pengumpulan mereka.</p>
                                </li>
                                <li>
                                    <strong>Unduh atau periksa langsung</strong>
                                    <ul class="list-disc pl-5 mt-1 space-y-1 text-sm text-gray-600">
                                        <li>Klik "Lihat" untuk memeriksa pengumpulan langsung di browser (untuk PDF)</li>
                                        <li>Klik "Unduh" untuk mengunduh file pengumpulan</li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Berikan nilai dan umpan balik</strong>
                                    <p class="text-sm text-gray-600">Masukkan nilai dan berikan komentar atau umpan balik yang konstruktif.</p>
                                </li>
                                <li>
                                    <strong>Simpan penilaian</strong>
                                    <p class="text-sm text-gray-600">Klik "Simpan" untuk merekam nilai dan umpan balik Anda.</p>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                  <!-- More tutorial sections can be added following the same pattern -->
                
                <!-- Back to Help Home -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-wrap justify-between items-center">
                        <a href="{{ route('guru.help') }}" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Pusat Bantuan
                        </a>
                        
                        <div class="mt-4 md:mt-0">
                            <p class="text-sm text-gray-600">Apakah tutorial ini membantu?</p>
                            <div class="mt-2 flex space-x-2">
                                <button class="px-3 py-1 bg-green-50 text-green-700 rounded hover:bg-green-100 transition-colors">
                                    <i class="far fa-thumbs-up mr-1"></i> Ya
                                </button>
                                <button class="px-3 py-1 bg-red-50 text-red-700 rounded hover:bg-red-100 transition-colors">
                                    <i class="far fa-thumbs-down mr-1"></i> Tidak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80, // Adjust for header height
                        behavior: 'smooth'
                    });
                    
                    // Update URL hash without scrolling
                    history.pushState(null, null, targetId);
                }
            });
        });
        
        // Highlight current section based on scroll position
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            
            document.querySelectorAll('div[id]').forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionBottom = sectionTop + section.offsetHeight;
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                    const id = section.getAttribute('id');
                    document.querySelectorAll('.flex.flex-wrap.gap-2 a').forEach(link => {
                        link.classList.remove('bg-blue-500', 'text-white');
                        link.classList.add('bg-blue-100', 'text-blue-700');
                        
                        if (link.getAttribute('href') === `#${id}`) {
                            link.classList.remove('bg-blue-100', 'text-blue-700');
                            link.classList.add('bg-blue-500', 'text-white');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
