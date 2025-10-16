const overlay = document.querySelector('.custom-popup__overlay');

// verifica as teclas digitadas
function handleKeydown(e) {
   if (e.key === 'Escape') closePopup(popup);
}

//fecha o popup e remove os gatilhos
function closePopup(popup) {
    popup.classList.remove('custom-popup--active');
    popup.classList.remove('custom-popup--active--secondary');
    document.body.style.overflow = '';

    overlay.removeEventListener('click', function () {
        closePopup(popup);
    });

    document.removeEventListener('keydown', handleKeydown);

    returnAllPririty();

    let has_active_popups = (document.querySelectorAll('.custom-popup--active').length > 0);
    if (!has_active_popups) overlay.style.display='none';
}

// adiciona gatilhos para fechar o popup
function closePopupTriggers(popup) {

    overlay.style.display='flex';

    document.addEventListener('keydown', handleKeydown);

    overlay.addEventListener('click', function () {
        closePopup(popup);
    });

    if (popup.querySelector('.close_popup'))
        popup.querySelector('.close_popup').addEventListener('click', function () {
            closePopup(popup);
        });
}

function secondaryAll() {
    let popups_active = document.querySelectorAll('.custom-popup--active');
    for (const popup of popups_active) {
        popup.classList.add('custom-popup--active--secondary');
    }
}

function returnAllPririty() {
    let popups_active = document.querySelectorAll('.custom-popup--active--secondary');
    for (const popup of popups_active) {
        popup.classList.remove('custom-popup--active--secondary');
    }
}

// Menu Toggle para Mobile
// ========================================
// CUSTOM POPUPS (Alert, Confirm, Prompt)
// ========================================

// Função customAlert
function customAlert(message, title = 'Sucesso!') {

    secondaryAll();

    let popup = document.querySelector('#customAlert');

    const titleEl   = popup.querySelector('#alertTitle');
    const messageEl = popup.querySelector('#alertMessage');

    titleEl.textContent   = title;
    messageEl.textContent = message;

    popup.classList.add('custom-popup--active');
    popup.classList.remove('custom-popup--active--secondary');
    document.body.style.overflow = 'hidden';
    overlay.style.display='flex'

    closePopupTriggers(popup);
}

function popupFirstAccess() {
    let popup = document.querySelector('#popup_first_access');

    // const titleEl   = popup.querySelector('#alertTitle');
    // const messageEl = popup.querySelector('#alertMessage');

    // titleEl.textContent   = title;
    // messageEl.textContent = message;

    popup.classList.add('custom-popup--active');
    document.body.style.overflow = 'hidden';

    closePopupTriggers(popup);

    document.querySelector('#first_access_btn_ok').addEventListener('click', function () {
        registerPopupData(popup);
        registerCustomer(popup);
    })
}

// Função customConfirm
function customConfirm(message, title = 'Confirmação') {
    return new Promise((resolve) => {
        const popup     = document.getElementById('customConfirm');

        if (!popup) return resolve(false);

        const titleEl   = document.getElementById('confirmTitle');
        const messageEl = document.getElementById('confirmMessage');
        const okBtn     = document.getElementById('confirmOkBtn');
        const cancelBtn = document.getElementById('confirmCancelBtn');

        if (titleEl)  titleEl.textContent = title;
        if (messageEl)  messageEl.textContent = message;

        popup.classList.add('custom-popup--active');
        document.body.style.overflow = 'hidden';

        function close(result) {
            popup.classList.remove('custom-popup--active');
            document.body.style.overflow = '';
            okBtn.removeEventListener('click', handleOk);
            cancelBtn.removeEventListener('click', handleCancel);
            overlay.removeEventListener('click', handleCancel);
            document.removeEventListener('keydown', handleKeydown);
            resolve(result);
        }

        function handleOk() {
            close(true);
        }

        function handleCancel() {
            close(false);
        }

        function handleKeydown(e) {
            if (e.key === 'Enter') {
                close(true);
            } else if (e.key === 'Escape') {
                close(false);
            }
        }

        okBtn.addEventListener('click', handleOk);
        cancelBtn.addEventListener('click', handleCancel);
        overlay.addEventListener('click', handleCancel);
        document.addEventListener('keydown', handleKeydown);
    });
}

// Função customPrompt
function customPrompt(message, title = 'Digite o valor', defaultValue = '') {
    return new Promise((resolve) => {
        const popup = document.getElementById('customPrompt');
        const titleEl = document.getElementById('promptTitle');
        const messageEl = document.getElementById('promptMessage');
        const inputEl = document.getElementById('promptInput');
        const okBtn = document.getElementById('promptOkBtn');
        const cancelBtn = document.getElementById('promptCancelBtn');
        
        titleEl.textContent = title;
        messageEl.textContent = message;
        inputEl.value = defaultValue;
        
        popup.classList.add('custom-popup--active');
        document.body.style.overflow = 'hidden';
        
        // Focar no input após animação
        setTimeout(() => {
            inputEl.focus();
            inputEl.select();
        }, 100);
        
        function close(result) {
            popup.classList.remove('custom-popup--active');
            document.body.style.overflow = '';
            okBtn.removeEventListener('click', handleOk);
            cancelBtn.removeEventListener('click', handleCancel);
            overlay.removeEventListener('click', handleCancel);
            document.removeEventListener('keydown', handleKeydown);
            resolve(result);
        }
        
        function handleOk() {
            const value = inputEl.value.trim();
            close(value || null);
        }
        
        function handleCancel() {
            close(null);
        }
        
        function handleKeydown(e) {
            if (e.key === 'Enter') {
                const value = inputEl.value.trim();
                close(value || null);
            } else if (e.key === 'Escape') {
                close(null);
            }
        }
        
        okBtn.addEventListener('click', handleOk);
        cancelBtn.addEventListener('click', handleCancel);
        overlay.addEventListener('click', handleCancel);
        document.addEventListener('keydown', handleKeydown);
    });
}


// ========================================
// MAIN SCRIPT
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.header__menu-toggle');
    const nav = document.querySelector('.nav');
    const navLinks = document.querySelectorAll('.nav__link');
    
    // Toggle menu
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            nav.classList.toggle('nav--active');
            
            // Animação do ícone hamburger
            const icons = menuToggle.querySelectorAll('.header__menu-icon');
            if (nav.classList.contains('nav--active')) {
                icons[0].style.transform = 'rotate(45deg) translateY(8px)';
                icons[1].style.opacity = '0';
                icons[2].style.transform = 'rotate(-45deg) translateY(-8px)';
            } else {
                icons[0].style.transform = 'none';
                icons[1].style.opacity = '1';
                icons[2].style.transform = 'none';
            }
        });
    }
    
    // Fechar menu ao clicar em um link
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                nav.classList.remove('nav--active');
                const icons = menuToggle.querySelectorAll('.header__menu-icon');
                icons[0].style.transform = 'none';
                icons[1].style.opacity = '1';
                icons[2].style.transform = 'none';
            }
        });
    });
    
    // Fechar menu ao clicar fora
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.header__nav') && nav.classList.contains('nav--active')) {
            nav.classList.remove('nav--active');
            const icons = menuToggle.querySelectorAll('.header__menu-icon');
            icons[0].style.transform = 'none';
            icons[1].style.opacity = '1';
            icons[2].style.transform = 'none';
        }
    });
    
    // Smooth scroll para links internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '#playlist-interativa' && href !== '#lazer-atividades') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const headerHeight = document.querySelector('.header').offsetHeight;
                    const targetPosition = target.offsetTop - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
    
    // Lazy loading para imagens (quando forem adicionadas)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Adicionar classe ao header no scroll
    let lastScroll = 0;
    const header = document.querySelector('.header');
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            header.style.boxShadow = '0 2px 12px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.05)';
        }
        
        lastScroll = currentScroll;
    });
});


// ========================================
// MAIN SCRIPT
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.menu__filter');
    const menuItems = document.querySelectorAll('.menu-item');

    // Função para filtrar itens do cardápio
    function filterMenu(category) {
        menuItems.forEach((item, index) => {
            const itemCategory = item.getAttribute('data-category');

            if (category === 'todos' || itemCategory === category) {
                // Mostrar item com animação
                item.classList.remove('menu-item--hidden');
                item.style.animation = 'none';

                // Forçar reflow para reiniciar a animação
                void item.offsetWidth;

                // Aplicar animação escalonada
                item.style.animation = `slideUp 0.4s ease forwards ${index * 0.05}s`;
            } else {
                // Esconder item
                item.classList.add('menu-item--hidden');
            }
        });
    }

    // Event listeners para os botões de filtro
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');

            // Remover classe active de todos os botões
            filterButtons.forEach(btn => {
                btn.classList.remove('menu__filter--active');
            });

            // Adicionar classe active ao botão clicado
            this.classList.add('menu__filter--active');

            // Filtrar menu
            filterMenu(category);

            // Scroll suave para o grid de produtos
            const menuGrid = document.querySelector('.menu__grid');
            if (menuGrid) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const filtersHeight = document.querySelector('.menu__filters').offsetHeight;
                const targetPosition = menuGrid.offsetTop - headerHeight - filtersHeight - 20;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Adicionar contador de itens visíveis
    function updateItemCount() {
        const visibleItems = document.querySelectorAll('.menu-item:not(.menu-item--hidden)');
        const activeFilter = document.querySelector('.menu__filter--active');

        if (activeFilter) {
            const category = activeFilter.getAttribute('data-category');
            const count = visibleItems.length;

            // Você pode adicionar um elemento para mostrar a contagem
            // Por exemplo: "Mostrando 15 itens"
            console.log(`Categoria: ${category}, Itens visíveis: ${count}`);
        }
    }

    // Atualizar contagem ao filtrar
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            setTimeout(updateItemCount, 100);
        });
    });
    
    // Adicionar efeito de hover nos cards do menu
    menuItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.borderColor = 'var(--color-turquoise)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.borderColor = 'var(--color-gray-light)';
        });
    });
});


// ========================================
// MAIN SCRIPT
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('menuSearch');
    const clearButton = document.getElementById('clearSearch');
    const searchResults = document.getElementById('searchResults');
    const menuItems = document.querySelectorAll('.menu-item');
    const filterButtons = document.querySelectorAll('.menu__filter');
    
    let currentFilter = 'todos';
    
    // Função para normalizar texto (remover acentos)
    function normalizeText(text) {
        return text
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }
    
    // Função para buscar produtos
    function searchProducts(searchTerm) {
        const normalizedSearch = normalizeText(searchTerm);
        let visibleCount = 0;
        let totalInCategory = 0;
        
        menuItems.forEach((item, index) => {
            const itemCategory = item.getAttribute('data-category');
            const itemName = item.querySelector('.menu-item__name').textContent;
            const normalizedName = normalizeText(itemName);
            
            // Verificar se o item pertence à categoria ativa
            const matchesCategory = currentFilter === 'todos' || itemCategory === currentFilter;
            
            // Verificar se o nome contém o termo de busca
            const matchesSearch = normalizedName.includes(normalizedSearch);
            
            if (matchesCategory) {
                totalInCategory++;
            }
            
            if (matchesCategory && matchesSearch) {
                // Mostrar item
                item.classList.remove('menu-item--hidden');
                item.style.animation = 'none';
                void item.offsetWidth;
                item.style.animation = `slideUp 0.4s ease forwards ${visibleCount * 0.05}s`;
                visibleCount++;
                
                // Destacar texto encontrado (opcional)
                if (searchTerm.length > 0) {
                    highlightText(item, searchTerm);
                } else {
                    removeHighlight(item);
                }
            } else {
                // Esconder item
                item.classList.add('menu-item--hidden');
                removeHighlight(item);
            }
        });
        
        // Atualizar mensagem de resultados
        updateSearchResults(searchTerm, visibleCount, totalInCategory);
        
        return visibleCount;
    }
    
    // Função para destacar texto encontrado
    function highlightText(item, searchTerm) {
        const nameElement = item.querySelector('.menu-item__name');
        const originalText = nameElement.textContent;
        const normalizedOriginal = normalizeText(originalText);
        const normalizedSearch = normalizeText(searchTerm);
        
        const index = normalizedOriginal.indexOf(normalizedSearch);
        
        if (index !== -1) {
            const before = originalText.substring(0, index);
            const match = originalText.substring(index, index + searchTerm.length);
            const after = originalText.substring(index + searchTerm.length);
            
            nameElement.innerHTML = `${before}<span class="menu-item__name--highlight">${match}</span>${after}`;
        }
    }
    
    // Função para remover destaque
    function removeHighlight(item) {
        const nameElement = item.querySelector('.menu-item__name');
        const text = nameElement.textContent;
        nameElement.textContent = text;
    }
    
    // Função para atualizar mensagem de resultados
    function updateSearchResults(searchTerm, visibleCount, totalInCategory) {
        if (searchTerm.length === 0) {
            searchResults.textContent = '';
            searchResults.className = 'menu__search-results';
        } else if (visibleCount === 0) {
            searchResults.textContent = `Nenhum produto encontrado para "${searchTerm}"`;
            searchResults.className = 'menu__search-results menu__search-results--empty';
        } else if (visibleCount === 1) {
            searchResults.textContent = `1 produto encontrado`;
            searchResults.className = 'menu__search-results menu__search-results--highlight';
        } else {
            searchResults.textContent = `${visibleCount} produtos encontrados`;
            searchResults.className = 'menu__search-results menu__search-results--highlight';
        }
    }
    
    // Event listener para o campo de busca
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.trim();
            
            // Mostrar/esconder botão de limpar
            if (searchTerm.length > 0) {
                clearButton.classList.add('search-box__clear--visible');
            } else {
                clearButton.classList.remove('search-box__clear--visible');
            }
            
            // Executar busca
            searchProducts(searchTerm);
        });
        
        // Event listener para tecla Enter
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchInput.blur(); // Remove foco do input
            }
        });
    }
    
    // Event listener para botão de limpar
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            clearButton.classList.remove('search-box__clear--visible');
            searchProducts('');
            searchInput.focus();
        });
    }
    
    // Atualizar filtros para trabalhar com busca
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentFilter = this.getAttribute('data-category');
            
            // Se houver texto na busca, re-executar busca com novo filtro
            const searchTerm = searchInput ? searchInput.value.trim() : '';
            searchProducts(searchTerm);
        });
    });
});


// ========================================
// MAIN SCRIPT
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    const ordersBtn = document.getElementById('ordersBtn');
    const ordersPopup = document.getElementById('ordersPopup');
    const ordersOverlay = document.getElementById('ordersOverlay');
    const closeOrdersPopup = document.getElementById('closeOrdersPopup');
    const ordersBadge = document.getElementById('ordersBadge');
    const ordersList = document.getElementById('ordersList');
    const ordersTotal = document.getElementById('ordersTotal');

    // Abrir popup
    if (ordersBtn) {
        ordersBtn.addEventListener('click', function() {
            ordersPopup.classList.add('orders-popup--active');
            document.body.style.overflow = 'hidden'; // Prevenir scroll do body
        });
    }

    // Fechar popup - botão X
    if (closeOrdersPopup) {
        closeOrdersPopup.addEventListener('click', function() {
            closePopup();
        });
    }

    // Fechar popup - clique no overlay
    if (ordersOverlay) {
        ordersOverlay.addEventListener('click', function() {
            closePopup();
        });
    }

    // Fechar popup - tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && ordersPopup.classList.contains('orders-popup--active')) {
            closePopup();
        }
    });

    function closePopup() {
        ordersPopup.classList.remove('orders-popup--active');
        document.body.style.overflow = ''; // Restaurar scroll do body
    }

    // Atualizar contador de itens no badge
    function updateOrdersBadge() {

        if (!ordersList) return;

        const items = ordersList.querySelectorAll('.order-item');
        let totalItems = 0;

        items.forEach(item => {
            const qtyElement = item.querySelector('.order-item__qty-value');
            if (qtyElement) {
                totalItems += parseInt(qtyElement.textContent) || 0;
            }
        });

        ordersBadge.textContent = totalItems;

    }


    // Inicializar contadores
    updateOrdersBadge();
});

// ========================================
// POPUP DE BUSCA DE MÚSICAS
// ========================================

// Base de dados de músicas (exemplo)
const musicDatabase = [
    { id: 1, song: 'Evidências', artist: 'Chitãozinho & Xororó', duration: '5:00', genre: 'Sertanejo' },
    { id: 2, song: 'Aquarela do Brasil', artist: 'Gal Costa', duration: '3:45', genre: 'MPB' },
    { id: 3, song: 'Mas Que Nada', artist: 'Jorge Ben Jor', duration: '2:50', genre: 'Samba' },
    { id: 4, song: 'Garota de Ipanema', artist: 'Tom Jobim', duration: '5:20', genre: 'Bossa Nova' },
    { id: 5, song: 'Tempo Perdido', artist: 'Legião Urbana', duration: '4:35', genre: 'Rock' },
    { id: 6, song: 'Chega de Saudade', artist: 'João Gilberto', duration: '3:15', genre: 'Bossa Nova' },
    { id: 7, song: 'Asa Branca', artist: 'Luiz Gonzaga', duration: '3:50', genre: 'Forró' },
    { id: 8, song: 'Construção', artist: 'Chico Buarque', duration: '6:15', genre: 'MPB' },
    { id: 9, song: 'Sozinho', artist: 'Caetano Veloso', duration: '4:20', genre: 'MPB' },
    { id: 10, song: 'Aquele Abraço', artist: 'Gilberto Gil', duration: '3:30', genre: 'MPB' },
    { id: 11, song: 'Faroeste Caboclo', artist: 'Legião Urbana', duration: '9:05', genre: 'Rock' },
    { id: 12, song: 'Fico Assim Sem Você', artist: 'Adriana Calcanhotto', duration: '3:40', genre: 'Pop' },
    { id: 13, song: 'Exagerado', artist: 'Cazuza', duration: '4:25', genre: 'Rock' },
    { id: 14, song: 'Ainda é Cedo', artist: 'Legião Urbana', duration: '3:50', genre: 'Rock' },
    { id: 15, song: 'Roda Viva', artist: 'Chico Buarque', duration: '3:15', genre: 'MPB' },
    { id: 16, song: 'Alegria, Alegria', artist: 'Caetano Veloso', duration: '2:50', genre: 'Tropicália' },
    { id: 17, song: 'Apesar de Você', artist: 'Chico Buarque', duration: '3:45', genre: 'MPB' },
    { id: 18, song: 'Cálice', artist: 'Chico Buarque', duration: '5:10', genre: 'MPB' },
    { id: 19, song: 'Trem das Onze', artist: 'Adoniran Barbosa', duration: '2:45', genre: 'Samba' },
    { id: 20, song: 'Samba de Uma Nota Só', artist: 'Tom Jobim', duration: '2:30', genre: 'Bossa Nova' }
];

// ========================================
// MAIN SCRIPT
// ========================================

// Elementos do popup de música
const musicPopup       = document.getElementById('musicPopup');
const musicOverlay     = document.getElementById('musicOverlay');
const closeMusicPopup  = document.getElementById('closeMusicPopup');
const musicSearchInput = document.getElementById('musicSearchInput');
const clearMusicSearch = document.getElementById('clearMusicSearch');
const musicSearchInfo  = document.getElementById('musicSearchInfo');
const musicEmpty       = document.getElementById('musicEmpty');
const musicResultsList = document.getElementById('musicResultsList');

// Mostrar sem resultados
function showNoResults(query) {
    musicEmpty.style.display = 'flex';
    musicResultsList.style.display = 'none';
    musicSearchInfo.textContent = `Nenhuma música encontrada para "${query}"`;
    musicSearchInfo.style.color = 'var(--color-gold)';

    musicEmpty.innerHTML = `
        <svg class="music-popup__empty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
            <path d="M9 9H9.01M15 9H15.01M8 13C8 13 9 11 12 11C15 11 16 13 16 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <p class="music-popup__empty-text">Nenhuma música encontrada</p>
        <p class="music-popup__empty-subtitle">Tente buscar por outro nome ou artista</p>
    `;
}

// Função de busca
function searchMusic(query, results_data) {
    const normalizedQuery = normalizeText(query);
    const results         = results_data.filter(music => {
        const normalizedSong   = normalizeText(music.name);
        const normalizedArtist = normalizeText(music.artistss);
        const normalizedAlbum  = normalizeText(music.album_name);
        return normalizedSong.includes(normalizedQuery) || normalizedArtist.includes(normalizedQuery) || normalizedAlbum.includes(normalizedQuery);
    });

    displayResults(results, query);
}

// Normalizar texto (remover acentos e converter para minúsculas)
function normalizeText(text) {

    if (!text) return '';

    return text
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');
}

// Exibir resultados
function displayResults(results, query) {
    if (results.length === 0) {
        showNoResults(query);
        return;
    }

    musicEmpty.style.display       = 'none';
    musicResultsList.style.display = 'flex';
    musicSearchInfo.textContent    = `${results.length} música${results.length > 1 ? 's' : ''} encontrada${results.length > 1 ? 's' : ''}`;
    musicSearchInfo.style.color    = 'var(--color-turquoise)';

    // <button class="music-result__btn music-result__btn--bid" data-action="bid" data-music-id="${music.id}">
    //     <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    //         <path d="M21 8L12 3L3 8M21 8L12 13M21 8V16L12 21M12 13L3 8M12 13V21M3 8V16L12 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    //     </svg>
    //     Dar Lance
    // </button>

    // Gerar HTML dos resultados
    musicResultsList.innerHTML = results.map(music => `
        <div class="music-result" >
            <div class="music-result__cover" style="background-image: url(${music.image}); width: 80px; height: 80px; background-size: contain; filter: brightness(0.5);">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <path d="M9 10L9 18M15 6V18M15 6C15 6 13 7 12 7C11 7 9 6 9 6M15 6V10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="music-result__info">
                <h4 class="music-result__song">${highlightText(music.name, query)}</h4>
                <p class="music-result__artist">${highlightText(music.artists, query)} | ${highlightText(music.album_name, query)}</p>
                <p class="music-result__duration">${music.duration_min}</p>
            </div>
            <div class="music-result__actions">
                <button class="music-result__btn music-result__btn--queue" data-action="queue" onclick="addMusicToQueue('${music.id}', '${music.uri}', '${music.name.replaceAll("'", "")};${music.artists.replaceAll("'", "")}|${music.album_name.replaceAll("'", "")};${music.duration_min.replaceAll("'", "")}')">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M12 5L19 12M12 5L5 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Adicionar à Fila
                </button>
            </div>
        </div>
    `).join('');

    // Adicionar event listeners aos botões
    // attachButtonListeners();
}

// Destacar texto encontrado
function highlightText(text, query) {
    if (!query) return text;

    const regex = new RegExp(`(${query})`, 'gi');
    return text.replace(regex, '<mark style="background-color: rgba(0, 206, 209, 0.2); padding: 2px 4px; border-radius: 3px;">$1</mark>');
}

function closeMusicPopupFunc() {

    if(!musicPopup) return;

    musicPopup.classList.remove('music-popup--active');
    document.body.style.overflow = '';
    musicSearchInput.value = '';
    clearMusicSearch.classList.remove('music-search__clear--visible');
    showEmptyState();
}

// Mostrar estado vazio
function showEmptyState() {
    musicEmpty.style.display = 'flex';
    musicResultsList.style.display = 'none';
    musicSearchInfo.textContent = 'Digite para buscar músicas';
    musicSearchInfo.style.color = 'var(--color-gray-dark)';
}

document.addEventListener('DOMContentLoaded', function() {

    if(!musicPopup) return;

    // Botões "Peça Sua Música" (podem existir múltiplos)
    const requestMusicButtons = document.querySelectorAll('a[href="#pedir-musica"], a[href="#playlist-interativa"]');
    let search_btn = musicPopup.querySelector('#btn_search');

    // Abrir popup de música
    requestMusicButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            openMusicPopup();
            musicSearchInput.focus();
        });
    });

    search_btn.addEventListener('click', function(e) {
        searchMusicRequest(musicSearchInput.value);
    });

    function openMusicPopup() {
        musicPopup.classList.add('music-popup--active');
        document.body.style.overflow = 'hidden';
        musicSearchInput.focus();
    }

    // Fechar popup - botão X
    if (closeMusicPopup) {
        closeMusicPopup.addEventListener('click', closeMusicPopupFunc);
    }

    // Fechar popup - clique no overlay
    if (musicOverlay) {
        musicOverlay.addEventListener('click', closeMusicPopupFunc);
    }

    // Fechar popup - tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && musicPopup.classList.contains('music-popup--active')) {
            closeMusicPopupFunc();
        }
    });

    // Busca de músicas
    // const query = this.value.trim();
    // searchMusic(query);

    // Limpar busca
    if (clearMusicSearch) {
        clearMusicSearch.addEventListener('click', function() {
            musicSearchInput.value = '';
            clearMusicSearch.classList.remove('music-search__clear--visible');
            showEmptyState();
            musicSearchInput.focus();
        });
    }
});

// ========================================
// PRODUCT POPUP
// ========================================

// Executar após o DOM estar pronto

// Fechar popup
function closeProductPopup(productPopup) {

    if(!productPopup) return;

    productPopup.classList.remove('product-popup--active');
    document.body.style.overflow = '';
}

// Função para adicionar à comanda
function updateOrdersList() {
    const ordersList = document.getElementById('ordersList');

    if (!orders_data || orders_data.length == 0) return;

    if (document.querySelector('#ordersBadge')) 
        document.querySelector('#ordersBadge').innerHTML = orders_data.length

    ordersList.innerHTML = '';

    for (const order of orders_data) {

        const orderItem                = document.createElement('div');
        orderItem.className            = 'order-item';
        orderItem.innerHTML = `
            <div class="order-item__header">
                <h4 class="order-item__name">${order.product_name}</h4>
            </div>
            <div class="order-item__details">
                <div class="order-item__badges">
                    <span class="order-item__badge order-item__badge--drinks">${order.customer_name}</span>
                </div>
                <div class="order-item__quantity">
                    <span class="order-item__qty-value">${order.quantity}</span>
                </div>
            </div>
            <div class="order-item__price">
                <span class="order-item__total-price only_waiter">R$ ${parseFloat(order.total).toFixed(2).replace('.', ',')}</span>
            </div>
        `;

        ordersList.appendChild(orderItem);
    }
}


document.addEventListener('DOMContentLoaded', function() {
    const productPopup        = document.getElementById('productPopup');

    if(!productPopup) return;

    const productPopupClose   = document.getElementById('productPopupClose');

    const productTitle        = document.getElementById('productTitle');
    const productDescription  = document.getElementById('productDescription');
    const productPrice        = document.getElementById('productPrice');
    const productBadge        = document.getElementById('productBadge');
    const productQtyInput     = productPopup.querySelector('#quantity');
    const productQtyMinus     = document.getElementById('productQtyMinus');
    const productQtyPlus      = document.getElementById('productQtyPlus');
    const productObservations = productPopup.querySelector('#observations');
    const productCharCount    = document.getElementById('productCharCount');
    const productTotal        = document.getElementById('productTotal');
    const productTotalInput   = productPopup.querySelector('#total');
    const productIdInput      = productPopup.querySelector('#product_id');
    const productAddBtn       = document.getElementById('productAddBtn');

    let currentProduct = null;

    // Abrir popup ao clicar em um card de produto
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            const name          = this.querySelector('.menu-item__name').textContent;
            const description   = this.querySelector('.menu-item__description').textContent;
            const priceText     = this.querySelector('.menu-item__price').textContent;
            const price         = parseFloat(priceText.replace('R$', '').replace(',', '.').trim());
            const category      = this.querySelector('.menu-item__badge').textContent;
            const categoryClass = this.querySelector('.menu-item__badge').className;
            const product_id    = this.getAttribute('data-product_id');

            currentProduct = {
                product_id,
                name,
                description,
                price,
                category,
                categoryClass
            };

            currentProduct['qty']          = 1;
            currentProduct['observations'] = '';

            console.log('currentProduct', currentProduct);

            openProductPopup(currentProduct);
        });
    });

    // Abrir popup
    function openProductPopup(product) {
        productTitle.textContent       = product.name;
        productDescription.textContent = product.description;
        productPrice.textContent       = `R$ ${product.price.toFixed(2).replace('.', ',')}`;
        productBadge.textContent       = product.category;
        productIdInput.value           = product.product_id;

        // Resetar valores
        productQtyInput.value        = 1;
        productObservations.value    = '';
        productCharCount.textContent = '0';
        updateProductTotal();

        productPopup.classList.add('product-popup--active');
        document.body.style.overflow = 'hidden';
    }

    productPopupClose.addEventListener('click', function () {
        closeProductPopup(productPopup);
    });
    overlay.addEventListener('click', function () {
        closeProductPopup(productPopup);
    });

    // Fechar com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && productPopup.classList.contains('product-popup--active')) {
            closeProductPopup(productPopup);
        }
    });

    // Controles de quantidade
    productQtyMinus.addEventListener('click', function() {
        let qty = parseInt(productQtyInput.value);
        if (qty > 1) {
            productQtyInput.value = qty - 1;
            updateProductTotal();
        }
    });

    productQtyPlus.addEventListener('click', function() {
        let qty = parseInt(productQtyInput.value);
        if (qty < 99) {
            productQtyInput.value = qty + 1;
            updateProductTotal();
        }
    });

    // Contador de caracteres
    productObservations.addEventListener('input', function() {
        const count = this.value.length;
        productCharCount.textContent = count;

        if (count >= 200) {
            productCharCount.style.color = 'var(--color-gold)';
        } else {
            productCharCount.style.color = 'var(--color-gray-dark)';
        }

        currentProduct['observations'] = this.value;
    });

    // Atualizar total do produto
    function updateProductTotal() {
        if (!currentProduct) return;

        const qty                = parseInt(productQtyInput.value);
        const total              = currentProduct.price * qty;
        productTotal.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
        productTotalInput.value  = total;
        currentProduct['qty']    = parseInt(productQtyInput.value);
    }

    // Adicionar à comanda
    productAddBtn.addEventListener('click', async function() {

        registerPopupData(productPopup);

        if (!getUserData()) {
            popupFirstAccess();
            customAlert('Para realizar um pedido, é necessario primeiro se identificar', 'OPS...');
            return;
        }

        registerOrder(productPopup);
    });

    // Funções auxiliares (já existem no código, mas garantir que estão disponíveis)
    function updateOrdersBadge() {
        const ordersList = document.getElementById('ordersList');

        if (!ordersList) return;

        const ordersBadge = document.querySelector('.header__orders-badge');
        const items = ordersList.querySelectorAll('.order-item');
        
        let totalItems = 0;
        items.forEach(item => {
            const qty = parseInt(item.querySelector('.order-item__quantity').textContent);
            totalItems += qty;
        });
        
        if (ordersBadge) {
            ordersBadge.textContent = totalItems;
            
            if (totalItems === 0) {
                ordersBadge.style.display = 'none';
            } else {
                ordersBadge.style.display = 'flex';
            }
        }
    }
    
    function updateTotal() {
        const ordersList = document.getElementById('ordersList');

        if (!ordersList) return;

        const ordersTotal = document.getElementById('ordersTotal');
        const items = ordersList.querySelectorAll('.order-item');
        
        let total = 0;
        items.forEach(item => {
            const itemTotal = item.querySelector('.order-item__total').textContent;
            const value = parseFloat(itemTotal.replace('R$', '').replace(',', '.').trim());
            total += value;
        });
        
        if (ordersTotal) {
            ordersTotal.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
        }
    }
}); // Aguardar 500ms para garantir que o DOM esteja pronto

// ============================================
// SISTEMA DE PUSH NOTIFICATIONS NATIVAS
// ============================================

const PushNotifications = {
    // Estado da permissão
    permission: 'default',

    // Configurações
    config: {
        icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="%2300CED1"/><text x="50" y="65" font-size="50" text-anchor="middle" fill="white" font-family="Arial">P</text></svg>',
        badge: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50" fill="%23C4A76D"/></svg>',
        vibrate: [200, 100, 200],
        requireInteraction: false,
        silent: false
    },

    /**
     * Inicializar o sistema de notificações
     */
    init() {
        // Verificar se o navegador suporta notificações
        if (!('Notification' in window)) {
            console.warn('Este navegador não suporta notificações push');
            return false;
        }

        // Verificar permissão atual
        this.permission = Notification.permission;
        console.log('Permissão de notificações:', this.permission);

        // Se já tiver permissão, registrar service worker (opcional)
        if (this.permission === 'granted') {
            this.registerServiceWorker();
        }

        return true;
    },

    /**
     * Solicitar permissão para enviar notificações
     */
    async requestPermission() {
        if (!('Notification' in window)) {
            await customAlert(
                'Seu navegador não suporta notificações push. Por favor, use um navegador moderno como Chrome, Firefox ou Safari.',
                'Notificações Não Suportadas'
            );
            return false;
        }

        // Se já tiver permissão
        if (Notification.permission === 'granted') {
            await customAlert(
                'Você já autorizou as notificações! 🎉\n\nVocê receberá alertas sobre seus pedidos e músicas.',
                'Notificações Ativadas'
            );
            return true;
        }

        // Se já tiver negado
        if (Notification.permission === 'denied') {
            await customAlert(
                'Você bloqueou as notificações anteriormente.\n\nPara ativar, acesse as configurações do seu navegador e permita notificações para este site.',
                'Notificações Bloqueadas'
            );
            return false;
        }

        // Solicitar permissão
        try {
            const permission = await Notification.requestPermission();
            this.permission = permission;

            if (permission === 'granted') {
                // Enviar notificação de boas-vindas
                this.send({
                    title: '🎉 Notificações Ativadas!',
                    body: 'Você receberá alertas sobre seus pedidos e quando sua música tocar.',
                    tag: 'welcome'
                });

                // Registrar service worker
                this.registerServiceWorker();

                return true;
            } else {
                await customAlert(
                    'Você negou a permissão para notificações.\n\nVocê pode ativar depois nas configurações do navegador.',
                    'Permissão Negada'
                );
                return false;
            }
        } catch (error) {
            console.error('Erro ao solicitar permissão:', error);
            return false;
        }
    },
    
    /**
     * Registrar Service Worker (opcional, para notificações em background)
     */
    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                // Nota: Você precisaria criar um arquivo service-worker.js
                // const registration = await navigator.serviceWorker.register('/service-worker.js');
                // console.log('Service Worker registrado:', registration);
                console.log('Service Worker: Implementação futura');
            } catch (error) {
                console.error('Erro ao registrar Service Worker:', error);
            }
        }
    },
    
    /**
     * Enviar notificação
     * @param {Object} options - Opções da notificação
     */
    send(options) {
        // Verificar permissão
        if (Notification.permission !== 'granted') {
            console.warn('Permissão de notificação não concedida');
            return null;
        }
        
        // Configurações padrão
        const defaults = {
            icon: this.config.icon,
            badge: this.config.badge,
            vibrate: this.config.vibrate,
            requireInteraction: this.config.requireInteraction,
            silent: this.config.silent,
            timestamp: Date.now()
        };
        
        // Mesclar opções
        const notificationOptions = { ...defaults, ...options };
        
        // Criar notificação
        try {
            const notification = new Notification(options.title, notificationOptions);
            
            // Event listeners
            notification.onclick = (event) => {
                event.preventDefault();
                window.focus();
                notification.close();
                
                // Callback personalizado
                if (options.onClick) {
                    options.onClick(event);
                }
            };
            
            notification.onclose = () => {
                if (options.onClose) {
                    options.onClose();
                }
            };
            
            notification.onerror = (error) => {
                console.error('Erro na notificação:', error);
                if (options.onError) {
                    options.onError(error);
                }
            };
            
            // Auto-fechar após 5 segundos (se não for requireInteraction)
            if (!notificationOptions.requireInteraction) {
                setTimeout(() => {
                    notification.close();
                }, 5000);
            }
            
            return notification;
        } catch (error) {
            console.error('Erro ao criar notificação:', error);
            return null;
        }
    },
    
    /**
     * Notificação de pedido confirmado
     */
    notifyOrderConfirmed(orderNumber, total, items) {
        return this.send({
            title: '✅ Pedido Confirmado!',
            body: `Pedido #${orderNumber} - ${items} ${items === 1 ? 'item' : 'itens'}\nTotal: R$ ${total}\n\nEstamos preparando seu pedido!`,
            tag: `order-${orderNumber}`,
            icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="%2300CED1"/><path d="M30 50 L45 65 L70 35" stroke="white" stroke-width="8" fill="none" stroke-linecap="round"/></svg>',
            requireInteraction: true,
            data: {
                type: 'order',
                orderNumber: orderNumber
            },
            onClick: () => {
                // Abrir popup da comanda
                document.getElementById('ordersPopup').classList.add('orders-popup--active');
            }
        });
    },
    
    /**
     * Notificação de pedido pronto
     */
    notifyOrderReady(orderNumber) {
        return this.send({
            title: '🎉 Pedido Pronto!',
            body: `Seu pedido #${orderNumber} está pronto!\n\nVenha retirar na barraca.`,
            tag: `order-ready-${orderNumber}`,
            icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="%23C4A76D"/><text x="50" y="70" font-size="50" text-anchor="middle" fill="white">🎉</text></svg>',
            vibrate: [300, 100, 300, 100, 300],
            requireInteraction: true,
            data: {
                type: 'order-ready',
                orderNumber: orderNumber
            }
        });
    },
    
    /**
     * Notificação de música tocando
     */
    notifyMusicPlaying(musicName, artist) {
        return this.send({
            title: '🎵 Sua Música Está Tocando!',
            body: `${musicName}\n${artist}\n\nCurta o momento!`,
            tag: 'music-playing',
            icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="%23C4A76D"/><text x="50" y="70" font-size="50" text-anchor="middle" fill="white">🎵</text></svg>',
            vibrate: [200, 100, 200],
            data: {
                type: 'music',
                musicName: musicName,
                artist: artist
            },
            onClick: () => {
                // Scroll para a seção de playlist
                document.getElementById('playlist').scrollIntoView({ behavior: 'smooth' });
            }
        });
    },
    
    /**
     * Notificação de música próxima na fila
     */
    notifyMusicNext(musicName, position) {
        return this.send({
            title: '⏭️ Sua Música é a Próxima!',
            body: `${musicName}\n\nFaltam ${position} ${position === 1 ? 'música' : 'músicas'} para tocar a sua.`,
            tag: 'music-next',
            icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="%2300CED1"/><text x="50" y="70" font-size="50" text-anchor="middle" fill="white">⏭️</text></svg>',
            data: {
                type: 'music-next',
                musicName: musicName,
                position: position
            }
        });
    },
    
    /**
     * Notificação de leilão vencido
     */
    notifyAuctionWon(musicName, bidAmount) {
        return this.send({
            title: '🎪 Leilão Vencido!',
            body: `Você venceu o leilão!\n\n${musicName}\nLance: R$ ${bidAmount}\n\nSua música tocará em breve!`,
            tag: 'auction-won',
            icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="%23FFD700"/><text x="50" y="70" font-size="50" text-anchor="middle" fill="white">🎪</text></svg>',
            vibrate: [300, 100, 300, 100, 300],
            requireInteraction: true,
            data: {
                type: 'auction',
                musicName: musicName,
                bidAmount: bidAmount
            }
        });
    },
    
    /**
     * Notificação de leilão superado
     */
    notifyAuctionOutbid(musicName, newBidAmount) {
        return this.send({
            title: '⚠️ Lance Superado!',
            body: `Alguém deu um lance maior!\n\n${musicName}\nNovo lance: R$ ${newBidAmount}\n\nQuer dar um lance maior?`,
            tag: 'auction-outbid',
            icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="%23FF6B6B"/><text x="50" y="70" font-size="50" text-anchor="middle" fill="white">⚠️</text></svg>',
            vibrate: [200, 100, 200],
            data: {
                type: 'auction-outbid',
                musicName: musicName,
                newBidAmount: newBidAmount
            },
            onClick: () => {
                // Abrir popup de músicas
                document.getElementById('musicPopup').classList.add('music-popup--active');
            }
        });
    },
    
    /**
     * Notificação de promoção/oferta especial
     */
    notifyPromotion(title, message, discount) {
        return this.send({
            title: `🔥 ${title}`,
            body: `${message}\n\n${discount ? `Desconto: ${discount}` : 'Aproveite agora!'}`,
            tag: 'promotion',
            icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="%23FF6B6B"/><text x="50" y="70" font-size="50" text-anchor="middle" fill="white">🔥</text></svg>',
            requireInteraction: true,
            data: {
                type: 'promotion',
                discount: discount
            },
            onClick: () => {
                // Scroll para o cardápio
                document.getElementById('menu').scrollIntoView({ behavior: 'smooth' });
            }
        });
    },
    
    /**
     * Notificação personalizada
     */
    notifyCustom(title, body, options = {}) {
        return this.send({
            title: title,
            body: body,
            tag: options.tag || 'custom',
            icon: options.icon || this.config.icon,
            ...options
        });
    },
    
    /**
     * Verificar se as notificações estão habilitadas
     */
    isEnabled() {
        return Notification.permission === 'granted';
    },
    
    /**
     * Obter status da permissão
     */
    getPermissionStatus() {
        return Notification.permission;
    }
};

// ============================================
// INICIALIZAR NOTIFICAÇÕES AO CARREGAR
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    // Inicializar sistema de notificações
    PushNotifications.init();
    
    // Criar botão para ativar notificações (se ainda não tiver permissão)
    if (Notification.permission === 'default') {
        // Mostrar banner de notificações após 3 segundos
        setTimeout(() => {
            showNotificationBanner();
        }, 3000);
    }
});

/**
 * Mostrar banner para ativar notificações
 */
async function showNotificationBanner() {
    const confirmed = await customConfirm(
        'Quer receber notificações sobre seus pedidos e quando sua música tocar?\n\nVocê pode desativar a qualquer momento.',
        '🔔 Ativar Notificações?'
    );
    
    if (confirmed) {
        await PushNotifications.requestPermission();
    }
}

// ============================================
// INTEGRAÇÃO COM SISTEMA EXISTENTE
// ============================================

// Exemplo de uso nas funções existentes:

var exist_add_orders = (typeof addToOrders !== 'undefined');
// Quando adicionar item à comanda (modificar função existente)
const originalAddToOrders = null;
if (exist_add_orders) {
    addToOrders = function(product, quantity, observations) {
        // Chamar função original
        originalAddToOrders(product, quantity, observations);

        // Enviar notificação (opcional)
        if (PushNotifications.isEnabled()) {
            // Notificação silenciosa de item adicionado
            // PushNotifications.notifyCustom(
            //     '✅ Item Adicionado',
            //     `${product.name} (${quantity}x) foi adicionado à comanda.`,
            //     { silent: true, tag: 'item-added' }
            // );
        }
    };
}

// Expor objeto global para uso em outros scripts
window.PushNotifications = PushNotifications;

console.log('✅ Sistema de Push Notifications carregado');

