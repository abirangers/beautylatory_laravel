<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BeautyLatory')</title>
    <link rel="preload" href="{{ asset('css/style.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </noscript>
    @yield('styles')
    <style>
        .nav-container {
            max-width: var(--container-width);
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .hero__slider {
                height: auto;
            }

            .hero__image {
                height: auto;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('images/asset-logo.png') }}"
                            alt="BeautyLatory Logo" width="68" height="30" loading="eager"></a>
                </div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link">Products</a>
                    </li>
                    @auth('admin')
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
                                @csrf
                                <button type="submit" class="nav-link nav-link-button">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; {{ date('Y') }} BeautyLatory. All rights reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}" defer></script>
    @yield('scripts')
</body>

</html>
