<!-- POPUP DE PRODUTO -->
<div class="product-popup" id="productPopup">
    <div class="product-popup__content">
        <button class="product-popup__close" id="productPopupClose" aria-label="Fechar">
            ✕
        </button>

        <div class="product-popup__header">
            <div class="product-popup__image">
                <span>IMAGEM</span>
            </div>
            <div class="product-popup__info">
                <span class="product-popup__badge" id="productBadge">Categoria</span>
                <h3 class="product-popup__title" id="productTitle">Nome do Produto</h3>
                <p class="product-popup__description" id="productDescription">Descrição do produto</p>
                <p class="product-popup__price" id="productPrice">R$ 0,00</p>
            </div>
        </div>

        <div class="product-popup__body">
            <div class="product-popup__quantity">

                <select id="table_number" class="custom-popup__input table_number">
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
                </select>
                <input type="text" class="custom-popup__input" id="name" placeholder="Seu nome">

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
                <label class="product-popup__label" for="productObservations">
                    Observações (opcional)
                </label>
                <textarea class="product-popup__textarea" id="observations" placeholder="Ex: Sem cebola, ponto da carne mal passado, etc..." rows="4" maxlength="200" ></textarea>
                <span class="product-popup__char-count">
                    <span id="productCharCount">0</span>/200
                </span>
            </div>
        </div>

        <div class="product-popup__footer">
            <div class="product-popup__total only_waiter">
                <span class="product-popup__total-label">Total:</span>
                <span class="product-popup__total-value" id="productTotal">R$ 0,00</span>
                <input type="hidden" id="total">
                <input type="hidden" id="product_id">
            </div>
            <button class="product-popup__add-btn" id="productAddBtn">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 3H5L5.4 5M7 13H17L21 5H5.4M7 13L5.4 5M7 13L4.707 15.293C4.077 15.923 4.523 17 5.414 17H17M17 17C15.895 17 15 17.895 15 19C15 20.105 15.895 21 17 21C18.105 21 19 20.105 19 19C19 17.895 18.105 17 17 17ZM9 19C9 20.105 8.105 21 7 21C5.895 21 5 20.105 5 19C5 17.895 5.895 17 7 17C8.105 17 9 17.895 9 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Adicionar à Comanda
            </button>
        </div>
    </div>
</div>
