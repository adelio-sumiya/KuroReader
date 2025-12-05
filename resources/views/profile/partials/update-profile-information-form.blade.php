<section class="form-section">
    <header class="form-header">
        <h2>Profile Information</h2>
        <p>Update your account's profile information and email address.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="form-group">
            <label>Profile Picture</label>
            <div style="display: flex; gap: 1.5rem; align-items: center; margin-bottom: 1rem;">
                <div class="avatar-preview" style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: var(--bg-tertiary); border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    @if($user->avatar)
                        <img src="{{ $user->avatar_url }}" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover;" id="avatarPreview">
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; color: var(--accent-secondary); background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));" id="avatarPreview">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div style="flex: 1;">
                    <input 
                        id="avatar" 
                        name="avatar" 
                        type="file" 
                        accept="image/*"
                        onchange="previewAvatar(this)"
                        style="padding: 0.5rem;"
                    />
                    <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.5rem;">
                        Upload a profile picture (max 2MB, JPG, PNG, GIF)
                    </p>
                    @error('avatar')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name"
            />
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username"
            />
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top: 0.75rem; padding: 0.75rem; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 8px; color: #fbbf24;">
                    <p style="font-size: 0.875rem; margin-bottom: 0.5rem;">
                        Your email address is unverified.
                    </p>
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="color: #fbbf24; text-decoration: underline; background: none; border: none; cursor: pointer; font-size: 0.875rem;">
                            Click here to re-send the verification email.
                        </button>
                    </form>
                    @if (session('status') === 'verification-link-sent')
                        <p style="margin-top: 0.5rem; font-weight: 600; font-size: 0.875rem; color: var(--success);">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>

            @if (session('status') === 'profile-updated')
                <p class="form-success">Saved.</p>
            @endif
        </div>
    </form>
</section>
