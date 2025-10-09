<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MusicQueue; // Para vincular ao item da fila
use App\Models\SimpleValues as SV; // Para o status

/**
 * Modelo MusicAuction
 *
 * Gerencia a tabela 'music_auction', que armazena detalhes de leilões
 * de músicas (apenas para itens onde mq_is_auction é TRUE na tabela music_queue).
 */
class MusicAuction extends Model
{
    // Define a chave primária da tabela 'music_auction'
    protected $primaryKey = 'ma_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'music_auction';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ma_id',
        'ma_music_queue_fk',
        'ma_sv_status_ma_fk',
        'ma_offer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'added_linked_status_description', // Descrição do status do leilão
        'added_linked_queue_uri'           // URI da música (obtido da fila)
    ];

    /**
     * timestamps - desabilita os campos de controle de criação/atualização
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * VALIDATES - regras de validação para criação de leilões
     *
     * @var array
     */
    const VALIDATES = [
        // campos de dados
        'offer'        => 'required|numeric|min:0.01',

        // chaves estrangeiras
        // music_queue_id deve ser único e existir na tabela music_queue
        'music_queue_id' => 'required|int|unique:music_auction,ma_music_queue_fk|exists:music_queue,mq_id',
        'status_id'      => 'required|int|exists:simple_values,sv_id',
    ];

    /**
     * VALIDATES_UPDATE - regras de validação para atualização
     *
     * @var array
     */
    const VALIDATES_UPDATE = [
        'id'             => 'required|int|exists:music_auction,ma_id',
        // A chave music_queue_fk deve ser imutável, por isso é excluída
        'music_queue_id' => 'exclude',
    ];

    /**
     * FIELDS_MAP - mapeamento dos campos do model para os campos do formulário/API
     *
     * @var array
     */
    const FIELDS_MAP = [
        'id'                  => 'ma_id',
        'music_queue_id'      => 'ma_music_queue_fk',
        'status_id'           => 'ma_sv_status_ma_fk',
        'offer'               => 'ma_offer',
        'date_created'        => 'ma_dt_created',
        'date_updated'        => 'ma_dt_updated',
        'status_description'  => 'added_linked_status_description',
        'queue_uri'           => 'added_linked_queue_uri',
    ];

    /**
     * LABELS_MAP - mapeamento dos titulos dos campos do model para exibição amigável
     *
     * @var array
     */
    const LABELS_MAP = [
        'id'                  => '#',
        'music_queue_id'      => 'ID do Item na Fila',
        'offer'               => 'Valor da Oferta',
        'status_description'  => 'Status do Leilão',
        'queue_uri'           => 'URI da Música',
        'date_created'        => 'Data de Criação',
        'date_updated'        => 'Última Atualização',
    ];

    /**
     * INDEXABLE_COLUMNS - colunas que podem ser indexadas para busca
     *
     * @var array
     */
    const INDEXABLE_COLUMNS = [
        'ma_offer',
        'ma_dt_created',
    ];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
    }

    // --- RELACIONAMENTOS ---

    /**
     * getLinkedMusicQueue - O leilão está vinculado a um item específico da fila (1:1).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedMusicQueue(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        // Relação hasOne, funcionando como belongsTo de forma concisa.
        return $this->hasOne(MusicQueue::class, 'mq_id', 'ma_music_queue_fk');
    }

    /**
     * getLinkedStatus - O leilão tem um Status (SimpleValues).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedStatus(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SV::class, 'sv_id', 'ma_sv_status_ma_fk');
    }

    // --- ACCESSORS ---

    /**
     * getAddedLinkedStatusDescriptionAttribute - Retorna a descrição do status do leilão.
     *
     * @return string|null
     */
    public function getAddedLinkedStatusDescriptionAttribute(): ?string
    {
        $status = $this->getLinkedStatus()->first();
        // Assume que o modelo SimpleValues tem a coluna 'sv_title'
        return $status ? $status->sv_title : null;
    }

    /**
     * getAddedLinkedQueueUriAttribute - Retorna a URI da música (do item da fila).
     *
     * @return string|null
     */
    public function getAddedLinkedQueueUriAttribute(): ?string
    {
        $queueItem = $this->getLinkedMusicQueue()->first();
        // Assume que o modelo MusicQueue tem a coluna 'mq_uri'
        return $queueItem ? $queueItem->mq_uri : null;
    }
}
