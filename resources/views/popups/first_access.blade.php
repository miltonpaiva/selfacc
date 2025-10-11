<!-- Prompt Popup -->
<div class="custom-popup" id="popup_first_access">
    <div class="custom-popup__box custom-popup__box--prompt">
        <div class="custom-popup__icon custom-popup__icon--info">
            BOTA O ICONE AQUI
        </div>
        <h3 class="custom-popup__title" id="promptTitle">Olá, bem vindo a MNW BLACK BEACH</h3>
        <p class="custom-popup__message" id="promptMessage">Por favor, insira abaixo seu nome e uma senha para que só você realize seus pedidos:</p>
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
        <input type="text"   class="custom-popup__input" id="name" placeholder="Seu nome">
        <input type="number" class="custom-popup__input" id="code" placeholder="Sua senha ex: 1234" min="0001" max="9999">
        <div class="custom-popup__actions">
            <button class="custom-popup__btn custom-popup__btn--secondary close_popup" id="first_access_btn_cancel">
                Cancelar
            </button>
            <button class="custom-popup__btn custom-popup__btn--primary" id="first_access_btn_ok">
                Fazer meu pedido
            </button>
        </div>
    </div>
</div>
