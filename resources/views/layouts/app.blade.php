<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token"  content="{{ csrf_token() }}">
    <meta name="viewport"    content="width=device-width,initial-scale=1, initial-scale=1.0">
    <meta name="description" content="MNW BLACK BEACH - A melhor barraca de praia com estilo, música e lazer">

    <title>{{ $page_title ?? 'MNW BLACK BEACH' }}</title>

    @hasSection('css')
        @yield('css')
    @endif

</head>
<body class="page">

    @hasSection('header')
        @yield('header')
    @endif

    @hasSection('body')
        @yield('body')
    @endif

    <!-- FOOTER SECTION -->
    <footer class="footer" id="contato">
        <div class="footer__container">
            <div class="footer__brand">
                <h3 class="footer__logo">MNW BLACK BEACH</h3>
                <p class="footer__tagline">Estilo, Música e Lazer</p>
            </div>

            <div class="footer__social">
                <h4 class="footer__title">Siga a Vibe</h4>
                <div class="social">
                    <a href="#" class="social__link" aria-label="Instagram">
                        <div class="social__icon social__icon--instagram"></div>
                    </a>
                    <a href="#" class="social__link" aria-label="Facebook">
                        <div class="social__icon social__icon--facebook"></div>
                    </a>
                    <a href="#" class="social__link" aria-label="WhatsApp">
                        <div class="social__icon social__icon--whatsapp"></div>
                    </a>
                    <a href="#" class="social__link" aria-label="TikTok">
                        <div class="social__icon social__icon--tiktok"></div>
                    </a>
                </div>
            </div>

            <div class="footer__contact">
                <h4 class="footer__title">Contato</h4>
                <p class="footer__info">📍 Praia do [Local]</p>
                <p class="footer__info">📞 (00) 00000-0000</p>
                <p class="footer__info">⏰ Seg-Dom: 9h às 19h</p>
            </div>
        </div>

        <div class="footer__bottom">
            <p class="footer__copyright">© 2025 MNW BLACK BEACH. Todos os direitos reservados.</p>
        </div>
    </footer>

    @hasSection('popups')
        @yield('popups')
    @endif

    @hasSection('javascript')
        @yield('javascript')
    @endif

</body>
</html>
