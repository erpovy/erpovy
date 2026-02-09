<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <title>{{ config('app.name', 'Erpovy Kurumsal Yönetim Sistemi V2') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
            
            :root {
                --bg-main: #06090f;
                --card-bg: rgba(13, 17, 23, 0.7);
                --primary: #5c67ff;
                --text-muted: #6b7280;
                --input-bg: #fdfae7;
            }

            body { 
                background-color: var(--bg-main); 
                font-family: 'Plus Jakarta Sans', sans-serif;
                margin: 0;
                overflow-x: hidden;
            }

            .auth-wrapper {
                min-height: 100vh;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }

            .glass-card {
                background: rgba(13, 17, 23, 0.6);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 1.5rem;
                padding: 3rem 2.5rem;
                width: 100%;
                max-width: 420px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                text-align: center;
                position: relative;
                z-index: 10;
            }

            /* Input Styling */
            .input-group {
                text-align: left;
                margin-bottom: 1.5rem;
            }
            /* ... (keep other existing styles) ... */
            .input-label {
                font-size: 11px;
                font-weight: 700;
                color: #94a3b8;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 0.75rem;
                display: block;
            }

            .input-wrapper {
                position: relative;
                display: flex;
                align-items: center;
            }

            .input-icon {
                position: absolute;
                left: 1rem;
                color: #64748b;
                font-size: 18px;
            }

            input.custom-input {
                width: 100%;
                background-color: rgba(255, 255, 255, 0.03) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                border-radius: 12px !important;
                padding: 0.85rem 1rem 0.85rem 3rem !important;
                color: white !important;
                font-size: 14px !important;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            input.custom-input:focus, input.custom-input:not(:placeholder-shown) {
                background-color: #fdfae7 !important;
                color: #1a1f3c !important;
                border-color: #fdfae7 !important;
            }

            input.custom-input:focus + .input-icon,
            input.custom-input:not(:placeholder-shown) + .input-icon {
                color: #1a1f3c !important;
            }

            .btn-primary {
                background: var(--primary);
                color: white;
                width: 100%;
                padding: 1rem;
                border-radius: 12px;
                font-weight: 700;
                font-size: 14px;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 10px 15px -3px rgba(92, 103, 255, 0.4);
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 20px 25px -5px rgba(92, 103, 255, 0.5);
                filter: brightness(1.1);
            }

            .auth-footer {
                margin-top: 2rem;
                font-size: 12px;
                color: var(--text-muted);
            }

            .link-subtle {
                color: #5c67ff;
                text-decoration: none;
                font-weight: 600;
            }

            .link-subtle:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body class="antialiased font-display bg-[#06090f] text-white selection:bg-pink-500/30">
        @php
            $loginBackground = \App\Models\Setting::get('login_background');
            $isVideo = $loginBackground && \Illuminate\Support\Str::endsWith($loginBackground, ['.mp4', '.webm']);
        @endphp

        <!-- Background Container -->
        <div class="fixed inset-0 z-[-1] overflow-hidden">
            @if($loginBackground)
                @if($isVideo)
                    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover opacity-60" style="object-fit: cover !important;">
                        <source src="{{ asset($loginBackground) }}" type="video/mp4">
                    </video>
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
                @else
                    <div class="absolute inset-0 w-full h-full bg-cover bg-center opacity-60" style="background-image: url('{{ asset($loginBackground) }}'); background-size: cover !important; background-position: center !important;"></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-[#06090f]/90 via-[#06090f]/70 to-[#06090f]/90"></div>
                @endif
            @else
                <!-- Default Ambient Background -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,#1a1f3c_0%,#06090f_70%)]"></div>
                <div class="absolute -top-[10%] -left-[10%] w-[600px] h-[600px] bg-primary/20 rounded-full blur-[120px] opacity-40 animate-pulse-slow"></div>
                <div class="absolute -bottom-[10%] -right-[10%] w-[600px] h-[600px] bg-purple-600/20 rounded-full blur-[120px] opacity-30 animate-pulse-slow" style="animation-delay: 2s;"></div>
            @endif
        </div>

        <!-- Main Layout (Centered with CSS class) -->
        <div class="auth-wrapper">
            <div class="glass-card">
                {{ $slot }}
                
                <div class="mt-8 pt-6 border-t border-white/5 text-center">
                    <p class="text-[10px] text-slate-500 font-bold tracking-[0.2em] uppercase">
                        &copy; {{ date('Y') }} Erpovy {{ $appVersion['version'] ?? 'v1.0' }}
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
