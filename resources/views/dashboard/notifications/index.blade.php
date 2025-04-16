@extends('layouts.dashboard')

@section('page-title', 'Notifikasi')

@section('dashboard-content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title">Semua Notifikasi</h5>
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary" id="markAllAsReadBtn">
                    <i class="bx bx-check-all me-1"></i> Tandai Semua Sudah Dibaca
                </button>
            </form>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="list-group notification-list">
            @forelse($notifications as $notification)
                <div class="list-group-item notification-item px-0 py-3 d-flex {{ is_null($notification->read_at) ? 'unread' => '' }}" data-id="{{ $notification->id }}">
                    <div class="notification-icon {{ $notification->icon_background }} me-3">
                        <i class="bx {{ $notification->icon }}"></i>
                    </div>
                    <div class="notification-content flex-grow-1">
                        <h6 class="notification-title mb-1">{{ $notification->title }}</h6>
                        <p class="notification-text mb-0">{{ $notification->message }}</p>
                        <span class="notification-time small text-muted">{{ $notification->time_ago }}</span>
                    </div>
                    @if(is_null($notification->read_at))
                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="mark-read-form">
                            @csrf
                            <button type="submit" class="notification-action btn-mark-as-read" data-id="{{ $notification->id }}" title="Tandai sudah dibaca">
                                <i class="bx bx-check"></i>
                            </button>
                        </form>
                    @endif
                    @if($notification->link)
                        <a href="{{ $notification->link }}" class="ms-2 btn btn-sm btn-light">Lihat</a>
                    @endif
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bx bx-bell-off text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3">Tidak Ada Notifikasi</h5>
                    <p class="text-muted">Anda belum memiliki notifikasi baru</p>
                </div>
            @endforelse
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark as read functionality with AJAX
        document.querySelectorAll('.btn-mark-as-read').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const notificationId = this.dataset.id;
                const form = this.closest('form');
                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ id: notificationId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
                        item.classList.remove('unread');
                        this.remove();
                        updateUnreadCount();
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                });
            });
        });

        // Mark all as read with AJAX
        const markAllBtn = document.getElementById('markAllAsReadBtn');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelectorAll('.notification-item.unread').forEach(item => {
                            item.classList.remove('unread');
                            const button = item.querySelector('.btn-mark-as-read');
                            if (button) button.remove();
                        });
                        updateUnreadCount();
                        showToast('Semua notifikasi ditandai sudah dibaca', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error marking all notifications as read:', error);
                });
            });
        }

        function updateUnreadCount() {
            fetch("{{ route('notifications.unread-count') }}")
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notifications .badge');
                    if (badge) {
                        badge.textContent = data.count;
                        if (data.count === 0) {
                            badge.style.display = 'none';
                        } else {
                            badge.style.display = '';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating notification count:', error);
                });
        }
    });
</script>
@endsection
