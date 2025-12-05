<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KuroReader - Light Novel Platform')</title>

    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #0a0e13;
            --bg-secondary: #131921;
            --bg-tertiary: #1a2332;
            --bg-hover: #232d3f;
            --accent-primary: #4f46e5;
            --accent-secondary: #818cf8;
            --accent-tertiary: #a5b4fc;
            --text-primary: #e8edf2;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
            --border-color: rgba(255, 255, 255, 0.05);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        /* ===== NAVIGATION ===== */
        .main-nav {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        /* Logo & Brand */
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .nav-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.25rem;
            color: white;
        }

        .nav-logo-text {
            font-size: 1.375rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--accent-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Nav Links */
        .nav-links {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex: 1;
            margin-left: 2rem;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            color: var(--text-primary);
            background: var(--bg-tertiary);
        }

        .nav-link.active {
            color: var(--accent-secondary);
            background: rgba(79, 70, 229, 0.1);
        }

        /* Nav Actions */
        .nav-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
        }

        /* Buttons */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--accent-primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-secondary);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--accent-primary);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* ===== ALERTS ===== */
        .alerts-container {
            max-width: 1400px;
            margin: 1rem auto;
            padding: 0 1.5rem;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-info {
            background: rgba(79, 70, 229, 0.1);
            border: 1px solid rgba(79, 70, 229, 0.3);
            color: var(--accent-tertiary);
        }

        .alert ul {
            list-style: none;
            margin: 0;
        }

        .alert li {
            margin-bottom: 0.25rem;
        }

        /* ===== FORMS ===== */
        input, select, textarea {
            width: 100%;
            padding: 0.75rem;
            margin: 0.5rem 0;
            border-radius: 8px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        input::placeholder {
            color: var(--text-muted);
        }

        label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        /* ===== GRID ===== */
        .grid {
            display: grid;
            gap: 1.5rem;
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* ===== MOBILE MENU ===== */
        .mobile-menu-btn {
            display: none;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 65px;
            left: 0;
            right: 0;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            z-index: 99;
        }

        .mobile-menu.active {
            display: block;
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .nav-container {
                padding: 0.75rem 1rem;
            }

            .nav-logo-text {
                font-size: 1.125rem;
            }

            .nav-actions {
                gap: 0.5rem;
            }

            .btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .nav-logo {
                width: 36px;
                height: 36px;
                font-size: 1.125rem;
            }

            .user-avatar {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }
        }


        /* ===== LOGO STYLES ===== */
.nav-logo-img {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-logo-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    /* Jika logo terlalu gelap, tambahkan filter */
    /* filter: brightness(0) invert(1); */
}

    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
<a href="/" class="nav-brand">
    <!-- Ganti nama file sesuai ekstensi asli Anda -->
    <img src="{{ asset('images/Logo_KuroReader.png') }}" 
         alt="KuroReader Logo" 
         class="nav-logo-img">
    <span class="nav-logo-text">KuroReader</span>
</a>
            <div class="nav-links">
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Home
                </a>
                <a href="/novels" class="nav-link {{ request()->is('novels*') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    Browse
                </a>
                @auth
                    <a href="/library" class="nav-link {{ request()->is('library*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        My Library
                    </a>
                    <a href="/history" class="nav-link {{ request()->is('history*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        History
                    </a>
                    <a href="/profile" class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Profile
                    </a>
                @endauth
            </div>

            <div class="nav-actions">
                @auth
                    <a href="/profile" class="nav-user" style="text-decoration: none;">
                        <span>{{ Str::limit(auth()->user()->name, 12) }}</span>
                        <div class="user-avatar">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                    </a>
                    <form action="/logout" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="/login" class="btn btn-secondary">Login</a>
                    <a href="/register" class="btn btn-primary">Sign Up</a>
                @endauth
            </div>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-nav-links">
            <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="/novels" class="nav-link {{ request()->is('novels*') ? 'active' : '' }}">Browse</a>
            @auth
                <a href="/library" class="nav-link {{ request()->is('library*') ? 'active' : '' }}">My Library</a>
                <a href="/history" class="nav-link {{ request()->is('history*') ? 'active' : '' }}">History</a>
                <a href="/profile" class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">Profile</a>
            @endauth
        </div>
    </div>

    <!-- Flash Messages -->
    <div class="alerts-container">
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- MAIN CONTENT -->
    @yield('content')

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('active');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('mobileMenu');
            const btn = document.querySelector('.mobile-menu-btn');
            if (!menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.remove('active');
            }
        });
    </script>
</body>
</html>