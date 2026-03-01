@extends('layouts.user')
@section('title', 'Home')

@push('styles')
    <style>
        #title_wrapper {
            width: 100%;
            background-position: center;
            background-image: url('{{ asset('assets/img/bg-home-blue.png') }}');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
@endpush

@section('content')
    <div class="bg-[--black]  min-h-screen">
        {{-- <h1 class="text-3xl font-bold underline">
            Welcome to the User Home Page!
        </h1> --}}

        <div id="title_wrapper">
            {{-- title --}}
            <div class="relative min-h-screen pb-24 flex items-center justify-center" id="title">
                <div class="relative z-31">
                    @include('partials.title')
                </div>
            </div>

            {{-- about us --}}
            <div class="relative min-h-screen pb-24 flex items-center justify-center" id="about">
                <div class="relative z-31">
                    @include('partials.about')
                </div>
            </div>
        </div>


        {{-- schedule --}}
        <div class="relative min-h-screen pb-24 flex items-center justify-center" id="schedule">
            <div class="relative z-31">
                @include('partials.schedule')
            </div>
        </div>


        {{-- submission --}}
        <div class="relative  flex items-center justify-center" id="submission">
            <div class="relative z-31">
                @include('partials.submission')
            </div>
        </div>

        {{-- ticket --}}
        <div class="relative flex items-center justify-center" id="ticket">
            <div class="relative z-31">
                @include('partials.ticket')
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            if (history.scrollRestoration) {
                history.scrollRestoration = 'manual';
            }
            
            window.addEventListener('beforeunload', function () {
                window.scrollTo(0, 0);
            });
            
            window.addEventListener('load', function () {
                setTimeout(function() {
                    window.scrollTo(0, 0);
                }, 0);
            });
        </script>
    @endpush
@endsection
