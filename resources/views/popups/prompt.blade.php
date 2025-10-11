<!-- Prompt Popup -->
<div class="custom-popup" id="customPrompt">
    <div class="custom-popup__box custom-popup__box--prompt">
        <div class="custom-popup__icon custom-popup__icon--info">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                <path d="M12 16V12M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
        <h3 class="custom-popup__title" id="promptTitle">Digite o valor</h3>
        <p class="custom-popup__message" id="promptMessage">Por favor, insira a informação:</p>
        <input 
            type="text" 
            class="custom-popup__input" 
            id="promptInput"
            placeholder="Digite aqui..."
        >
        <div class="custom-popup__actions">
            <button class="custom-popup__btn custom-popup__btn--secondary" id="promptCancelBtn">
                Cancelar
            </button>
            <button class="custom-popup__btn custom-popup__btn--primary" id="promptOkBtn">
                Confirmar
            </button>
        </div>
    </div>
</div>
