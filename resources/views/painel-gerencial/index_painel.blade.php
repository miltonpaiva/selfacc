@extends('layouts.app')

@section('css')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS DA PÁGINA INICIAL -->
    <style type="text/css">
        @import "{{ asset('assets/css/styles.css?' . uniqid()) }}";
    </style>
    <!-- CSS DA PÁGINA INICIAL -->

@endsection

@section('header')
    <!-- HEADER SECTION -->
    <header class="header">
        <div class="header__container">
            <div class="header__logo">
                <h1 class="logo">MNW BLACK BEACH</h1>
            </div>
            <nav class="header__nav">
                <button class="header__menu-toggle" aria-label="Menu">
                    <span class="header__menu-icon"></span>
                    <span class="header__menu-icon"></span>
                    <span class="header__menu-icon"></span>
                </button>
                <ul class="nav">
                    <li class="nav__item"><a href="#cardapio" class="nav__link">Cardápio</a></li>
                </ul>
            </nav>
        </div>
    </header>
@endsection

@section('body')

        @include('sections.painel-gerencial.settings')

@endsection






@section('javascript')
    <script src="{{ asset('assets/js/manus.js?' . uniqid()) }}"></script>
    <script src="{{ asset('assets/js/main.js?' . uniqid()) }}"></script>
    <script src="{{ asset('assets/js/painel-gerencial.js?' . uniqid()) }}"></script>
@endsection


