@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container" style="margin-top: 2rem;">
    <div class="card" style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.8rem; margin-bottom: 0.5rem;">Profile Settings</h1>
        <p style="color: var(--text-secondary);">
            Manage your account settings and preferences.
        </p>
    </div>

    <!-- Profile Information -->
    <div class="card">
        @include('profile.partials.update-profile-information-form')
    </div>

    <!-- Update Password -->
    <div class="card">
        @include('profile.partials.update-password-form')
    </div>

    <!-- Delete Account -->
    <div class="card">
        @include('profile.partials.delete-user-form')
    </div>
</div>

<style>
    .form-section {
        margin-bottom: 2rem;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .form-header {
        margin-bottom: 1.5rem;
    }

    .form-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-header p {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .form-success {
        color: var(--success);
        font-size: 0.9rem;
        padding: 0.5rem 0;
    }

    .error-message {
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        margin-bottom: 1.5rem;
    }

    .modal-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .modal-header p {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
    }
</style>

<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.add('active');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('deleteModal');
        if (e.target === modal) {
            closeDeleteModal();
        }
    });

    function previewAvatar(input) {
        const preview = document.getElementById('avatarPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
