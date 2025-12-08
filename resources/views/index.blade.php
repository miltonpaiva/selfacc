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

    <script>
        var user_logged  = <?= empty($auth_data)? 'false' : 'true' ; ?>;
        var auth_data    = <?= json_encode($auth_data      ?? null); ?>;
        var orders_data  = <?= json_encode($orders         ?? null); ?>;
        var playing_data = <?= json_encode($playing        ?? null); ?>;
        var queue_data   = <?= json_encode($queue['queue'] ?? null); ?>;
    </script>

@endsection

@section('header')
    <!-- HEADER SECTION -->
    <header class="header">
        <div class="header__container">
            <div class="header__logo">
                <!-- <h1 class="logo">MNW BLACK BEACH</h1> -->
                <img class="header__logo-img" src="assets/images/logoheader-mnw-black-beach.png" alt="logo-mnw-black-beach">

            </div>
            <nav class="header__nav">
                <button class="header__menu-toggle" aria-label="Menu">
                    <span class="header__menu-icon"></span>
                    <span class="header__menu-icon"></span>
                    <span class="header__menu-icon"></span>
                </button>
                <ul class="nav">
                    <li class="nav__item"><a href="#cardapio" class="nav__link">Cardápio</a></li>
                    <li class="nav__item"><a href="#playlist" class="nav__link">Playlist</a></li>
                    <li class="nav__item"><a href="#lazer" class="nav__link">Lazer</a></li>
                    <li class="nav__item"><a href="#contato" class="nav__link">Contato</a></li>
                </ul>
                <button class="header__orders-btn" id="ordersBtn" aria-label="Ver minha comanda">
                    <svg class="header__orders-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 12H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span class="header__orders-badge" id="ordersBadge">0</span>
                </button>
            </nav>
        </div>
    </header>
@endsection

@section('body')

    @include('sections.hero')

    @include('sections.playlist')

    @include('sections.common')

    @include('sections.menu')

@endsection

@section('popups')

    @include('popups.comanda')

    @include('popups.music_search')

    @include('popups.alert')

    @include('popups.prompt')

    @include('popups.new_order')

    @include('popups.messages')

    @include('popups.confirm')

    @include('popups.prompt2')

    @include('popups.first_access')

    <div class="custom-popup__overlay" style="display:none"></div>

@endsection

@section('javascript')
    <script src="{{ asset('assets/js/manus.js?' . uniqid()) }}"></script>
    <script src="{{ asset('assets/js/main.js?' . uniqid()) }}"></script>
@endsection


