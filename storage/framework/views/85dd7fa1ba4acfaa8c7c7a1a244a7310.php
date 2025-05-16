<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent('title'); ?> - SMAN 1 Girsip</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-active {
            background-color: rgba(255, 255, 255, 0.08);
        }
        
        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.04);
        }
        
        /* Sidebar section styles */
        .sidebar-section-header {
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .sidebar-section-header::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 0;
            width: 65%;
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1));
            z-index: -1;
        }
        
        /* Add smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Animation utilities */
        .animate-spin-slow {
            animation: spin 3s linear infinite;
        }
        
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Enhanced styling for dashboard pages */
        .animate-item {
            opacity: 0;
            animation: item-appear 0.5s ease forwards;
        }
        
        .counter-value {
            display: inline-block;
            position: relative;
        }
        
        .counter-value:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, currentColor, transparent);
            animation: counter-line 2s ease-in-out;
        }
        
        .animate-gradient-x {
            background-size: 300% 300%;
            animation: gradient-x 15s ease infinite;
        }
        
        .animate-fade-in {
            animation: fade-in 0.6s ease-in-out;
        }
        
        .floating-element {
            animation: floating 3s ease-in-out infinite alternate;
        }
        
        .text-shadow-sm {
            text-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollbar-thumb-gray-300::-webkit-scrollbar-thumb {
            background-color: #D1D5DB;
            border-radius: 3px;
        }
        
        .scrollbar-track-gray-100::-webkit-scrollbar-track {
            background-color: #F3F4F6;
        }
        
        @keyframes counter-line {
            0% { width: 0; left: 50%; opacity: 0; }
            50% { opacity: 1; }
            100% { width: 100%; left: 0; opacity: 0; }
        }
        
        @keyframes item-appear {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes gradient-x {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        
        @keyframes fade-in {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes floating {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-5px);
            }
        }
        
        /* Add subtle pulse animation to icons on hover */
        .dashboard-card:hover i {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        /* Card hover effects */
        .quick-action:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Better animation for cards */
        .dashboard-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Glass morphism effects */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }
        
        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ff4b4b, #ff0000);
            color: white;
            font-size: 0.65rem;
            font-weight: bold;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(255, 0, 0, 0.3);
        }
        
        /* Interactive elements */
        .btn-glass {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-glass:hover {
            background-color: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-50">
    <!-- Mobile sidebar backdrop -->
    <div class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden mobile-backdrop hidden" id="sidebarBackdrop"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full lg:translate-x-0 bg-gradient-to-b from-indigo-900 to-indigo-800 text-white overflow-y-auto scrollbar-thin scrollbar-thumb-indigo-700 scrollbar-track-indigo-900">
        <div class="p-4 flex items-center justify-between border-b border-indigo-700/50">
            <div class="flex items-center space-x-3">
                <div class="h-9 w-9 rounded-lg bg-white/10 backdrop-blur-sm shadow-inner flex items-center justify-center">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="SMAN 1 Girsip Logo" class="h-7 w-auto">
                </div>
                <div>
                    <h2 class="font-bold text-lg text-white">SMAN 1 Girsip</h2>
                    <div class="text-xs text-indigo-200 flex items-center">
                        <span class="inline-block h-2 w-2 bg-green-400 rounded-full mr-1"></span>
                        <span>E-Learning System</span>
                    </div>
                </div>
            </div>
            
            <!-- Close button for mobile -->
            <button id="closeSidebar" class="text-white p-1 rounded-md hover:bg-indigo-700 lg:hidden">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="py-4">
            <!-- Display the appropriate sidebar based on user role -->
            <?php if(auth()->user()->role->slug === 'admin'): ?>
                <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php elseif(auth()->user()->role->slug === 'guru'): ?>
                <?php echo $__env->make('guru.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php elseif(auth()->user()->role->slug === 'siswa'): ?>
                <?php echo $__env->make('siswa.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            
            <!-- User info and Logout -->
            <div class="mt-6 px-4">
                <div class="p-3 rounded-lg bg-indigo-700/30 mb-3">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-indigo-600/50 flex items-center justify-center text-white mr-3">
                            <?php if(auth()->user()->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . auth()->user()->avatar)); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="h-10 w-10 rounded-full object-cover">
                            <?php else: ?>
                                <span class="font-medium text-sm"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate"><?php echo e(auth()->user()->name); ?></p>
                            <p class="text-xs text-indigo-200 truncate"><?php echo e(auth()->user()->role->name); ?></p>
                        </div>
                    </div>
                </div>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 rounded-lg bg-indigo-950/50 hover:bg-indigo-950/70 text-indigo-100 transition-all duration-200 hover:shadow-md">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    
    <!-- Content area -->
    <div class="lg:ml-64 min-h-screen flex flex-col">
        <!-- Top bar -->
        <header class="bg-white shadow-sm sticky top-0 z-30">
            <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <!-- Hamburger menu for mobile -->
                <button id="openSidebar" class="lg:hidden text-gray-600 p-2 rounded-md hover:bg-gray-100 focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Page title -->
                <h1 class="text-xl font-semibold text-gray-800"><?php echo $__env->yieldContent('header'); ?></h1>
                
                <!-- User dropdown -->
                <div class="relative" id="userDropdown">
                    <button class="flex items-center space-x-3 focus:outline-none" id="userDropdownButton">
                        <div class="hidden sm:block text-right">
                            <div class="text-sm font-medium text-gray-700"><?php echo e(auth()->user()->name); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e(auth()->user()->role->name); ?></div>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white">
                            <?php if(auth()->user()->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . auth()->user()->avatar)); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="h-10 w-10 rounded-full object-cover">
                            <?php else: ?>
                                <span class="font-medium text-sm"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs hidden sm:block"></i>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden" id="userDropdownMenu">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2 text-gray-500"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main content -->
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
            <div class="animate-fade-in">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; <?php echo e(date('Y')); ?> SMAN 1 Girsip. All rights reserved.</p>
            </div>
        </footer>
    </div>
    
    <!-- Scripts -->
    <script>
        // Dropdowns
        document.getElementById('userDropdownButton').addEventListener('click', function() {
            document.getElementById('userDropdownMenu').classList.toggle('hidden');
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const userDropdown = document.getElementById('userDropdown');
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            
            if (!userDropdown.contains(event.target)) {
                userDropdownMenu.classList.add('hidden');
            }
        });
        
        // Mobile sidebar
        document.getElementById('openSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebarBackdrop').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
        
        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarBackdrop').classList.add('hidden');
            document.body.style.overflow = 'auto';
        });
        
        document.getElementById('sidebarBackdrop').addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarBackdrop').classList.add('hidden');
            document.body.style.overflow = 'auto';
        });
        
        document.addEventListener("DOMContentLoaded", function() {
            // Create floating particles effect for banners
            const particlesContainers = document.querySelectorAll('.particles-container');
            if (particlesContainers.length > 0) {
                particlesContainers.forEach(container => {
                    createParticles(container);
                });
            }
            
            // Initialize counter animations
            initializeCounters();
            
            function createParticles(container) {
                for (let i = 0; i < 30; i++) {
                    const particle = document.createElement('div');
                    
                    // Style the particle
                    particle.style.position = 'absolute';
                    particle.style.width = Math.random() * 5 + 2 + 'px';
                    particle.style.height = particle.style.width;
                    particle.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
                    particle.style.borderRadius = '50%';
                    particle.style.pointerEvents = 'none';
                    
                    // Position the particle randomly
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.top = Math.random() * 100 + '%';
                    
                    // Set animation properties
                    particle.style.opacity = Math.random() * 0.5 + 0.1;
                    const animationDuration = Math.random() * 15 + 10 + 's';
                    const animationDelay = Math.random() * 5 + 's';
                    
                    // Apply animation
                    particle.style.animation = `floatingParticle ${animationDuration} ease-in-out ${animationDelay} infinite alternate`;
                    
                    // Add particle to container
                    container.appendChild(particle);
                }
            }
            
            function initializeCounters() {
                document.querySelectorAll('.counter-value').forEach(counter => {
                    const value = parseInt(counter.textContent.replace(/,/g, ''), 10);
                    if (isNaN(value)) return;
                    
                    counter.setAttribute('data-value', value);
                    counter.textContent = '0';
                    
                    setTimeout(() => {
                        const duration = 1500;
                        const steps = 20;
                        const stepValue = value / steps;
                        const stepTime = duration / steps;
                        let currentStep = 0;
                        
                        const interval = setInterval(() => {
                            currentStep++;
                            const currentValue = Math.ceil(Math.min(stepValue * currentStep, value));
                            counter.textContent = currentValue.toLocaleString();
                            
                            if (currentStep >= steps) {
                                clearInterval(interval);
                            }
                        }, stepTime);
                    }, 300);
                });
            }
            
            // Add animation keyframes 
            const style = document.createElement('style');
            style.innerHTML = `
                @keyframes floatingParticle {
                    0% {
                        transform: translate(0, 0) rotate(0deg);
                    }
                    25% {
                        transform: translate(${Math.random() * 30 - 15}px, ${Math.random() * 30 - 15}px) rotate(${Math.random() * 360}deg);
                    }
                    50% {
                        transform: translate(${Math.random() * 30 - 15}px, ${Math.random() * 30 - 15}px) rotate(${Math.random() * 360}deg);
                    }
                    75% {
                        transform: translate(${Math.random() * 30 - 15}px, ${Math.random() * 30 - 15}px) rotate(${Math.random() * 360}deg);
                    }
                    100% {
                        transform: translate(0, 0) rotate(0deg);
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
    
    <!-- User Management JS -->
    <script src="<?php echo e(asset('js/user-management.js')); ?>"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\SEMUA TENTANG KULIAH\SEMESTER 4\PA2\IMPLEMENTASI NEW\sman1-girsip\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>