<!-- POPUP DA COMANDA -->
<div class="orders-popup" id="ordersPopup">
    <div class="orders-popup__content">
        <div class="orders-popup__header">
            <svg class="orders-popup__empty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 10L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M8 14L12 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>

            <h3 class="orders-popup__title" id="command_table_number">Comanda Mesa 00</h3>
            <button class="orders-popup__close" id="closeOrdersPopup" aria-label="Fechar comanda">
                ✕
            </button>
        </div>
        <div class="orders-popup__list" id="ordersList">

            <!-- Exemplo de item (será gerado dinamicamente) -->
            <div class="order-item">
                <div class="order-item__header">
                    <h4 class="order-item__name">AINDA NÃO HÁ PEDIDOS</h4>
                </div>

                <a href="#cardapio" class="btn btn--primary btn--large">
                    <span class="btn__text">Ver Cardápio Rápido</span>
                </a>
            </div>
        </div>


    </div>
</div>
