@extends('layouts.user')
@section('title', 'Home')

@section('content')
    <div class="bg-[--black]  min-h-screen">
        {{-- <h1 class="text-3xl font-bold underline">
            Welcome to the User Home Page!
        </h1> --}}

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


        {{-- schedule --}}
        <div class="relative min-h-screen pb-24 flex items-center justify-center" id="tor">
            <div class="relative z-31">
                @include('partials.schedule')
            </div>
        </div>


        {{-- submission --}}
        <div class="relative min-h-screen pb-24 flex items-center justify-center" id="timeline">
            <div class="relative z-31">
                @include('partials.submission')
            </div>
        </div>

        {{-- ticket --}}
        <div class="relative min-h-screen pb-24 flex items-center justify-center" id="faq">
            <div class="relative z-31">
                @include('partials.ticket')
            </div>
        </div>
    </div>
@endsection
