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
        $playing        = (new Music())::getPlayngStatus();
        $queue          = (new Music())::getQueue();
        $customer_queue = MusicQueue::getQueue();

        if($playing['progress_percent'] > 96){
            $next = current($customer_queue);

            if ($next) {
                (new Music())::addToQueue($playing['device']['id'], $next['uri']);
                MusicQueue::setReproducing($next['id']);
            }

        }

        $queue['queue'] = array_merge($customer_queue, $queue['queue']);

        return self::success('lista de reprodução', ['playing' => $playing, 'queue' => $queue]);
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
