<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MBGood' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #0d6efd;
            --primary-light: #e9f0ff;
            --success: #198754;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.08), transparent 55%),
                radial-gradient(circle at bottom right, rgba(25, 135, 84, 0.08), transparent 55%),
                linear-gradient(180deg, #f9fbff 0%, #f4f6fb 40%, #f8f9fb 100%);
            background-attachment: fixed;
        }
        
        .bg-primary-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, #0b5ed7 100%);
        }
        
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
        }
        
        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .navbar {
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary) !important;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--dark) !important;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
        }
        
        .nav-link.active, .nav-link:hover {
            color: var(--primary) !important;
            background-color: var(--primary-light);
        }
        
        footer {
            background-color: #f8f9fa;
            padding: 3rem 0;
            margin-top: auto;
        }
        
        .hero-section {
            padding: 5rem 0;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), 
                        url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
        }
        
        .page-shell {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        
        .stat-card {
            text-align: center;
            padding: 2rem 1rem;
            border-radius: 1rem;
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #6c757d;
            margin: 0;
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            bottom: -10px;
            left: 0;
            background: var(--primary);
            border-radius: 2px;
        }
    </style>
</head>

<body>
    @include('layouts.navbar')

    <main class="flex-grow-1 page-shell">
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
