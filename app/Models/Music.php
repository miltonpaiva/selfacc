<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class Music
{
    // https://developer.spotify.com/dashboard/5fa0a1c1dd29408781ceb3627567fc26
    const CLIENT_ID     = '5fa0a1c1dd29408781ceb3627567fc26';
    const CLIENT_SECRET = '373258a29f0d4aefba8c816ee3b53634';

    // arquivos de cache de codigo e token
    const FILE_CACHE_CODE          = "spotfy_auth_code.json";
    const FILE_CACHE_TOKEN         = "spotfy_auth_token.json";
    const FILE_CACHE_REFRESH_TOKEN = "spotfy_refresh_token.json";

    const API_ENDPOINTS =
    [
        'get_code'   => 'https://accounts.spotify.com/authorize',
        'get_token'  => 'https://accounts.spotify.com/api/token',
        'search'     => 'https://api.spotify.com/v1/search',
        'devices'    => 'https://api.spotify.com/v1/me/player/devices',
        'set_device' => 'https://api.spotify.com/v1/me/player',
        'queue'      => 'https://api.spotify.com/v1/me/player/queue',
    ];

    /** @var mixed $cache_disk
     * para registro do codigo de autenticação adiquirido após a autenticação
     */
    public static $cache_disk;

    public function __construct() {
        // garantindo o horario brasileiro
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        // definindo o disco local para salvar os arquivos de index
        self::$cache_disk = Storage::build([
            'driver' => 'local',
            'root'   => storage_path() . '\selfacc_files_cache',
        ]);
    }

    /**
     * requestAuthCode - gera e redireciona o usuario para a pagina de solicitação do codigo de autorização
     * [https://developer.spotify.com/documentation/web-api/tutorials/code-flow]
     *
     * @return RedirectResponse
     */
    public function requestAuthCode(): RedirectResponse
    {
        $route  = self::API_ENDPOINTS['get_code'];
        $scopes =
        [
            'user-modify-playback-state',
            'user-read-currently-playing',
            'user-read-playback-state',
            'app-remote-control',
            'streaming',
        ];
        $scope_str =  implode(' ', $scopes);
        $params    =
        [
            'response_type' => 'code',
            'client_id'     => self::CLIENT_ID,
            'state'         => 'autorizacao_pro_token',
            'redirect_uri'  => str_replace('http://', 'https://', route('music.code_callback')),
            'scope'         => $scope_str,
        ];

        $params_str = http_build_query($params);

        $url = "{$route}?{$params_str}";

        return redirect($url);
    }

    /**
     * saveCode - salva o auth code recebido pelo callback
     *
     * @param  mixed $request
     * @return array
     */
    public function saveCode(Request $request): array
    {
        $now  = date("Y-m-d H:i:s");
        $data = $request->all();

        $data['received_in'] = $now;

        $saved  = self::saveCache(self::FILE_CACHE_CODE, $data);
        $result = ['saved' => $saved, 'data' => $data];

        return $result;
    }

    /**
     * getCode - retorna o codigo salvo em cache, se houver
     *
     * @return string
     */
    public static function getCode(): ?string
    {
        return self::getCache(self::FILE_CACHE_CODE)['code'] ?? null;
    }

    /**
     * getToken - solicita e salva em cache o token de autorização com duração de 1h
     * para ele é necessario o fluxo de solicitação de codigo de autorização [requestAuthCode()]
     * [https://developer.spotify.com/documentation/web-api/tutorials/code-flow]
     *
     * @return string
     */
    public static function getToken(): ?string
    {
        $cache_token = self::getCache(self::FILE_CACHE_TOKEN);

        if ($cache_token                        &&
            isset($cache_token['expires_at'])   &&
            $cache_token['expires_at'] > time()
        ) return $cache_token['access_token'] ?? null;

        // verifica se o token esta vencido e se o mesmo foi registrado em cache hoje
        $is_expired         = (($cache_token['expires_at'] ?? 0) < time());
        $cached_today       = (strpos($cache_token['expires_date'] ?? '', date('Y-m-d')) !== false);
        $request_token_data = self::sendTokenRequest(($is_expired && $cached_today && self::getRefreshToken()));

        if(!isset($request_token_data['json']['access_token'])) return null;

        $token_data                 = $request_token_data['json'];
        $token_data['expires_at']   = time() + $token_data['expires_in'];
        $token_data['expires_date'] = date('Y-m-d H:i:s', $token_data['expires_at']);

        self::saveCache(self::FILE_CACHE_TOKEN, $token_data);

        if (isset($$request_token_data['json']['refresh_token']))
            self::saveCache(self::FILE_CACHE_REFRESH_TOKEN, ['refresh_token' => $request_token_data['json']['refresh_token']]);

        return $token_data['access_token'];
    }

    /**
     * getRefreshToken - retorna o token para atualização após vencimento
     * [https://developer.spotify.com/documentation/web-api/tutorials/refreshing-tokens]
     *
     * @return string
     */
    public static function getRefreshToken(): ?string
    {
        $refresh_token = self::getCache(self::FILE_CACHE_TOKEN)['refresh_token']         ??
                         self::getCache(self::FILE_CACHE_REFRESH_TOKEN)['refresh_token'] ?? null;

        if($refresh_token)
            self::saveCache(self::FILE_CACHE_REFRESH_TOKEN, ['refresh_token' => $refresh_token]);

        return $refresh_token;
    }

    /**
     * search - realiza a busca por uma musica
     * [https://developer.spotify.com/documentation/web-api/reference/search]
     *
     * @param  string $term
     * @return array
     */
    public static function search(string $term): array
    {
        $params['q']    = $term;
        $params['type'] = 'track';

        $result = self::sendCurl(self::API_ENDPOINTS['search'], $params, self::getToken());
        $itens  = $result['json']['tracks']['items'] ?? [];

        foreach ($itens as $item) {
            $clear_item = self::getTrackItemInfo($item);

            if (!$clear_item) continue;

            $tracks[] = $clear_item;
        }

        return $tracks ?? [];
    }

    /**
     * getTrackItemInfo - extrai apenas informações esseciais sobre a musica informada
     *
     * @param  array $item
     * @return array
     */
    public static function getTrackItemInfo(array $item): ?array
    {
        // if ($item['type'] != 'track') return null;

        if(!isset($item['id'])){
            echo '<h1>$ teste</h1><pre>';
            print_r($item);
            exit();
        }

        $images['small_64']   = searchAll($item['album']['images'] ?? [], 'width', 64, true)['url']  ?? null;
        $images['medium_300'] = searchAll($item['album']['images'] ?? [], 'width', 300, true)['url'] ?? null;
        $images['large_640']  = searchAll($item['album']['images'] ?? [], 'width', 640, true)['url'] ?? null;

        $artists = implode(', ', array_column($item['artists'] ?? [], 'name'));

        $clear_item =
        [
            'id'           => $item['id'],
            'name'         => $item['name'],
            'duration_ms'  => $item['duration_ms'] ?? 0,
            'duration_min' => getTimeStr($item['duration_ms']),
            'href'         => $item['href'],
            'preview_url'  => $item['preview_url'] ?? '',
            'uri'          => $item['uri'],
            'is_local'     => $item['is_local'] ?? null,
            'is_playable'  => $item['is_playable'] ?? null,
            'track_number' => $item['track_number'] ?? null,
            'album_name'   => $item['album']['name'] ?? '',
            'artists'      => $artists,
            'images'       => $images,
            'image'        => $images['small_64']   ??
                              $images['medium_300'] ??
                              $images['large_640'],
        ];

        return $clear_item;
    }

    /**
     * getDevicesList - retorna a lista de dispositivos disponiveis para seleção
     * [https://developer.spotify.com/documentation/web-api/reference/get-a-users-available-devices]
     *
     * @return array
     */
    public static function getDevicesList(): array
    {
        $result = self::sendCurl(self::API_ENDPOINTS['devices'], [], self::getToken());

        return $result['json']['devices'] ?? [];
    }

    /**
     * setDevice - define o dispositivo a ser reproduzido
     * [https://developer.spotify.com/documentation/web-api/reference/transfer-a-users-playback]
     *
     * @param  string $device_id
     * @return array
     */
    public static function setDevice(string $device_id): array
    {
        $params = ['device_ids' => [$device_id]];
        $result = self::sendCurl(self::API_ENDPOINTS['set_device'], $params, self::getToken(), 'PUT');

        return $result;
    }

    /**
     * getPlayingStatus - retorna algumas informações sobre a musica tocando e do dispositivo
     *
     * @return array
     */
    public static function getPlayingStatus(): array
    {
        $result = self::sendCurl(self::API_ENDPOINTS['set_device'], [], self::getToken());

        if (!isset($result['json']['currently_playing_type'])) return [];

        $play_info['currently_playing_type'] = $result['json']['currently_playing_type'];
        $play_info['progress_ms']            = $result['json']['progress_ms'];
        $play_info['item']                   = self::getTrackItemInfo($result['json']['item']);
        $play_info['progress_percent']       = round(($play_info['progress_ms']/$play_info['item']['duration_ms'])*100);
        $play_info['device']                 = $result['json']['device'];
        $play_info['progress_min']           = getTimeStr($result['json']['progress_ms']);
        $play_info['duration_min']           = getTimeStr($play_info['item']['duration_ms']);


        return $play_info;
    }

    public static function getQueue(): array
    {
        $result = self::sendCurl(self::API_ENDPOINTS['queue'], [], self::getToken());

        if (!isset($result['json']['currently_playing'])) return ['playing' => [], 'queue' => []];

        $current_playing = self::getTrackItemInfo($result['json']['currently_playing']);

        $itens = $result['json']['queue'];
        $itens = array_map(function ($item) {
            return self::getTrackItemInfo($item);
        }, $itens);

        return ['playing' => $current_playing, 'queue' => $itens];
    }

    /**
     * addToQueue - adiciona uma musica a lista de reprodução
     *
     * @param  string $device_id
     * @param  string $track_url
     * @return array
     */
    public static function addToQueue(string $device_id, string $track_url): array
    {
        $params =
        [
            'uri'       => $track_url,
            'device_id' => $device_id,
        ];
        $result = self::sendCurl(self::API_ENDPOINTS['queue'], $params, self::getToken(), 'POST');

        return $result;
    }

    /**
     * sendTokenRequest - envia a requisição de solicitação do token
     * [https://developer.spotify.com/documentation/web-api/tutorials/code-flow]
     * [https://developer.spotify.com/documentation/web-api/tutorials/getting-started#request-an-access-token]
     * [https://developer.spotify.com/documentation/web-api/tutorials/refreshing-tokens]
     *
     * @param  mixed $is_refresh
     * @return array
     */
    public static function sendTokenRequest(?bool $is_refresh = false): array
    {
        $ch      = curl_init();
        $headers =
            [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . str_replace('base64', '', base64_encode(self::CLIENT_ID . ':' .self::CLIENT_SECRET)),
            ];
        $params  = [
            'grant_type'    => 'authorization_code',
            'code'          => self::getCode(),
            'redirect_uri'  => str_replace('http:', 'https:', route('music.code_callback')),
        ];

        // atualizando os dados para o caso de refresh de token
        if ($is_refresh)
            $params  = [
                'grant_type'    => 'refresh_token',
                'refresh_token' => self::getRefreshToken(),
            ];

        curl_setopt($ch, CURLOPT_URL, self::API_ENDPOINTS['get_token']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $server_output = curl_exec($ch);

        if (curl_error($ch)) $errors = 'Erro cURL: ' . curl_error($ch);

        // 9. Fecha a sessão cURL
        curl_close($ch);

        return [
            'json'   => json_decode($server_output, true),
            'raw'    => $server_output,
            'code'   => $status_code,
            'errors' => $errors ?? null,
        ];
    }

    /**
     * faz uma requisição CURL para a url informada com os parametros informados
     *
     * @param      string       $route   The route
     * @param      array|null   $params  The parameters
     * @param      null|string  $token   The token
     * @param      null|string  $method  The method
     *
     * @return     array|null   retorno da requisição
     */
    public static function sendCurl(string $route, ?array $params = [], ?string $token = null, ?string $method = 'GET'): ?array
    {
        $ch   = curl_init();
        $full = $route;

        $is_get_method = ($method == 'GET');

        $params_str = http_build_query($params);
        if(count($params) > 0) $full = "{$route}?{$params_str}";

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_POST, !$is_get_method);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_URL, $full);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $headers = ["Content-Type: application/json"];
        if ($token) $headers[] = "Authorization: Bearer {$token}";

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);

        if (curl_error($ch)) $errors = 'Erro cURL: ' . curl_error($ch);

        // 9. Fecha a sessão cURL
        curl_close($ch);

        return [
            'json'   => json_decode($server_output, true),
            'raw'    => $server_output,
            'code'   => $status_code,
            'errors' => $errors ?? null,
        ];
    }

    /**
     * saveCache - salva em cache de arquivo no servidor os dados enviados
     *
     * @param  string $file
     * @param  array $data
     * @return bool
     */
    public static function saveCache(string $file, array $data): bool
    {
        return self::$cache_disk->put($file, json_encode($data, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES));
    }

    /**
     * getCache - busca e retorna os dados salvos em cache, se houver
     *
     * @param  string $file
     * @return array
     */
    public static function getCache(string $file): ?array
    {
        return self::$cache_disk->json($file);
    }
}