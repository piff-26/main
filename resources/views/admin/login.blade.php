@extends('layouts.admin')
@section('title', 'Login Admin')

@push('styles')
    <style>
        .login-section {
            position: relative;
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--black);
            overflow: hidden;
        }

        .login-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        .login-card {
            position: relative;
            z-index: 2;
            min-width: 280px;
            width: 280px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid var(--primary-white);
            border-radius: 16px;
            overflow: hidden;
            opacity: 1;
            transform: scale(1);
            transition: all 0.4s ease;
        }

        @media (min-width: 768px) {
            .login-card {
                min-width: 400px;
                width: 400px;
            }
        }

        .login-card:hover {
            border-color: var(--yellow);
            box-shadow: 0 0 40px var(--yellow);
        }

        .card-head {
            padding: 1.5rem 1.5rem 0.5rem 1.5rem;
        }

        @media (min-width: 768px) {
            .card-head {
                padding: 2rem 2rem 0.75rem 2rem;
            }
        }

        .card-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary-white);
            letter-spacing: 0.05em;
            line-height: 1.1;
            text-align: center;
        }

        @media (min-width: 768px) {
            .card-title {
                font-size: 2.25rem;
            }
        }

        .card-body {
            width: 280px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1.5rem 1.5rem 1.5rem;
        }

        @media (min-width: 768px) {
            .card-body {
                width: 400px;
                padding: 0.75rem 2rem 2rem 2rem;
            }
        }

        .google-btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 9999px;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: #374151;
            transition: all 0.3s ease;
            overflow: hidden;
            text-decoration: none;
        }

        .google-btn:hover {
            border-color: #3b82f6;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .google-btn:active {
            transform: translateY(0);
        }

        .shimmer {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg,
                    transparent 0%,
                    rgba(255, 255, 255, 0.4) 50%,
                    transparent 100%);
            width: 50%;
            transform: translateX(-100%);
            transition: transform 0.7s ease-in-out;
        }

        .google-btn:hover .shimmer {
            transform: translateX(200%);
        }
    </style>
@endpush

@section('content')
    <div class="login-section"
        style="background-image: url('{{ asset('assets/background/bg_login_admin.png') }}'); background-size: cover; background-position: center;">
        <div class="login-card cursor-pointer">
            <div class="card-head">
                <div class="card-title">ADMIN LOGIN</div>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.auth.google') }}" class="google-btn">
                    <div class="shimmer"></div>
                    <svg class="w-6 h-6 relative z-10" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853"
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05"
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path fill="#EA4335"
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    <span class="relative z-10">Sign in with Google</span>
                </a>
            </div>
        </div>
    </div>
@endsection
