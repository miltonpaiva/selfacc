<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SimpleValues as SV;
use App\Models\User; // Importando o modelo User

/**
 * Modelo Notifications
 *
 * Gerencia a tabela 'notifications', que armazena notificações
 * para usuários, incluindo texto, status e tópico.
 */
class Notifications extends Model
{
    // Define a chave primária da tabela 'notifications'
    protected $primaryKey = 'n_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'n_id',
        'n_text',
        'n_route',
        'n_confirm_text',
        'n_is_confirmable',
        'n_specific_id',
        'n_sv_status_nt_fk',
        'n_sv_topic_nt_fk',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'added_linked_status_description', // Descrição do Status
        'added_linked_topic_description', // Descrição do Tópico/Tipo
    ];

    /**
     * timestamps - desabilita os campos de controle de criação/atualização
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * VALIDATES - regras de validação para criação de notificação
     *
     * @var array
     */
    const VALIDATES = [
        // campos de dados
        'text'              => 'required|string',
        'route'             => 'nullable|string|max:255',
        'confirm_text'      => 'string|max:255',
        'is_confirmable'    => 'boolean',
        'specific_id'       => 'nullable|int',

        // chaves estrangeiras
        'user_id'           => 'required|int|exists:user,u_id', // Chave estrangeira para o User
        'status_id'         => 'required|int|exists:simple_values,sv_id',
        'topic_id'          => 'required|int|exists:simple_values,sv_id',
    ];

    /**
     * VALIDATES_UPDATE - regras de validação para atualização de notificação
     *
     * @var array
     */
    const VALIDATES_UPDATE = [
        'id'                => 'required|int|exists:notifications,n_id',
        'user_id'           => 'exclude', // O destinatário não deve ser alterado
    ];

    /**
     * FIELDS_MAP - mapeamento dos campos do model para os campos do formulário/API
     *
     * @var array
     */
    const FIELDS_MAP = [
        'id'                  => 'n_id',
        'text'                => 'n_text',
        'route'               => 'n_route',
        'confirm_text'        => 'n_confirm_text',
        'is_confirmable'      => 'n_is_confirmable',
        'specific_id'         => 'n_specific_id',
        'status_id'           => 'n_sv_status_nt_fk',
        'topic_id'            => 'n_sv_topic_nt_fk',
        'date_created'        => 'n_dt_created',
        'date_updated'        => 'n_dt_updated',
        'status_description'  => 'added_linked_status_description',
        'topic_description'   => 'added_linked_topic_description',
    ];

    /**
     * LABELS_MAP - mapeamento dos titulos dos campos do model para exibição amigável
     *
     * @var array
     */
    const LABELS_MAP = [
        'id'                  => '#',
        'text'                => 'Texto da Notificação',
        'route'               => 'Rota de Ação',
        'confirm_text'        => 'Texto do Botão',
        'is_confirmable'      => 'Requer Confirmação',
        'specific_id'         => 'ID Específico',
        'status_description'  => 'Status',
        'topic_description'   => 'Tópico/Tipo',
        'date_created'        => 'Data de Criação',
        'date_updated'        => 'Última Atualização',
    ];

    /**
     * INDEXABLE_COLUMNS - colunas que podem ser indexadas para busca
     *
     * @var array
     */
    const INDEXABLE_COLUMNS = [
        'n_text',
        'n_dt_created',
        'added_linked_status_description',
        'added_linked_topic_description',
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
     * getLinkedStatus - A notificação pertence a um Status (SimpleValues).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedStatus(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SV::class, 'sv_id', 'n_sv_status_nt_fk');
    }

    /**
     * getLinkedTopic - A notificação tem um Tópico/Tipo (SimpleValues).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedTopic(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SV::class, 'sv_id', 'n_sv_topic_nt_fk');
    }

    // --- ACCESSORS ---

    /**
     * getAddedLinkedStatusDescriptionAttribute - Retorna a descrição do status.
     *
     * @return string|null
     */
    public function getAddedLinkedStatusDescriptionAttribute(): ?string
    {
        $status = $this->getLinkedStatus()->first();
        return $status ? $status->sv_title : null;
    }

    /**
     * getAddedLinkedTopicDescriptionAttribute - Retorna a descrição do tópico/tipo.
     *
     * @return string|null
     */
    public function getAddedLinkedTopicDescriptionAttribute(): ?string
    {
        $topic = $this->getLinkedTopic()->first();
        return $topic ? $topic->sv_title : null;
    }
}
