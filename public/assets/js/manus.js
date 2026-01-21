const overlay = document.querySelector('.custom-popup__overlay');
// informações do atual popup
var current_popup = {};
clearCurrentPopup();

function clearCurrentPopup() {
    current_popup =
    {
        'popup':                 null,
        'popup_class':           null,
        'popup_trigger_element': null,
    }
}

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

function openAllPopup() {

    let class_str = current_popup.popup_class;
    let popup     = current_popup.popup;

    popup.classList.add(class_str);
    document.body.style.overflow = 'hidden'; // Prevenir scroll do body
}

function closeAllPopup() {

    let class_str = current_popup.popup_class;
    let popup     = current_popup.popup;

    popup.classList.remove(class_str);
    document.body.style.overflow = ''; // Restaurar scroll do body
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

    if (!popup) return;

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
// menu mobile
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

function initPopupTrigger(popup_id, itens_trigger_class, callback = false) {
    const popup = document.getElementById(popup_id);

    if (!popup) return;

    const popup_close_btn = popup.querySelector('.popup_close');
    const popup_class     = 'custom-popup--active';

    let itens_trigger = document.querySelectorAll(`.${itens_trigger_class}`);
    for (const item of itens_trigger) {
        item.addEventListener('click', function() {

            current_popup.popup                 = popup;
            current_popup.popup_class           = popup_class;
            current_popup.popup_trigger_element = item;

            if (callback) callback();

            openAllPopup();
        });
    }

    // Fechar popup - botão X
    if (popup_close_btn) {
        popup_close_btn.addEventListener('click', function() {
            closeAllPopup();
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const ordersBtn             = document.getElementById('ordersBtn');
    const ordersPopup           = document.getElementById('ordersPopup');
    const closeOrdersPopup      = document.getElementById('closeOrdersPopup');
    const ordersOverlay         = document.getElementById('ordersOverlay');
    const ordersBadge           = document.getElementById('ordersBadge');
    const ordersList            = document.getElementById('ordersList');
    const ordersTotal           = document.getElementById('ordersTotal');

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

        if (!ordersList || !ordersBadge) return;

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

    if(!productPopup || !productPopup.classList) return;

    productPopup.classList.remove('product-popup--active');
    document.body.style.overflow = '';
}

// Função para adicionar à comanda
function updateOrdersList() {
    const ordersList = document.getElementById('ordersList');

    if (typeof orders_data == 'undefined' || !orders_data || orders_data.length == 0) return;

    if (!ordersList) return;

    if (document.querySelector('#ordersBadge'))
        document.querySelector('#ordersBadge').innerHTML = orders_data.length

    ordersList.innerHTML = '';

    for (const order of orders_data) {

        const orderItem                = document.createElement('div');
        orderItem.className            = 'order-item';
        orderItem.innerHTML = `
            <div class="order-item__header">
                <h4 class="order-item__name">${order.product_name}</h4>
                <p>${order.observations?order.observations:''}</p>
            </div>
            <div class="order-item__details">
                <div class="order-item__quantity">
                    <span class="order-item__qty-value">(X${order.quantity})</span>
                </div>
                <div class="order-item__badges">
                    <span class="order-item__badge order-item__badge--drinks">${order.customer_name}</span>
                    <span class="order-item__badge order-item__badge--bebidas">${order.status_description}</span>
                </div>
            </div>
            <!-- <div class="order-item__price">
                <span class="order-item__total-price only_waiter">R$ ${parseFloat(order.total).toFixed(2).replace('.', ',')}</span>
            </div> -->
        `;

        ordersList.appendChild(orderItem);
    }
}

function getTablesData() {
    let current_table_number = current_popup.popup_trigger_element.getAttribute('table_number');

    let table_data = tables_data.filter(table => {
        if (table.number == current_table_number) {
            return table;
        }
    });

    console.log('table_data', table_data);

    return table_data[0];
}

function updateOrdersListAdmin() {
    const ordersList           = document.getElementById('ordersListAdmin');
    const table_command_number = document.getElementById('command_table_number');
    const more_order_values    = document.getElementById('more_order_values');

    let current_table_number = current_popup.popup_trigger_element.getAttribute('table_number');
    let table_data           = getTablesData()         ?? {};
    let orders_data          = table_data['orders']    ?? [];
    let customers_data       = table_data['customers'] ?? [];

    let close_table_customer = document.getElementById('close_table_customer');

    close_table_customer.innerHTML = `
        <option value="">Selecione o cliente</option>
        <option value="all">todos os clientes</option>
    `;

    for (const customer of customers_data) {
        close_table_customer.innerHTML += `
            <option value="${customer.account_id}">${customer.name} (R$ ${customer.total_consumed})</option>
        `;
    }

    table_command_number.innerHTML = `Mesa ${current_table_number}  | R$ ${table_data['total_formated']}`;
    more_order_values.innerHTML    = `PIX: R$ ${table_data['total_formated']} | C. Credito: R$ ${table_data['total_credit_card_formated']} | C. Debito: R$ ${table_data['total_debit_card_formated']}`;

    if (typeof orders_data == 'undefined' || !orders_data || orders_data.length == 0){
        ordersList.innerHTML = `<div class="order-item">
            <div class="order-item__header">
                <h4 class="order-item__name">AINDA NÃO HÁ PEDIDOS</h4>
            </div>
        </div>`;
        return;
    }

    ordersList.innerHTML = '';

    for (const order of orders_data) {

        let order_action_btn = '';
        let order_remove_btn   = '';
        if (order.is_new) {
         order_action_btn = `
                <button class="product-popup__qty-btn" onclick="concludeOrderAdmin('${order.id}')">
                    <svg fill="#ffffff" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="256px" height="256px" viewBox="-16.56 -16.56 105.12 105.12" enable-background="new 0 0 72 72" xml:space="preserve" stroke="#ffffff" stroke-width="0.576"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M24.014,70.462c-2.617,0-5.073-1.016-6.917-2.859L2.175,53.877c-1.908-1.906-2.926-4.364-2.926-6.979 s1.018-5.072,2.866-6.92c1.849-1.849,4.307-2.866,6.921-2.866c2.591,0,5.029,1,6.872,2.818l8.102,7.109L55.861,4.618 c0.057-0.075,0.119-0.146,0.186-0.213c1.849-1.85,4.307-2.867,6.921-2.867s5.072,1.018,6.921,2.867 c3.784,3.784,3.815,9.923,0.093,13.747L31.697,67.416c-0.051,0.065-0.106,0.128-0.165,0.188c-1.914,1.912-4.498,2.926-7.214,2.854 C24.216,70.46,24.116,70.462,24.014,70.462z M9.037,41.112c-1.546,0-2.999,0.602-4.093,1.695C3.851,43.9,3.25,45.353,3.25,46.898 s0.602,3,1.694,4.093l14.922,13.726c1.148,1.146,2.6,1.914,4.148,1.914l0.227,0.164c0.05,0,0.1,0,0.151,0l0.221-0.164 c1.51,0,2.929-0.654,4.008-1.69l38.275-49.294c0.051-0.065,0.105-0.148,0.165-0.207c2.256-2.258,2.256-5.939,0-8.195 c-1.094-1.094-2.547-1.701-4.093-1.701c-1.502,0-2.917,0.566-3.999,1.602L25.914,51.169c-0.335,0.445-0.84,0.73-1.394,0.787 c-0.551,0.057-1.106-0.118-1.525-0.486l-9.771-8.573c-0.032-0.028-0.064-0.058-0.095-0.089 C12.036,41.714,10.583,41.112,9.037,41.112z"></path> </g> </g></svg>
                </button>
            `;
         order_remove_btn = `
                <button class="product-popup__qty-btn" onclick="removeOrderAdmin('${order.id}')">
                    <svg width="74px" height="74px" viewBox="-2.88 -2.88 29.76 29.76" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.096"></g><g id="SVGRepo_iconCarrier"> <path d="M8.00386 9.41816C7.61333 9.02763 7.61334 8.39447 8.00386 8.00395C8.39438 7.61342 9.02755 7.61342 9.41807 8.00395L12.0057 10.5916L14.5907 8.00657C14.9813 7.61605 15.6144 7.61605 16.0049 8.00657C16.3955 8.3971 16.3955 9.03026 16.0049 9.42079L13.4199 12.0058L16.0039 14.5897C16.3944 14.9803 16.3944 15.6134 16.0039 16.0039C15.6133 16.3945 14.9802 16.3945 14.5896 16.0039L12.0057 13.42L9.42097 16.0048C9.03045 16.3953 8.39728 16.3953 8.00676 16.0048C7.61624 15.6142 7.61624 14.9811 8.00676 14.5905L10.5915 12.0058L8.00386 9.41816Z" fill="#ffffff"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12ZM3.00683 12C3.00683 16.9668 7.03321 20.9932 12 20.9932C16.9668 20.9932 20.9932 16.9668 20.9932 12C20.9932 7.03321 16.9668 3.00683 12 3.00683C7.03321 3.00683 3.00683 7.03321 3.00683 12Z" fill="#ffffff"></path> </g></svg>
                </button>
            `;
        }

        if (!order.is_new) {
            order_action_btn = `
                    <button class="product-popup__qty-btn" onclick="repeatOrder('${order.product_id}', ${order.account_id})">
                        <svg viewBox="-4.8 -4.8 33.60 33.60" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.048"></g><g id="SVGRepo_iconCarrier"> <path d="M9.5 19.75C9.91421 19.75 10.25 19.4142 10.25 19C10.25 18.5858 9.91421 18.25 9.5 18.25V19.75ZM11 5V5.75C11.3033 5.75 11.5768 5.56727 11.6929 5.28701C11.809 5.00676 11.7448 4.68417 11.5303 4.46967L11 5ZM9.53033 2.46967C9.23744 2.17678 8.76256 2.17678 8.46967 2.46967C8.17678 2.76256 8.17678 3.23744 8.46967 3.53033L9.53033 2.46967ZM9.5 18.25H9.00028V19.75H9.5V18.25ZM9 5.75H11V4.25H9V5.75ZM11.5303 4.46967L9.53033 2.46967L8.46967 3.53033L10.4697 5.53033L11.5303 4.46967ZM1.25 12C1.25 16.2802 4.72011 19.75 9.00028 19.75V18.25C5.54846 18.25 2.75 15.4517 2.75 12H1.25ZM2.75 12C2.75 8.54822 5.54822 5.75 9 5.75V4.25C4.71979 4.25 1.25 7.71979 1.25 12H2.75Z" fill="#ffffff"></path> <path d="M13 19V18.25C12.6967 18.25 12.4232 18.4327 12.3071 18.713C12.191 18.9932 12.2552 19.3158 12.4697 19.5303L13 19ZM14.4697 21.5303C14.7626 21.8232 15.2374 21.8232 15.5303 21.5303C15.8232 21.2374 15.8232 20.7626 15.5303 20.4697L14.4697 21.5303ZM14.5 4.25C14.0858 4.25 13.75 4.58579 13.75 5C13.75 5.41421 14.0858 5.75 14.5 5.75V4.25ZM15 18.25H13V19.75H15V18.25ZM12.4697 19.5303L14.4697 21.5303L15.5303 20.4697L13.5303 18.4697L12.4697 19.5303ZM14.5 5.75H15V4.25H14.5V5.75ZM21.25 12C21.25 15.4518 18.4518 18.25 15 18.25V19.75C19.2802 19.75 22.75 16.2802 22.75 12H21.25ZM22.75 12C22.75 7.71979 19.2802 4.25 15 4.25V5.75C18.4518 5.75 21.25 8.54822 21.25 12H22.75Z" fill="#ffffff"></path> <path d="M10.5 11.5L12 10V14" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </button>
                `;
        }

        const orderItem                = document.createElement('div');
        orderItem.className            = 'order-item';
        orderItem.innerHTML = `
            <div class="order-item__header">
                <h4 class="order-item__name">${order.product_name}</h4>
                <p>${order.observations?order.observations:''}</p>
                <p>${order.minutes?'(' + order.minutes + ' min)':''}</p>
            </div>
            <div class="order-item__details">
                <div class="order-item__quantity">
                    <span class="order-item__qty-value">(X${order.quantity})</span>
                </div>
                <div class="order-item__badges">
                    <span class="order-item__badge order-item__badge--drinks">${order.customer_name}</span>
                    <span class="order-item__badge order-item__badge--bebidas">${order.status_description}</span>
                </div>
            </div>
            <div class="order-item__price">
                <span class="order-item__total-price only_waiter">R$ ${parseFloat(order.total).toFixed(2).replace('.', ',')}</span>

                ${order_remove_btn}
                ${order_action_btn}

            </div>
        `;

        ordersList.appendChild(orderItem);
    }
}

function updateTablesList() {
    let container = document.getElementById('tables_content');

    if (!container) return;

    if (typeof tables_data == 'undefined' || !tables_data || tables_data.length == 0) {
        return;
    }

    let temp_items = container.querySelectorAll('.temp-item');
    temp_items.forEach(item => item.remove());

    Object.values(tables_data).forEach(table => {
        let article = document.createElement('article');

        article.classList.add('menu-item');
        article.classList.add('temp-item');

        article.innerHTML += getTablesTemplate(table);

        container.appendChild(article);
    });

    initPopupTrigger('ordersPopupAdmin',   'table_command', updateOrdersListAdmin);
    initPopupTrigger('newOrderPopupAdmin', 'table_new_order', updateProductPopup);
}

function createNewTable() {
    let select_table_number = document.getElementById('new_table_number');
    let table_number        = select_table_number.value;
    let name_input          = document.getElementById('name');

    if (!table_number) {
        alert('Selecione o número da mesa');
        return;
    }

    if (!name_input.value) {
        alert('Informe o nome do cliente');
        return;
    }

    let url    = '/api/new-account'
    let params = {
        name:         name_input.value,
        code:         1234,
        table_number: table_number,
        is_admin:     true,
    };

    sendRequestDefault(url, function (response) {

        console.log('response', response);

        if(!response || !response.success){
            customAlert(response.message ?? 'Erro desconhecido!', 'Ops não foi possivel registrar a mesa!');
            return;
        }

        tables_data = response.data.tables;

        updateTablesList(table_number);

        customAlert('Mesa criada/atualizada com sucesso!', 'Sucesso!')

        select_table_number.value = '';
        name_input.value          = '';

    }, params);

}

function getTablesTemplate(table) {

    let customers_name = '';
    for (const customer of table.customers)
        customers_name += `<span class="menu-item__badge menu-item__badge--drinks table_new_order" table_number="${table['number']}" account_id="${customer.account_id}">${customer.name} (R$ ${customer.total_consumed})</span>`;

    return `
        <div class="menu-item__content">
            <span class="header__orders-badge-admin">${table['new_order_qtd']}</span>
            <div class="menu-item__header">
                <h4 class="menu-item__name">Mesa: ${table['number']}</h4>
                <span class="menu-item__price">R$ ${table['total_formated']}</span>
            </div>
            ${customers_name}

            <div class="product-popup__quantity-controls">
                <button class="product-popup__qty-btn table_command" table_number="${table['number']}" >
                    <svg class="header__orders-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        <path d="M7 12H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        <path d="M7 17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                </button>

                <button class="product-popup__qty-btn table_new_order" table_number="${table['number']}">
                    +
                </button>
            </div>

        </div>
    `;
}

/**
 * atualiza os dados do popup com base na mesa selecionada
 *
 * @param   {int|null}  table_number
 *
 * @return  {void}
 */
function updateProductPopup(table_number = null) {
    let current_account_id   = current_popup.popup_trigger_element.getAttribute('account_id');
    let table_data           = getTablesData() ?? {};
    let popup                = current_popup.popup;
    let order_title          = popup.querySelector('#new_order_title');
    let select_account       = popup.querySelector('#account_id');
    let btn_plus             = popup.querySelector('#productQtyPlus');
    let btn_minus            = popup.querySelector('#productQtyMinus');

    // verificando a existencia do id da conta ou definindo em
    // caso de mesa com apenas 1 cliente
    if (!current_account_id && table_data.customers.length == 1)
        current_account_id = table_data.customers['0']['account_id'];

    // resetar valores
    clearBadgeCheckbox();
    searchItens('search_item_products', {value: ''});

    popup.querySelector('#quantity').value             = 1;
    popup.querySelector('#observations').value         = '';
    popup.querySelector('#input_search_product').value = '';
    popup.querySelector('#order_total').innerHTML      = 'R$ 0,00';

    order_title.innerHTML     = `Adicionar pedido Mesa ${table_data.number}`;
    select_account.innerHTML = '<option value="">Selecione o cliente</option>';

    for (const customer of table_data.customers)
        select_account.innerHTML += `<option value="${customer.account_id}">${customer.name}</option>`

    // se houver id da conta ja definido
    if (current_account_id) select_account.value = current_account_id;

    if (!btn_plus.classList.contains('triggered'))
        btn_plus.addEventListener('click', function() {
        updateQuantityAndTotal(true);
    });

    if (!btn_minus.classList.contains('triggered'))
        btn_minus.addEventListener('click', function() {
        updateQuantityAndTotal(false);
    });

    btn_minus.classList.add('triggered');
    btn_plus.classList.add('triggered');
}

function updateQuantityAndTotal(is_plus) {

    let popup          = current_popup.popup;
    let input_quantity = popup.querySelector('#quantity');

    value = parseInt(input_quantity.value);

    let new_quantity     = is_plus? (value+1) : (value-1);
    let is_invalid_plus  = (new_quantity == 100);
    let is_invalid_minus = (new_quantity == 0);

    if (is_invalid_plus || is_invalid_minus) return;

    if(!calculateAndSetTotal(new_quantity)) return;

    input_quantity.value = new_quantity;
}

function calculateAndSetTotal(new_quantity) {
    let popup               = current_popup.popup;
    let total_text          = popup.querySelector('#order_total');
    let total_input         = popup.querySelector('#total');
    let checkbox_product_id = popup.querySelector('input[type="checkbox"]:checked');

    let product = products_data.find(product => product.id == checkbox_product_id.value);

    if (!product){
        alert('produto não selecionado');
        return false;
    }

    let total = parseFloat(product.price) * new_quantity;

    setTotalText(total_text, total);
    total_input.value = total;

    return total;
}

function setTotalText(element, total) {
    element.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
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
    const productAddBtnAdmin  = document.getElementById('productAddBtnAdmin');

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
    if (productAddBtn) productAddBtn.addEventListener('click', async function() {

        registerPopupData(productPopup);

        if (!getUserData()) {
            popupFirstAccess();
            customAlert('Para realizar um pedido, é necessario primeiro se identificar', 'OPS...');
            return;
        }

        registerOrder(productPopup);
    });
}); // Aguardar 500ms para garantir que o DOM esteja pronto


