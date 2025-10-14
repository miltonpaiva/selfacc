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
        $customer_queue   = $customer_queue['queue'];
        $customer_played  = MusicQueue::getPlayed();

        if (!isset($music_playing['progress_percent']))
            return self::success('lista de reprodução', ['playing' => [], 'queue' => []]);

        $is_customer_playing = (!empty($customer_playing) && $customer_playing['id'] == $music_playing['item']['id']);
        $customer_next_queue = current($customer_queue);

        // if($music_playing['progress_percent'] >= 95) {
        if(true && $customer_next_queue) {
            if($is_customer_playing) MusicQueue::clearReproducing();

            (new Music())::addToQueue($music_playing['device']['id'], $customer_next_queue['uri']);

            if($customer_next_queue && !$is_customer_playing) MusicQueue::setReproducing($customer_next_queue['id']);
        }

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

        if (!$data) return $response;

        $new_data = $data['data'];

        $new_data['queue']= $queue['queue'];

        return self::success($data['message'], $new_data);

    }
}
