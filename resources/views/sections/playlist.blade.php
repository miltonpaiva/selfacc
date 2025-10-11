<!-- PLAYLIST SECTION -->
<section class="playlist" id="playlist">
    <div class="playlist__container">
        <div class="playlist__header">
            <div class="playlist__header-content">
                <h3 class="playlist__title">Playlist Ao Vivo</h3>
                <p class="playlist__subtitle">Controle a trilha sonora da sua experiência na praia</p>
            </div>
            <a href="#pedir-musica" class="btn btn--secondary">
                <span class="btn__text">Peça Sua Música</span>
            </a>
        </div>

        <!-- Player Atual -->
        <div class="playlist__player" id="playing_div">
            <div class="playlist__player-cover">
                <div class="playlist__player-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                        <path d="M10 8L16 12L10 16V8Z" fill="currentColor"/>
                    </svg>
                </div>
            </div>
            <div class="playlist__player-info">
                <h4 class="playlist__player-song">...</h4>
                <p class="playlist__player-artist">...</p>
                <div class="playlist__player-badges">
                    <span class="playlist__badge playlist__badge--playing">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 5V19M16 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Tocando Agora
                    </span>
                    <span class="playlist__badge playlist__badge--user">👤 ...</span>
                </div>
            </div>
            <div class="playlist__player-progress">
                <div class="playlist__player-progress-bar">
                    <div class="playlist__player-progress-fill" style="width: 0%;"></div>
                </div>
                <div class="playlist__player-time">
                    <span>00:00</span>
                    <span>00:00</span>
                </div>
            </div>
        </div>

        <!-- Lista de Músicas -->
        <div class="playlist__list" id="queue_list">
            <h4 class="playlist__list-title">Próximas Músicas</h4>

            <div class="playlist-item">
                <div class="playlist-item__number">0</div>
                <div class="playlist-item__info">
                    <h5 class="playlist-item__song">...</h5>
                    <p class="playlist-item__artist">...</p>
                </div>
                <div class="playlist-item__badges">
                    <span class="playlist__badge playlist__badge--user">👤 ...</span>
                    <span class="playlist__badge playlist__badge--auction">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 8L12 3L3 8M21 8L12 13M21 8V16L12 21M12 13L3 8M12 13V21M3 8V16L12 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Leilão
                    </span>
                </div>
                <div class="playlist-item__duration">00:00</div>
            </div>

        </div>
    </div>
</section>
