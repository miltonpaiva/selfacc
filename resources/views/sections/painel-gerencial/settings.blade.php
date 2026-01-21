<section class="painel-gerencial">
    <div class="painel-gerencial__header">
        <h1 class="painel-gerencial__title">📊 Painel Gerencial</h1>
        <div class="painel-gerencial__actions">
            <button class="painel-gerencial__btn painel-gerencial__btn--primary" onclick="openNewProductPopup()">
                <span>➕</span> Novo Produto
            </button>
            <button class="painel-gerencial__btn painel-gerencial__btn--secondary" onclick="openCategoriesPopup()">
                <span>📂</span> Gerenciar Categorias
            </button>
        </div>
    </div>

    <div class="painel-gerencial__content">
        <table class="painel-gerencial__table">
            <thead>
                <tr>
                    <th style="width: 100px;">Imagem</th>
                    <th style="width: 200px;">Nome</th>
                    <th style="width: 120px;">Preço</th>
                    <th style="width: 150px;">Categoria</th>
                    <th style="width: 120px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products_agrouped as $category): ?>

                <tr class="painel-gerencial__category-row">
                    <td colspan="5">
                        📦 <?= strtoupper($category['category_name']); ?>
                    </td>
                </tr>

                <?php foreach ($category['products'] as $product): ?>

                <tr data-id="<?= $product['id']; ?>" data-name="<?= $product['name']; ?>" data-price="<?= $product['price']; ?>" data-category="<?= $product['category_description']; ?>">
                    <td>
                        <div class="painel-gerencial__img-container">
                            <img 
                                class="painel-gerencial__img"
                                src="<?= @$product['image']
                                    ? asset('storage/' . $product['image'])
                                    : asset('assets/images/imagemfundoitem.png'); ?>"
                                alt="<?= $product['name']; ?>"
                            >
                        </div>
                    </td>
                    <td class="painel-gerencial__name"><?= $product['name']; ?></td>
                    <td class="painel-gerencial__price">R$ <?= number_format($product['price'], 2, ',', '.'); ?></td>
                    <td>
                        <span class="painel-gerencial__category-label">
                            <?= $product['category_description']; ?>
                        </span>
                    </td>
                    <td class="painel-gerencial__actions-cell">
                        <button class="painel-gerencial__edit-btn" onclick="openEditProductPopup(<?= $product['id']; ?>)">
                            ✎ Editar
                        </button>
                    </td>
                </tr>

                <?php endforeach; ?>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Incluir os popups -->
@include('sections.painel-gerencial.edit-product')
