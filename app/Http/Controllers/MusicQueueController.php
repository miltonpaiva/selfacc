<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use App\Models\Music;
use App\Models\MusicQueue;
use App\Models\SimpleValues as SV;

class MusicQueueController extends Controller
{
    public function requestAuthCode(Request $request): RedirectResponse
    {
       return (new Music())->requestAuthCode();
    }

    public function saveCode(Request $request): JsonResponse
    {
       return self::success(null, (new Music())->saveCode($request));
    }

    public function getDevices(Request $request): JsonResponse
    {
        $devices = (new Music())::getDevicesList();

        return self::success('lista de dispositivos disponiveis', $devices);
    }

    public function setDevice(Request $request): JsonResponse
    {
        (new Music())::setDevice($request->get('device_id'));

        $devices       = (new Music())::getDevicesList();
        $active_device = searchAll($devices, 'is_active', true, true);
        $success       = ($active_device['id'] == $request->get('device_id'));

        if ($success) return self::success('dispositivo alterado', $devices);

        return self::error('dispositivo não alterado', $devices);
    }

    public function getQueue(): JsonResponse
    {
        $music_playing    = (new Music())::getPlayingStatus();
        $music_queue      = (new Music())::getQueue()['queue'];
        $customer_queue   = MusicQueue::getQueue();
        $customer_playing = $customer_queue['playing'];
        $customer_next    = $customer_queue['next'];
        $customer_queue   = $customer_queue['queue'];

        if (!isset($music_playing['progress_percent']))
            return self::success('lista de reprodução', ['playing' => [], 'queue' => []]);

        $is_end_song                   = ($music_playing['progress_percent'] >= 95);
        $no_has_next_seted             = empty($customer_next);
        $no_has_playing_seted          = empty($customer_playing);
        $current_playing_is_a_customer = (!$no_has_playing_seted && $customer_playing['id'] == $music_playing['item']['id']);
        $next_music_in_queue           = !is_null(searchAll($music_queue, 'id', $customer_next['id'] ?? 0));
        $next_music_is_playing         = (!empty($customer_queue) && $music_playing['item']['id'] == current($customer_queue)['id'] ?? 0);

        // echo '<h1>$is_end_song</h1><pre>';
        // print_r($is_end_song);
        // echo '<h1>$no_has_next_seted</h1><pre>';
        // print_r($no_has_next_seted);
        // echo '<h1>$next_music_is_playing</h1><pre>';
        // print_r($next_music_is_playing);
        // echo '<h1>$customer_next</h1><pre>';
        // print_r($customer_next);
        // echo '<h1>$customer_queue</h1><pre>';
        // print_r($customer_queue);

        // setando que a musica atual da fila será a proxima
        if (!$is_end_song && $no_has_next_seted && !empty($customer_queue))
            MusicQueue::setNext(current($customer_queue)['id']);

        // setando que a musica a seguir entre na fila, se ja não estiver
        if ($is_end_song && !$no_has_next_seted && !$next_music_in_queue)
            (new Music())::addToQueue($music_playing['device']['id'], $customer_next['uri']);

        // setando que a musica do cliente esta tocando
        if ($next_music_is_playing) MusicQueue::setReproducing($customer_next['id']);

        // limpando as musicas reproduzidas
        if ($is_end_song && $current_playing_is_a_customer) MusicQueue::clearReproducing();

        $queue = array_merge($customer_queue, $music_queue);

        return self::success('lista de reprodução', ['playing' => $music_playing, 'queue' => $queue]);
    }

    public function search(Request $request): JsonResponse
    {
        $result = (new Music())::search($request->get('term') ?? '');

        return self::success('resultado da busca', $result);
    }

    public function addQueue(Request $request): object
    {
        $request->merge(['position' => 1]);
        $request->merge(['is_auction' => false]);
        $request->merge(['status_id' => SV::getValueId('status_mq', 'Na Fila')]);

        $queue          = (new Music())::getQueue();
        $customer_queue = MusicQueue::getQueue();

        $queue['queue'] = array_merge($customer_queue, $queue['queue']);

        $exist = (searchAll($queue['queue'], 'id', $request->get('code')) !== null);

        if ($exist) return self::error('A musica desejada ja se encontra na fila de reprodução', $queue);

        $response = self::newOrUpdateModel($request, new MusicQueue(), null, false);

        $data = $response->getData(true);

        if (!$data || !$data['success']) return $response;

        $new_data = $data['data'];

        $new_data['queue']= $queue['queue'];

        return self::success($data['message'], $new_data);
    }
}
