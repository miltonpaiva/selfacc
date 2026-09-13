<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use App\Models\Music;
use App\Models\MusicQueue;
use App\Models\Customer;
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
        $music_playing  = (new Music())::getPlayingStatus();
        $music_queue    = (new Music())::getQueue()['queue'];
        $customer_queue = MusicQueue::getQueue();

        // setando o noem do usuario ao estar reproduzindo
        $music_playing['item']['customer'] =
            \searchAll($customer_queue['played'], 'id', $music_playing['item']['id'], true)['customer'] ?? '';

        // setando o nome das musicas a serem reproduzidas
        foreach ($music_queue as $key => $music) {
            $music_queue[$key]['customer'] =
                \searchAll($customer_queue['played'], 'id', $music['id'], true)['customer'] ?? '';
        }

        $customer_playing = $customer_queue['playing'];
        $customer_next    = $customer_queue['next'];
        $customer_queue   = $customer_queue['queue'];

        if (!isset($music_playing['progress_percent']))
            return self::success('Não há musica tocando', ['playing' => [], 'queue' => []]);

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
        $request->merge(['position'   => 1]);
        $request->merge(['is_auction' => false]);
        $request->merge(['status_id'  => SV::getValueId('status_mq', 'Na Fila')]);

        $palyer_queue   = (new Music())::getQueue();
        $customer       = Customer::find($request->input('customer_id'))->c_name;
        $customer_queue = MusicQueue::getQueue();
        $palyer_queue['queue'] = array_merge($customer_queue['queue'], $palyer_queue['queue']);

        $music_exist_in_queue = (searchAll($palyer_queue['queue'], 'id', $request->get('code')) !== null);

        if ($music_exist_in_queue) return self::error('A musica desejada ja se encontra na fila de reprodução', $palyer_queue);

        if($palyer_queue['playing']['id'] == $request->get('code')) return self::error('A musica desejada ja esta tocando', $palyer_queue);

        $customer_exist = (searchAll($palyer_queue['queue'], 'customer', $customer) !== null);

        if ($customer_exist) return self::error('O cliente já tem uma musica na fila de reprodução, aguarde para adicionar uma musica novamente!', $palyer_queue);

        $music_played_recent = (searchAll(array_slice($customer_queue['played'], 0, 5), 'id', $request->get('code')) !== null);

        if ($music_played_recent) return self::error('A musica desejada tocou recentemente', $palyer_queue);

        $response = self::newOrUpdateModel($request, new MusicQueue(), null, false);

        $data = $response->getData(true);

        if (!$data || !$data['success']) return $response;

        $new_data = $data['data'];

        $new_data['queue']= $palyer_queue['queue'];

        return self::success($data['message'], $new_data);
    }

    public function updateQueue(): JsonResponse
    {
        $music_playing    = (new Music())::getPlayingStatus();
        $music_queue      = (new Music())::getQueue()['queue'];
        $customer_queue   = MusicQueue::getQueue();
        $customer_playing = $customer_queue['playing'];
        $customer_next    = $customer_queue['next'];
        $customer_queue   = $customer_queue['queue'];

        // // se não houver proximo setado, setamos manualmente
        // if (empty($customer_next) && !empty($customer_queue))
        //     $customer_next = current($customer_queue);

        MusicQueue::clearReproducing();

        // // se não houver musica tocando
        if (!isset($music_playing['progress_percent']))
            return self::success('não há musica tocanto', ['playing' => [], 'queue' => []]);

        // // se não houver musica tocando
        if (empty($customer_next) && empty($customer_queue))
            return self::success('não há musica a ser adicionada', ['playing' => $music_playing]);

        // $no_has_next_seted             = empty($customer_queue['next']);
        // $no_has_customer_playing_seted = empty($customer_playing);
        // $current_playing_is_a_customer = (!$no_has_customer_playing_seted && $customer_playing['id'] == $music_playing['item']['id']);
        // $next_music_exist_in_queue     = !is_null(searchAll($music_queue, 'id', $customer_next['id'] ?? 0));
        // $next_music_is_playing         = (!empty($customer_queue) && $music_playing['item']['id'] == $customer_next['id']);

        // // setando que a musica atual da fila do usuario será a proxima
        // // a ser tocada na fila do player
        // if ($no_has_next_seted && !empty($customer_queue)) MusicQueue::setNext($customer_next['id']);

        // // setando que a musica a seguir na fila do usuario entre na fila do player, se ja não estiver
        // if (!$no_has_next_seted && !$next_music_exist_in_queue)
        //     (new Music())::addToQueue($music_playing['device']['id'], $customer_next['uri']);

        // // limpando as musicas reproduzidas
        // if ($next_music_is_playing) MusicQueue::clearReproducing();

        // // setando que a musica do cliente esta tocando
        // if ($next_music_is_playing) MusicQueue::setReproducing($customer_next['id']);

        $customer_next_id  = current($customer_queue)['id'];
        $customer_next_uri = current($customer_queue)['uri'];

        MusicQueue::setNext($customer_next_id);

        (new Music())::addToQueue($music_playing['device']['id'], $customer_next_uri);

        MusicQueue::setReproducing($customer_next_id);

        $queue = array_merge($customer_queue, $music_queue);

        return self::success('lista de reprodução', ['playing' => $music_playing, 'queue' => $queue]);
    }
}
