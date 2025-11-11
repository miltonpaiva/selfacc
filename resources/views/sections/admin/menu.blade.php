<!-- CARDAPIO COMPLETO SECTION -->
<section class="menu">
    <div class="menu__container">
        <!-- Campo de Busca -->
        <div class="menu__search">
            <div class="search-box">
                <input
                    type="text"
                    class="search-box__input"
                    id="menuSearch"
                    placeholder="Buscar por nome do cliente, numero da mesa..."
                    aria-label="Buscar produtos"
                >
                <button class="search-box__clear" id="clearSearch" aria-label="Limpar busca">
                    ✕
                </button>
            </div>
            <p class="menu__search-results" id="searchResultsTables"></p>
        </div>

        <div class="menu__grid" id="tables_content">

                <article class="menu-item" >
                    <div class="menu-item__content">
                        <select class="custom-popup__input" id="new_table_number">
                            <option value="">numero da sua mesa</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                        </select>
                        <input type="text" class="custom-popup__input" id="name" placeholder="Seu nome">
                        <button class="product-popup__add-btn" onclick="createNewTable()">
                            Criar comanda
                        </button>
                    </div>
                </article>

        </div>

    </div>
</section>


<!-- POPUP DE PRODUTO -->
<div class="product-popup" id="newOrderPopupAdmin">
    <div class="product-popup__content">
        <button class="product-popup__close popup_close" aria-label="Fechar">✕</button>

        <div class="product-popup__header">
            <div class="product-popup__info">
                <h3 class="product-popup__title" id="new_order_title">Adicionar pedido Mesa 00</h3>
            </div>
        </div>

        <div class="product-popup__body">

            <select id="account_id" class="custom-popup__input">
                <option value="">Selecione o cliente</option>
            </select>

            <div class="search-box">
                <input type="text" class="search-box__input" placeholder="Buscar por produto" aria-label="Buscar produtos" onkeyup="searchItens('search_item_products', this)">
                <button class="search-box__clear" aria-label="Limpar busca">
                    ✕
                </button>
            </div>

            <div class="products-table__wrapper">
                <table class="products-table__table">
                    <thead class="products-table__thead">
                        <tr class="products-table__row">
                            <th class="products-table__th products-table__th--name">Nome</th>
                            <th class="products-table__th products-table__th--price">Preço (R$)</th>
                            <th class="products-table__th products-table__th--category">Categoria</th>
                            <th class="products-table__th products-table__th--number">#</th>
                        </tr>
                    </thead>
                    <tbody class="products-table__tbody">

                        <?php foreach ($products_agrouped as $category): ?>

                                <tr class="products-table__row search_item_products hidden_in_search">
                                    <td class="products-table__td products-table__td--category-colspan" colspan="4">
                                        <?= $category['category_name']; ?>
                                    </td>
                                </tr>

                            <?php foreach ($category['products'] as $product): ?>

                                <tr class="products-table__row search_item_products" text_searchable="<?= "{$product['id']} {$product['name']}"; ?>">
                                    <td class="products-table__td products-table__td--name"><?= $product['name']; ?></td>
                                    <td class="products-table__td products-table__td--price"><?= number_format($product['price'], 2, ',', '.'); ?></td>
                                    <td class="products-table__td products-table__td--category">
                                        <span class="products-table__badge products-table__badge--drinks">
                                            <?= $product['category_description']; ?>
                                        </span>
                                    </td>
                                    <td class="products-table__td products-table__td--number">
                                        <label for="product_<?= $product['id']; ?>" class="badge_checkbox_label"><?= $product['id']; ?></label>
                                        <input type="checkbox" id="product_<?= $product['id']; ?>" name="product_id" value="<?= $product['id']; ?>" class="badge_checkbox">
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <div class="product-popup__quantity">
                <label class="product-popup__label">Quantidade</label>
                <div class="product-popup__quantity-controls">
                    <button class="product-popup__qty-btn" id="productQtyMinus" aria-label="Diminuir quantidade">
                        −
                    </button>
                    <input type="number" class="product-popup__qty-input" id="quantity" value="1" min="1" max="99" readonly >
                    <button class="product-popup__qty-btn" id="productQtyPlus" aria-label="Aumentar quantidade">
                        +
                    </button>
                </div>
            </div>

            <div class="product-popup__observations">
                <label class="product-popup__label" for="observations">
                    Observações (opcional)
                </label>
                <textarea class="product-popup__textarea" id="observations" placeholder="Ex: Sem cebola, ponto da carne mal passado, etc..." rows="4" maxlength="200" ></textarea>
            </div>
        </div>

        <div class="product-popup__footer">
            <div class="product-popup__total only_waiter">
                <span class="product-popup__total-label">Total:</span>
                <span class="product-popup__total-value" id="order_total">R$ 0,00</span>
                <input type="hidden" id="total" value="0">
                <input type="hidden" id="is_admin" value="1">
            </div>
            <button class="product-popup__add-btn" onclick="registerAdminOrder()">
                <svg class="header__orders-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                    <path d="M7 12H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                    <path d="M7 17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                </svg>
                Adicionar à Comanda
            </button>
        </div>
    </div>
</div>


<!-- POPUP DA COMANDA -->
<div class="orders-popup" id="ordersPopupAdmin">
    <div class="orders-popup__content">
        <div class="orders-popup__header">
            <svg class="orders-popup__empty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 10L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M8 14L12 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>

            <h3 class="orders-popup__title" id="command_table_number">Mesa 00 | R$ 00,00</h3>
            <button class="orders-popup__close popup_close"  aria-label="Fechar comanda">
                ✕
            </button>
        </div>
        <div class="orders-popup__header">
            <h4 class="orders-popup__more-values" id="more_order_values">C. Credito: 00,00 | C. Debito: 00,00</h4>
        </div>
        <div class="orders-popup__list" id="ordersListAdmin">

            <!-- Exemplo de item (será gerado dinamicamente) -->
            <div class="order-item">
                <div class="order-item__header">
                    <h4 class="order-item__name">AINDA NÃO HÁ PEDIDOS</h4>
                </div>
            </div>
        </div>

        <div class="product-popup__footer">
            <select class="custom-popup__input" id="close_table_customer">
                <option value="">Selecione o cliente</option>
                <option value="all">todos os clientes</option>
            </select>
            <button class="product-popup__add-btn" onclick="closeTable()">
                Fechar a comanda
            </button>
        </div>


    </div>
</div>

