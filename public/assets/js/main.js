var popups_data   = {};

var current_popup = {};

const request = new URLSearchParams(window.location.search);

function verifyTableNumber() {
    let table_number = request.get('table_number');

    if (getUserData() && getUserData()['account'])
        table_number = getUserData()['account']['table_number'];

    if (!table_number) return;

    let selects_table_number = document.querySelectorAll('.table_number');

    for (const select of selects_table_number) {
        select.value = table_number;

        select.classList.add('disabled')
    }

    document.querySelector('#command_table_number').innerHTML = `Comanda Mesa ${table_number}`
}
verifyTableNumber();
updateOrdersList();

function verifyUserLogged() {

    if (user_logged && auth_data) return true;

    if (getUserData()) return true;

    return false;
}

function getUserData(force_update = false) {

    if (auth_data && !force_update) return auth_data;

    let auth_coockie = getCookie('auth_data');

    if (!auth_coockie) return null;

    let user_json = atob(auth_coockie);

    if (!user_json) return null;

    let user_data = JSON.parse(user_json);

    if (!user_data) return null;

    auth_data = user_data;

    return user_data;
}

function getCookie(cName) {
  const name          = cName + "=";
  const decodedCookie = decodeURIComponent(document.cookie);
  const cookieArray   = decodedCookie.split(';');
  let result = null;

  cookieArray.forEach(val => {
    let trimmedVal = val.trim(); // Remove leading/trailing whitespace
    if (trimmedVal.indexOf(name) === 0) {
      result = trimmedVal.substring(name.length);
    }
  });
  return result;
}

function registerPopupData(popup) {
    popups_data[popup.id] = returnPopupData(popup);
    console.log('popups_data', popups_data);
}

function registerCustomer(popup) {

    let url    = '/api/new-account'
    let params = popups_data[popup.id];

    sendRequestDefault(url, function (response) {

        console.log('response', response);

        if(!response || !response.success){
            customAlert(response.message ?? 'Erro desconhecido!', 'Ops não foi possivel registrar você!');
            return;
        }

        auth_data   = response.data
        orders_data = response.data.orders;

        customAlert('Sinta se a vontade para fazer seus pedidos ou se preferir solicite que um garçom venha lhe atender no botão abaixo. Lembre-se, a senha solicitada anteriormente será necessaria para fazer pedidos no site.', `É um prazer lhe conhecer ${response.data.customer.name}!`)
        closePopup(popup);
        updateOrdersList();
        verifyTableNumber();

    }, params);

}

function registerOrder(popup) {
    let url    = '/api/new-order'
    let params = popups_data[popup.id];
    params['account_id'] = auth_data.account.id;

    sendRequestDefault(url, function (response) {

        console.log('response', response);

        if(!response || !response.success){
            customAlert(response.message ?? 'Erro desconhecido!', 'Ops não foi possivel registrar o pedido!');
            return;
        }

        // Fechar popup
        closeProductPopup(popup);

        // Mostrar mensagem de sucesso
        customAlert(
            `${response.data.product_name} (${response.data.quantity}x) adicionado à comanda da mesa ${response.data.table_number} !${response.data.observations ? '\n\nObservações: ' + response.data.observations : ''}`,
            'Item Adicionado'
        );

        orders_data = response.data.orders;
        updateOrdersList();

    }, params);
}

function setPlaying() {
    document.querySelector('#playing_div').innerHTML = getPlayingTemplate(playing_data);
}
setPlaying();

function getPlayingTemplate(playing) {
    let user_name = `<span class="playlist__badge playlist__badge--user">👤 ...</span>`;
    user_name = '';

    return `
        <div class="playlist__player-cover">
            <div class="playlist__player-icon" style="background-image: url(${playing.item.image}); width: 120px; height: 120px; background-size: contain; filter: brightness(0.5);">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <path d="M10 8L16 12L10 16V8Z" fill="currentColor"/>
                </svg>
            </div>
            <div class="playlist__player-equalizer">
                <span class="playlist__player-bar"></span>
                <span class="playlist__player-bar"></span>
                <span class="playlist__player-bar"></span>
                <span class="playlist__player-bar"></span>
            </div>
        </div>
        <div class="playlist__player-info">
            <h4 class="playlist__player-song">${playing.item.name}</h4>
            <p class="playlist__player-artist">${playing.item.artists} | ${playing.item.album_name}</p>
            <div class="playlist__player-badges">
                <span class="playlist__badge playlist__badge--playing">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 5V19M16 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Tocando Agora
                </span>
                ${user_name}
            </div>
        </div>
        <div class="playlist__player-progress">
            <div class="playlist__player-progress-bar">
                <div class="playlist__player-progress-fill" style="width: ${playing.progress_percent}%;"></div>
            </div>
            <div class="playlist__player-time">
                <span>${playing.progress_min}</span>
                <span>${playing.duration_min}</span>
            </div>
        </div>
    `;
}

function setQueueList() {

    let queue_list = document.querySelector('#queue_list');

    queue_list.innerHTML = '';

    let index = 1;
    Object.values(queue_data.queue).forEach(queue_item => {

        if (index > 5) return;

        let item = document.createElement('div');
        item.classList.add('playlist-item');

        item.innerHTML = getQueueItemTemplate(queue_item, index);

        queue_list.appendChild(item);

        index++;
    });
}
setQueueList();

function getQueueItemTemplate(queue, index) {

    let user_name = '';
    if (queue.customer) {
        user_name = `<span class="playlist__badge playlist__badge--user">👤 ${queue.customer}</span>`;
    }

    let user_auction = `<span class="playlist__badge playlist__badge--auction">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 8L12 3L3 8M21 8L12 13M21 8V16L12 21M12 13L3 8M12 13V21M3 8V16L12 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Leilão
                </span>`;
    user_auction = '';

    return `
        <div class="playlist-item__number">${index}</div>
        <div class="playlist-item__info">
            <h5 class="playlist-item__song">${queue.name}</h5>
            <p class="playlist-item__artist">${queue.artists} | ${queue.album_name}</p>
        </div>
        <div class="playlist-item__badges">
            ${user_name}
            ${user_auction}
        </div>
        <div class="playlist-item__duration">${queue.duration_min}</div>
    `;
}

setInterval(function(){
    sendRequestDefault('/api/music-get-queue', function (response) {
        if (!response || !response.success) return;

        playing_data = response.data.playing;
        queue_data   = response.data.queue;
        setPlaying();
        setQueueList();
    });
}, 5000);

function searchMusicRequest(term) {

    console.log('term', term);

    sendRequestDefault('/api/music-search', function (response) {
        if (!response || !response.success || response.data.length == 0){
            showNoResults(term);
        }

        searchMusic(term, response.data);
    }, {term:term});
}

function addMusicToQueue(code, uri, data) {

    let customer = getUserData()['customer'];
    let account  = getUserData()['account'];

    let params =
    {
        'uri':uri,
        'code':code,
        'data':data,
        'customer_id':customer.id,
        'account_id':account.id,
    }

    sendRequestDefault('/api/music-queue-add', function (response) {
        if (!response || !response.success){
            customAlert(response.message ?? 'Erro desconhecido!', 'Ops não foi possivel adicionar a musica a fila!');
            return;
        };

        queue_data.queue = response.data.queue ?? queue_data;

        console.log('queue_data', queue_data);
        console.log('response.data', response.data);

        setPlaying();
        setQueueList();

        customAlert('Musica adicionada a fila!', 'Oba!');
        closeMusicPopupFunc();
    }, params);
}

function returnPopupData(popup) {
    let inputs     = popup.querySelectorAll('input,textarea,select');
    let popup_data = {};

    for (const input of inputs) {
        popup_data[input.id] = input.value;
    }

    return popup_data;
}

//
// envia a requisição para a url informada, passando os parametros informados e
// chama o callack ao fim se houver
//
// @param      {string}              url                    The url
// @param      {(Function|boolean)}  [callback=false]       The callback
// @param      {object}              [params=new Object()]  The parameters
// @param      {boolean}             [is_text=false]        Indicates if text
//
function sendRequestDefault(url, callback = false, params = new Object(), is_text = false) {

  let csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  let args = {
    method:  'POST',
    body:    JSON.stringify(params),
    headers: {'Content-type': 'application/json; charset=UTF-8', 'X-CSRF-TOKEN': csrf_token},
  }
  fetch(url, args)
  .then((response)     => is_text? response.text() : response.json() )
  .then((responseJson) => {

      // chamando o callback se existir
      if (callback) callback(responseJson);

  }).catch(function(e) {
      console.error(`erro na requisição [${url}]:` , e);

      // chamando o callback se existir
      if (callback) callback(false);

  });
}


if (!verifyUserLogged()) popupFirstAccess();