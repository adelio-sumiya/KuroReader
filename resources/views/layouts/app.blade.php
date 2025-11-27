<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Light Novel Reader')</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: Arial, sans-serif; 
            background: #0d0f13;       /* NIGHT MODE BACKGROUND */
            color: #e5e9ef;            /* Light text */
            padding-bottom: 50px;
        }

        /* NAVIGATION */
        nav { 
            background: #111820;        /* Dark navy-black */
            padding: 1rem 2rem; 
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.6);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        nav .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a { 
            color: #e8edf5; 
            text-decoration: none; 
            margin-right: 20px;
            transition: 0.2s;
        }
        nav a:hover { 
            color: #4ea1ff;            /* Blue accent */
        }

        nav .logo { 
            font-size: 1.5rem; 
            font-weight: bold;
        }

        /* MAIN CONTAINER */
        .container { 
            max-width: 1200px; 
            margin: 2rem auto; 
            padding: 0 2rem;
        }

        /* ALERTS */
        .alert { 
            padding: 1rem; 
            margin-bottom: 1rem; 
            border-radius: 6px;
        }

        .alert-success { 
            background: rgba(22, 60, 32, 0.8);
            color: #c8f7d3;
            border: 1px solid rgba(87, 165, 110, 0.5);
        }

        .alert-error { 
            background: rgba(60, 22, 22, 0.8);
            color: #ffd5d5;
            border: 1px solid rgba(165, 87, 87, 0.4);
        }

        /* BUTTONS */
        .btn { 
            padding: 0.5rem 1rem; 
            background: #2563eb;             /* Blue night accent */
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.2s;
            font-weight: bold;
        }
        .btn:hover { background: #1d4ed8; }

        .btn-danger { background: #e11d48; }
        .btn-danger:hover { background: #be123c; }

        .btn-secondary { background: #374151; }
        .btn-secondary:hover { background: #4b5563; }

        /* CARD */
        .card { 
            background: #151a20;
            padding: 1.5rem; 
            border-radius: 8px; 
            margin-bottom: 1rem;
            border: 1px solid rgba(255,255,255,0.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.6);
        }

        /* GRID */
        .grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
            gap: 1.5rem;
        }

        /* NOVEL CARD */
        .novel-card {
            background: #101418; 
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.05);
            transition: 0.2s;
        }
        .novel-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.6);
        }

        .novel-card img {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }

        .novel-card-body {
            padding: 1rem;
        }

        .novel-card h3 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #e5e9ef;
        }

        .novel-card p {
            color: #9aa3ad;
        }

        /* FORM INPUTS */
        input, select, textarea {
            width: 100%;
            padding: 0.5rem;
            margin: 0.5rem 0;
            border-radius: 5px;
            background: #0f1419;
            border: 1px solid rgba(255,255,255,0.08);
            color: #e5e9ef;
        }
        input::placeholder {
            color: #6b7280;
        }
        input:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.2);
        }

        label {
            font-weight: bold;
            margin-bottom: 0.5rem;
            display: block;
            color: #e5e9ef;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="container">
            <div>
                <a href="/" class="logo"> KuroReader</a>
                <a href="/">Home</a>
                <a href="/novels">Search</a>
                @auth
                    <a href="/library">My Library</a>
                    <a href="/history">History</a>
                @endauth
            </div>

            <div>
                @auth
                    <span style="margin-right: 1rem;">Hi, {{ auth()->user()->name }}!</span>
                    <form action="/logout" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Logout</button>
                    </form>
                @else
                    <a href="/login" class="btn">Login</a>
                    <a href="/register" class="btn">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
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
</body>
</html>
