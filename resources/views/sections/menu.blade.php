<!-- CARDAPIO COMPLETO SECTION -->
<section class="menu" id="cardapio">
    <div class="menu__container">
        <div class="menu__header">
            <h3 class="menu__title">Nosso Cardápio</h3>
            <p class="menu__subtitle">Sabores que refrescam e agradam a galera</p>
        </div>

        <!-- Campo de Busca -->
        <div class="menu__search">
            <div class="search-box">
                <input
                    type="text"
                    class="search-box__input"
                    id="menuSearch"
                    placeholder="Buscar por nome do produto..."
                    aria-label="Buscar produtos"
                >
                <button class="search-box__clear" id="clearSearch" aria-label="Limpar busca">
                    ✕
                </button>
            </div>
            <p class="menu__search-results" id="searchResults"></p>
        </div>

        <!-- Filtro de Categorias -->
        <div class="menu__filters">
            <button class="menu__filter menu__filter--active" data-category="todos">
                Todos
            </button>
            <?php foreach ($categories as $category_id => $title): ?>
                <button class="menu__filter" data-category="<?= slugify($title); ?>">
                    <?= $title; ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Grid de Produtos -->
        <div class="menu__grid">

            <?php foreach ($products as $product): ?>
                <article class="menu-item" data-category="<?= slugify($product['category_description']); ?>" data-product_id="<?= $product['id']; ?>">
                    <div>
                        <img
                            class="menu-item__image"
                            src="<?= @$product['image']
                                ? asset('storage/' . $product['image'])
                                : asset('assets/images/imagemfundoitem.png'); ?>"
                            alt="<?= $product['name']; ?>"
                        >
                    </div>

                    <div class="menu-item__content">
                        <div class="menu-item__header">
                            <h4 class="menu-item__name"><?= $product['name']; ?></h4>
                            <span class="menu-item__price">R$ <?= number_format($product['price'], 2, ',', '.'); ?></span>
                        </div>
                        <p class="menu-item__description"><?= $product['description']; ?></p>
                        <span class="menu-item__badge menu-item__badge--drinks"><?= $product['category_description']; ?></span>
                    </div>
                </article>

            <?php endforeach; ?>

        </div>
    </div>
</section>
