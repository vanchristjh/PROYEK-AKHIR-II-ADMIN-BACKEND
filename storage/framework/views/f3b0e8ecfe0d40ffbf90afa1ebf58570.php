<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - SMAN 1 GIRSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        .float-animation {
            animation: float 5s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">
    <div class="max-w-md w-full mx-auto">
        <div class="text-center mb-8 float-animation">
            <div class="inline-flex items-center justify-center h-24 w-24 bg-blue-100 text-blue-600 rounded-full">
                <i class="fas fa-search text-5xl"></i>
            </div>
        </div>
        
        <div class="glass-card rounded-xl p-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Halaman Tidak Ditemukan</h1>
            <p class="text-gray-600 mb-8">Halaman yang Anda cari tidak tersedia. Silakan kembali ke halaman beranda atau logout.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="<?php echo e(auth()->check() ? 
                    (auth()->user()->hasRole('admin') ? route('admin.dashboard') : 
                    (auth()->user()->hasRole('guru') ? route('guru.dashboard') : 
                    (auth()->user()->hasRole('siswa') ? route('siswa.dashboard') : 
                    route('login')))) : route('login')); ?>"
                    class="py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center font-medium transition-colors">
                    <i class="fas fa-home mr-2"></i> Kembali ke Dashboard
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="py-3 px-4 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg flex items-center justify-center font-medium transition-colors w-full">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        
        <p class="text-center text-gray-500 text-sm mt-8">
            &copy; <?php echo e(date('Y')); ?> SMAN 1 Girsip. Sistem Informasi Akademik
        </p>
    </div>
</body>
</html>
<?php /**PATH D:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip\resources\views/errors/404.blade.php ENDPATH**/ ?>