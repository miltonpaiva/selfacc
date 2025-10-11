<!-- POPUP DE BUSCA DE MÚSICAS -->
<div class="music-popup" id="musicPopup">
    <div class="music-popup__content">
        <div class="music-popup__header">
            <h3 class="music-popup__title">Peça Sua Música</h3>
            <button class="music-popup__close" id="closeMusicPopup" aria-label="Fechar">
                ✕
            </button>
        </div>

        <!-- Campo de Busca -->
        <div class="music-popup__search">
            <div class="music-search">
                <input  type="text"  class="music-search__input"  id="musicSearchInput" placeholder="Buscar por música ou artista..." aria-label="Buscar música">
                <button class="music-search__clear" id="clearMusicSearch" aria-label="Limpar busca">
                    ✕
                </button>
                <button class="music-result__btn music-result__btn--queue" id="btn_search">
                    <svg class="music-search__icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                        <path d="M21 21L16.5 16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <p class="music-popup__search-info" id="musicSearchInfo">Digite para buscar músicas</p>
        </div>

        <!-- Lista de Resultados -->
        <div class="music-popup__results" id="musicResults">
            <!-- Estado inicial -->
            <div class="music-popup__empty" id="musicEmpty">
                <svg class="music-popup__empty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <path d="M9 9H9.01M15 9H15.01M9 15C9 15 10 17 12 17C14 17 15 15 15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <p class="music-popup__empty-text">Busque por sua música favorita</p>
                <p class="music-popup__empty-subtitle">Use o campo acima para encontrar</p>
            </div>

            <!-- Resultados (serão gerados dinamicamente) -->
            <div class="music-results-list" id="musicResultsList" style="display: none;">
                <!-- Exemplo de resultado -->
            </div>
        </div>

        <!-- Rodapé com informações -->
        <div class="music-popup__footer">
            <div class="music-popup__info-box">
                <div class="music-popup__info-item">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span><strong>Fila Normal:</strong> Grátis</span>
                </div>

                <!-- <div class="music-popup__info-item">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 8L12 3L3 8M21 8L12 13M21 8V16L12 21M12 13L3 8M12 13V21M3 8V16L12 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span><strong>Leilão:</strong> Toca antes!</span>
                </div> -->

            </div>
        </div>
    </div>
</div>
