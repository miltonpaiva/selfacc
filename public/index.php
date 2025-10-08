

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page - Exibição de Produtos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
        /* ====================
           VARIÁVEIS DE CORES
        ==================== */
        :root {
            --cor-azul-claro: #0077B6;
            --cor-azul-principal: #023E8A;
            --cor-branco: #FFFFFF;
            --cor-bege: #F5F5DC;
            --cor-texto-escuro: #333;
            --cor-texto-claro: #555;
            --cor-borda: #ddd;
        }

        /* ====================
           ESTILOS GERAIS E GRADIENTES
        ==================== */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--cor-texto-escuro);
            /* Gradiente principal no corpo da página */
            background-color: var(--cor-bege);
            background-image: linear-gradient(to bottom, var(--cor-branco) 0%, var(--cor-bege) 100%);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* ====================
           COMPONENTE HEADER (MENU)
        ==================== */
        .header {
            background-color: rgba(255, 255, 255, 0.9); /* Um branco semi-transparente para o gradiente de fundo aparecer */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .header__nav {
            padding: 1rem 0;
        }

        .header__logo-img {
            height: 40px;
        }

        .header__nav-link {
            color: var(--cor-azul-principal) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .header__nav-link:hover {
            color: var(--cor-azul-claro) !important;
        }

        /* ====================
           COMPONENTE SEARCH-FILTER
        ==================== */
        .search-filter {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px 0 10px;
        }

        .search-filter__input,
        .search-filter__dropdown {
            border-color: var(--cor-borda);
            color: var(--cor-texto-escuro);
        }
        
        .search-filter__button {
            background-color: var(--cor-azul-principal);
            border-color: var(--cor-azul-principal);
        }

        .search-filter__button:hover {
            background-color: var(--cor-azul-claro);
            border-color: var(--cor-azul-claro);
        }

        .search-filter__category-item {
            color: var(--cor-texto-escuro);
            transition: background-color 0.2s ease;
        }

        .search-filter__category-item:hover {
            background-color: var(--cor-bege);
            color: var(--cor-azul-principal);
        }

        /* ====================
           COMPONENTE PRODUCT-LIST (Produtos)
        ==================== */
        .product-list {
            padding: 60px 0;
            background-image: linear-gradient(to bottom, var(--cor-bege) 0%, #E8E8D0 100%);
        }

        .product-list__title {
            color: var(--cor-azul-principal);
            text-align: left;
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* ====================
           COMPONENTE ITEM DE PRODUTO
        ==================== */
        .product-item {
            background-color: var(--cor-branco);
            border: 1px solid var(--cor-borda);
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.3s ease;
        }

        .product-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-item__image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            margin-left: 15px;
        }

        .product-item__content {
            flex: 1;
        }

        .product-item__title {
            color: var(--cor-texto-escuro);
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .product-item__description {
            color: var(--cor-texto-claro);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .product-item__price {
            color: var(--cor-azul-principal);
            font-weight: bold;
            font-size: 1.1rem;
        }

        /* ====================
           COMPONENTE FOOTER
        ==================== */
        .footer {
            background-color: var(--cor-azul-principal);
            color: var(--cor-branco);
            padding: 40px 0;
            /* Gradiente no footer */
            background-image: linear-gradient(to top, var(--cor-azul-principal), #005691);
        }

        .footer__title {
            color: var(--cor-branco);
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .footer__link {
            color: var(--cor-bege);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer__link:hover {
            color: var(--cor-branco);
        }

        .footer__text {
            color: var(--cor-bege);
            margin-bottom: 0.5rem;
        }

        .footer__divider {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 2rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

    <header class="header">
        <nav class="header__nav navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand header__logo" href="#">
                    <span style="font-size: 1.5rem; font-weight: bold; color: var(--cor-azul-principal);">Sua Marca</span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto header__nav-list">
                        <li class="nav-item">
                            <a class="nav-link header__nav-link" href="#produtos">Produtos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="search-filter">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control search-filter__input" placeholder="Buscar produtos...">
                        <div class="input-group-append">
                            <button class="btn btn-primary search-filter__button" type="button">Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-end">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle search-filter__dropdown" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Categorias
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item search-filter__category-item" href="#" data-category="todas">Todas</a>
                            <a class="dropdown-item search-filter__category-item" href="#" data-category="frango">Frango Assado</a>
                            <a class="dropdown-item search-filter__category-item" href="#" data-category="combos">Combos</a>
                            <a class="dropdown-item search-filter__category-item" href="#" data-category="linguiças">Linguiças</a>
                            <a class="dropdown-item search-filter__category-item" href="#" data-category="porções">Porções</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="produtos" class="product-list">
        <div class="container">
            <h2 class="product-list__title">Frango assado + combos %</h2>
            <div class="row">
                <div class="col-md-6 product-item-frango" data-category="frango">
                    <div class="product-item">
                        <div class="product-item__content">
                            <h3 class="product-item__title">Frango assado na brasa (UNID)</h3>
                            <p class="product-item__description">Suculento ao molho de vinho + segredinhos do cheff celly.</p>
                            <span class="product-item__price">R$ 25,00</span>
                        </div>
                        <img src="https://via.placeholder.com/100x100?text=Frango" alt="Frango assado" class="product-item__image">
                    </div>
                </div>
                <div class="col-md-6 product-item-frango" data-category="frango">
                    <div class="product-item">
                        <div class="product-item__content">
                            <h3 class="product-item__title">1/2 Frango assado na brasa</h3>
                            <p class="product-item__description">Suculento ao molho de vinho + segredinhos do cheff celly.</p>
                            <span class="product-item__price">R$ 12,50</span>
                        </div>
                        <img src="https://via.placeholder.com/100x100?text=Meio+Frango" alt="Meio frango assado" class="product-item__image">
                    </div>
                </div>
                <div class="col-md-6 product-item-frango" data-category="frango">
                    <div class="product-item">
                        <div class="product-item__content">
                            <h3 class="product-item__title">1/4 Frango assado na brasa</h3>
                            <p class="product-item__description">Suculento ao molho de vinho + segredinhos do cheff celly.</p>
                            <span class="product-item__price">R$ 7,00</span>
                        </div>
                        <img src="https://via.placeholder.com/100x100?text=Quarto+Frango" alt="Quarto de frango assado" class="product-item__image">
                    </div>
                </div>
                <div class="col-md-6 product-item-combos" data-category="combos">
                    <div class="product-item">
                        <div class="product-item__content">
                            <h3 class="product-item__title">COMBO GALETO ASSADO</h3>
                            <p class="product-item__description">Acompanha: 1 baião (g), 2 linguiças, 1 frango inteiro</p>
                            <span class="product-item__price">R$ 37,00</span>
                        </div>
                        <img src="https://via.placeholder.com/100x100?text=Combo" alt="Combo galeto assado" class="product-item__image">
                    </div>
                </div>
                <div class="col-md-6 product-item-linguiças" data-category="linguiças">
                    <div class="product-item">
                        <div class="product-item__content">
                            <h3 class="product-item__title">Linguiça assada und</h3>
                            <p class="product-item__description">Pode ser toscana ou mista</p>
                            <span class="product-item__price">R$ 3,50</span>
                        </div>
                        <img src="https://via.placeholder.com/100x100?text=Linguiça" alt="Linguiça assada" class="product-item__image">
                    </div>
                </div>
                <div class="col-md-6 product-item-porções" data-category="porções">
                    <div class="product-item">
                        <div class="product-item__content">
                            <h3 class="product-item__title">Vinagrete porção</h3>
                            <p class="product-item__description">Potinho de 150ML</p>
                            <span class="product-item__price">R$ 2,50</span>
                        </div>
                        <img src="https://via.placeholder.com/100x100?text=Vinagrete" alt="Vinagrete porção" class="product-item__image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer__container container">
            <div class="row">
                <div class="col-md-6 text-center text-md-left">
                    <p class="footer__text mb-0">&copy; 2025 Sua Empresa. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-right">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#produtos" class="footer__link">Produtos</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        /* ====================
           JAVASCRIPT
        ==================== */
        $(document).ready(function(){
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.hash);
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 800);
                }
            });

            $('.search-filter__category-item').on('click', function(e) {
                e.preventDefault();
                var category = $(this).data('category');

                if (category === 'todas') {
                    $('.product-list .col-md-6').show();
                } else {
                    $('.product-list .col-md-6').hide();
                    $('.product-list .product-item-' + category).show();
                }
            });

            $('.search-filter__button').on('click', function() {
                performSearch();
            });

            $('.search-filter__input').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            function performSearch() {
                var searchText = $('.search-filter__input').val().toLowerCase();
                
                $('.product-list .col-md-6').each(function() {
                    var productText = $(this).text().toLowerCase();
                    if (productText.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
    </script>
</body>
</html>


<?php

exit();

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
