@extends('layouts.dashboard')

@section('page-title', 'Pengaturan Tampilan')

@section('dashboard-content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Menu Pengaturan</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('settings.account') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-user me-3"></i> Akun
                    </a>
                    <a href="{{ route('settings.notifications') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-bell me-3"></i> Notifikasi
                    </a>
                    <a href="{{ route('settings.appearance') }}" class="list-group-item list-group-item-action d-flex align-items-center active">
                        <i class="bx bx-palette me-3"></i> Tampilan
                    </a>
                    <a href="{{ route('settings.system') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bx bx-cog me-3"></i> Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        @if (session('success'))
            <div class="alert alert-success mb-4 d-flex align-items-center">
                <i class="bx bx-check-circle fs-4 me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger mb-4 d-flex align-items-center">
                <i class="bx bx-error-circle fs-4 me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">Tema Aplikasi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update-appearance') }}" method="POST" id="appearanceForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Add debug field to track submission -->
                    <input type="hidden" name="submission_time" value="{{ now()->timestamp }}">
                    
                    <div class="mb-4">
                        <label class="form-label mb-3">Mode Tampilan</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check appearance-option border rounded p-3">
                                    <input class="form-check-input" type="radio" name="theme_mode" id="light_mode" value="light" checked>
                                    <label class="form-check-label w-100" for="light_mode">
                                        <div class="text-center mb-2">
                                            <div class="theme-preview bg-white border mb-2" style="height: 80px; position: relative;">
                                                <div class="bg-primary" style="height: 15px; width: 100%;"></div>
                                                <div style="position: absolute; left: 10px; top: 25px; width: 30%; height: 10px; background: #e9ecef;"></div>
                                                <div style="position: absolute; left: 10px; top: 45px; width: 40%; height: 10px; background: #e9ecef;"></div>
                                                <div style="position: absolute; left: 10px; top: 65px; width: 20%; height: 10px; background: #e9ecef;"></div>
                                            </div>
                                            <h6 class="mb-0">Mode Terang</h6>
                                            <small class="text-muted">Tampilan default</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check appearance-option border rounded p-3">
                                    <input class="form-check-input" type="radio" name="theme_mode" id="dark_mode" value="dark">
                                    <label class="form-check-label w-100" for="dark_mode">
                                        <div class="text-center mb-2">
                                            <div class="theme-preview bg-dark border mb-2" style="height: 80px; position: relative;">
                                                <div class="bg-primary" style="height: 15px; width: 100%;"></div>
                                                <div style="position: absolute; left: 10px; top: 25px; width: 30%; height: 10px; background: #343a40;"></div>
                                                <div style="position: absolute; left: 10px; top: 45px; width: 40%; height: 10px; background: #343a40;"></div>
                                                <div style="position: absolute; left: 10px; top: 65px; width: 20%; height: 10px; background: #343a40;"></div>
                                            </div>
                                            <h6 class="mb-0">Mode Gelap</h6>
                                            <small class="text-muted">Lebih nyaman di malam hari</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check appearance-option border rounded p-3">
                                    <input class="form-check-input" type="radio" name="theme_mode" id="auto_mode" value="auto">
                                    <label class="form-check-label w-100" for="auto_mode">
                                        <div class="text-center mb-2">
                                            <div class="theme-preview mb-2" style="height: 80px; position: relative; background: linear-gradient(to right, white 50%, #212529 50%);">
                                                <div style="height: 15px; width: 100%; background: linear-gradient(to right, var(--primary) 50%, var(--primary) 50%);"></div>
                                                <div style="position: absolute; left: 10px; top: 25px; width: 30%; height: 10px; background: #e9ecef;"></div>
                                                <div style="position: absolute; left: 10px; top: 45px; width: 40%; height: 10px; background: #e9ecef;"></div>
                                                <div style="position: absolute; right: 10px; top: 25px; width: 30%; height: 10px; background: #343a40;"></div>
                                                <div style="position: absolute; right: 10px; top: 45px; width: 40%; height: 10px; background: #343a40;"></div>
                                            </div>
                                            <h6 class="mb-0">Otomatis</h6>
                                            <small class="text-muted">Ikuti pengaturan sistem</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label mb-3">Warna Utama</label>
                        <div class="row g-3">
                            <div class="col-md-2 col-4">
                                <div class="form-check appearance-option border rounded p-2">
                                    <input class="form-check-input" type="radio" name="primary_color" id="color_blue" value="blue" checked>
                                    <label class="form-check-label w-100" for="color_blue">
                                        <div class="text-center">
                                            <div class="color-preview rounded-circle mx-auto mb-2" style="background-color: #0066b3; width: 40px; height: 40px;"></div>
                                            <span class="small d-block">Biru</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 col-4">
                                <div class="form-check appearance-option border rounded p-2">
                                    <input class="form-check-input" type="radio" name="primary_color" id="color_green" value="green">
                                    <label class="form-check-label w-100" for="color_green">
                                        <div class="text-center">
                                            <div class="color-preview rounded-circle mx-auto mb-2" style="background-color: #28a745; width: 40px; height: 40px;"></div>
                                            <span class="small d-block">Hijau</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 col-4">
                                <div class="form-check appearance-option border rounded p-2">
                                    <input class="form-check-input" type="radio" name="primary_color" id="color_purple" value="purple">
                                    <label class="form-check-label w-100" for="color_purple">
                                        <div class="text-center">
                                            <div class="color-preview rounded-circle mx-auto mb-2" style="background-color: #6f42c1; width: 40px; height: 40px;"></div>
                                            <span class="small d-block">Ungu</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 col-4">
                                <div class="form-check appearance-option border rounded p-2">
                                    <input class="form-check-input" type="radio" name="primary_color" id="color_red" value="red">
                                    <label class="form-check-label w-100" for="color_red">
                                        <div class="text-center">
                                            <div class="color-preview rounded-circle mx-auto mb-2" style="background-color: #dc3545; width: 40px; height: 40px;"></div>
                                            <span class="small d-block">Merah</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 col-4">
                                <div class="form-check appearance-option border rounded p-2">
                                    <input class="form-check-input" type="radio" name="primary_color" id="color_orange" value="orange">
                                    <label class="form-check-label w-100" for="color_orange">
                                        <div class="text-center">
                                            <div class="color-preview rounded-circle mx-auto mb-2" style="background-color: #fd7e14; width: 40px; height: 40px;"></div>
                                            <span class="small d-block">Oranye</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 col-4">
                                <div class="form-check appearance-option border rounded p-2">
                                    <input class="form-check-input" type="radio" name="primary_color" id="color_teal" value="teal">
                                    <label class="form-check-label w-100" for="color_teal">
                                        <div class="text-center">
                                            <div class="color-preview rounded-circle mx-auto mb-2" style="background-color: #20c997; width: 40px; height: 40px;"></div>
                                            <span class="small d-block">Teal</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Tata Letak Sidebar</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check appearance-option border rounded p-3">
                                    <input class="form-check-input" type="radio" name="sidebar_layout" id="fixed_sidebar" value="fixed" checked>
                                    <label class="form-check-label w-100" for="fixed_sidebar">
                                        <div class="d-flex align-items-center">
                                            <div class="layout-preview me-3" style="width: 80px; height: 60px; background: #f8f9fa; position: relative;">
                                                <div style="position: absolute; left: 0; top: 0; width: 20px; height: 100%; background: var(--primary);"></div>
                                                <div style="position: absolute; left: 25px; top: 10px; width: 45px; height: 5px; background: #dee2e6;"></div>
                                                <div style="position: absolute; left: 25px; top: 20px; width: 35px; height: 5px; background: #dee2e6;"></div>
                                                <div style="position: absolute; left: 25px; top: 30px; width: 40px; height: 5px; background: #dee2e6;"></div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Fixed Sidebar</h6>
                                                <small class="text-muted">Sidebar selalu terlihat</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check appearance-option border rounded p-3">
                                    <input class="form-check-input" type="radio" name="sidebar_layout" id="collapsed_sidebar" value="collapsed">
                                    <label class="form-check-label w-100" for="collapsed_sidebar">
                                        <div class="d-flex align-items-center">
                                            <div class="layout-preview me-3" style="width: 80px; height: 60px; background: #f8f9fa; position: relative;">
                                                <div style="position: absolute; left: 0; top: 0; width: 10px; height: 100%; background: var(--primary);"></div>
                                                <div style="position: absolute; left: 15px; top: 10px; width: 55px; height: 5px; background: #dee2e6;"></div>
                                                <div style="position: absolute; left: 15px; top: 20px; width: 45px; height: 5px; background: #dee2e6;"></div>
                                                <div style="position: absolute; left: 15px; top: 30px; width: 50px; height: 5px; background: #dee2e6;"></div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">Collapsed Sidebar</h6>
                                                <small class="text-muted">Sidebar minimal</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="font_size" class="form-label">Ukuran Font</label>
                        <select class="form-select" id="font_size" name="font_size">
                            <option value="small">Kecil</option>
                            <option value="medium" selected>Sedang (Default)</option>
                            <option value="large">Besar</option>
                        </select>
                        <div class="form-text">Mengubah ukuran font dapat mempengaruhi tata letak halaman</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Pratinjau Tema</label>
                        <div class="card border">
                            <div class="card-body">
                                <div class="preview-panel bg-white p-3 border mb-3" id="themePreview">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary rounded me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bx bx-palette fs-3 text-white"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1 theme-preview-title">Judul Konten</h5>
                                            <p class="mb-0 small text-muted">Deskripsi contoh untuk melihat tampilan tema</p>
                                        </div>
                                    </div>
                                    <p class="theme-preview-text">Ini adalah contoh teks paragraf. Tampilan ini akan berubah sesuai dengan pengaturan tema yang Anda pilih. Anda dapat melihat bagaimana warna, ukuran teks, dan elemen lain akan terlihat.</p>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 mb-3">
                                                <h6 class="theme-preview-subtitle">Fitur Utama</h6>
                                                <ul class="mb-0">
                                                    <li>Kustomisasi tampilan</li>
                                                    <li>Opsi tema terang dan gelap</li>
                                                    <li>Pilihan warna yang beragam</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bg-light rounded p-3 mb-3">
                                                <h6 class="theme-preview-subtitle">Statistik</h6>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Progres:</span>
                                                    <span>75%</span>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-2">
                                        <button type="button" class="btn btn-primary me-2">Tombol Utama</button>
                                        <button type="button" class="btn btn-outline-secondary">Tombol Sekunder</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary" id="saveButton">
                            <i class="bx bx-save me-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
                
                <!-- Include debug helper (hidden by default) -->
                @include('dashboard.debug-helper')
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .appearance-option {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .appearance-option:hover {
        border-color: var(--primary) !important;
        background-color: rgba(0, 102, 179, 0.05);
    }
    .appearance-option .form-check-input {
        margin-top: 0;
        float: none;
    }
    .form-check-input:checked + .form-check-label .color-preview,
    .form-check-input:checked + .form-check-label .theme-preview,
    .form-check-input:checked + .form-check-label .layout-preview {
        box-shadow: 0 0 0 2px var(--primary);
    }
    .form-check-input:checked + .form-check-label {
        font-weight: 500;
    }
    .highlight-change {
        animation: highlight-pulse 3s ease-in-out;
    }
    
    @keyframes highlight-pulse {
        0%, 100% { 
            background-color: transparent; 
        }
        20%, 80% { 
            background-color: rgba(var(--primary-rgb), 0.1); 
            border-color: var(--primary) !important;
        }
    }
    
    /* Add primary color with RGB for opacity support */
    :root {
        --primary-rgb: 0, 102, 179;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Theme mode switcher preview
        const themeRadios = document.querySelectorAll('input[name="theme_mode"]');
        const themePreview = document.getElementById('themePreview');
        
        themeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'dark') {
                    themePreview.classList.remove('bg-white');
                    themePreview.classList.add('bg-dark', 'text-white');
                    themePreview.querySelectorAll('.text-muted').forEach(el => {
                        el.classList.remove('text-muted');
                        el.classList.add('text-light');
                    });
                    themePreview.querySelectorAll('.bg-light').forEach(el => {
                        el.classList.remove('bg-light');
                        el.classList.add('bg-secondary');
                    });
                } else {
                    themePreview.classList.remove('bg-dark', 'text-white');
                    themePreview.classList.add('bg-white');
                    themePreview.querySelectorAll('.text-light').forEach(el => {
                        el.classList.remove('text-light');
                        el.classList.add('text-muted');
                    });
                    themePreview.querySelectorAll('.bg-secondary').forEach(el => {
                        el.classList.remove('bg-secondary');
                        el.classList.add('bg-light');
                    });
                }
            });
        });
        
        // Primary color switcher
        const colorRadios = document.querySelectorAll('input[name="primary_color"]');
        
        colorRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                let primaryColor;
                
                switch(this.value) {
                    case 'blue': primaryColor = '#0066b3'; break;
                    case 'green': primaryColor = '#28a745'; break;
                    case 'purple': primaryColor = '#6f42c1'; break;
                    case 'red': primaryColor = '#dc3545'; break;
                    case 'orange': primaryColor = '#fd7e14'; break;
                    case 'teal': primaryColor = '#20c997'; break;
                    default: primaryColor = '#0066b3';
                }
                
                document.documentElement.style.setProperty('--primary', primaryColor);
                
                // Update button and progress bar colors
                document.querySelectorAll('.btn-primary').forEach(btn => {
                    btn.style.backgroundColor = primaryColor;
                    btn.style.borderColor = primaryColor;
                });
                
                document.querySelectorAll('.progress-bar').forEach(bar => {
                    bar.style.backgroundColor = primaryColor;
                });
            });
        });
        
        // Font size changer
        const fontSizeSelector = document.getElementById('font_size');
        
        fontSizeSelector.addEventListener('change', function() {
            const previewPanel = document.querySelector('.preview-panel');
            
            switch(this.value) {
                case 'small':
                    previewPanel.style.fontSize = '0.875rem';
                    document.querySelector('.theme-preview-title').style.fontSize = '1.15rem';
                    document.querySelector('.theme-preview-subtitle').style.fontSize = '0.95rem';
                    break;
                case 'medium':
                    previewPanel.style.fontSize = '1rem';
                    document.querySelector('.theme-preview-title').style.fontSize = '1.25rem';
                    document.querySelector('.theme-preview-subtitle').style.fontSize = '1rem';
                    break;
                case 'large':
                    previewPanel.style.fontSize = '1.125rem';
                    document.querySelector('.theme-preview-title').style.fontSize = '1.4rem';
                    document.querySelector('.theme-preview-subtitle').style.fontSize = '1.15rem';
                    break;
            }
        });

        // Form submission handler with visual feedback
        const appearanceForm = document.getElementById('appearanceForm');
        const saveButton = document.getElementById('saveButton');
        
        if (appearanceForm) {
            appearanceForm.addEventListener('submit', function(e) {
                // First prevent the default submission
                e.preventDefault();
                
                // Show loading state
                saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                saveButton.disabled = true;
                
                // Store current settings in localStorage to compare after reload
                const currentSettings = {
                    themeMode: document.querySelector('input[name="theme_mode"]:checked').value,
                    primaryColor: document.querySelector('input[name="primary_color"]:checked').value,
                    sidebarLayout: document.querySelector('input[name="sidebar_layout"]:checked').value,
                    fontSize: document.getElementById('font_size').value
                };
                
                localStorage.setItem('previousSettings', JSON.stringify(currentSettings));
                
                // Log debug info
                console.log('Form submitting with data:', currentSettings);
                
                // Submit the form after a brief delay
                setTimeout(() => {
                    this.submit();
                }, 200);
            });
        }
        
        // Check if returning after a save to highlight changes
        window.addEventListener('load', function() {
            console.log('Checking for success message:', @json(session('success') ? true : false));
            if (@json(session('success') ? true : false)) {
                // If we have success message, check for changes
                const savedSettings = localStorage.getItem('previousSettings');
                
                if (savedSettings) {
                    try {
                        const prevSettings = JSON.parse(savedSettings);
                        const currentTheme = document.querySelector('input[name="theme_mode"]:checked').value;
                        const currentColor = document.querySelector('input[name="primary_color"]:checked').value;
                        const currentSidebar = document.querySelector('input[name="sidebar_layout"]:checked').value;
                        const currentFont = document.getElementById('font_size').value;
                        
                        // Highlight changed settings
                        if (prevSettings.themeMode !== currentTheme) {
                            highlightChangedOption(`#${currentTheme}_mode`);
                        }
                        
                        if (prevSettings.primaryColor !== currentColor) {
                            highlightChangedOption(`#color_${currentColor}`);
                        }
                        
                        if (prevSettings.sidebarLayout !== currentSidebar) {
                            highlightChangedOption(`#${currentSidebar}_sidebar`);
                        }
                        
                        if (prevSettings.fontSize !== currentFont) {
                            highlightChangedOption('#font_size');
                        }
                        
                        // Show visual toast notification
                        showToast('Pengaturan tampilan berhasil disimpan');
                    } catch (e) {
                        console.error('Error parsing saved settings', e);
                    }
                }
            }
        });
        
        function highlightChangedOption(selector) {
            const element = document.querySelector(selector);
            if (element) {
                const parentCard = element.closest('.form-check') || element.closest('.mb-3');
                if (parentCard) {
                    parentCard.classList.add('highlight-change');
                    setTimeout(() => {
                        parentCard.classList.remove('highlight-change');
                    }, 3000);
                }
            }
        }
    });
</script>
@endsection
