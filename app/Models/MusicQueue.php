<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Customers;
use App\Models\SimpleValues as SV; // Usando o alias SV

/**
 * Modelo MusicQueue
 *
 * Gerencia a tabela 'music_queue', que armazena as músicas na fila de reprodução,
 * vinculando-as a uma conta (a_id), ao cliente que solicitou (c_id) e ao status.
 */
class MusicQueue extends Model
{
    // Define a chave primária da tabela 'music_queue'
    protected $primaryKey = 'mq_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'music_queue';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'mq_id',
        'mq_uri',
        'mq_code',
        'mq_position',
        'mq_is_auction',
        'mq_account_fk',
        'mq_customer_fk',
        'mq_sv_status_mq_fk',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'added_linked_customer_name', // Nome do cliente getAddedLinkedCustomerNameAttribute()
        'added_linked_status_description', // Descrição do status
    ];

    /**
     * timestamps - desabilita os campos de controle de criação/atualização
     *
     * NOTA: Os campos de data são definidos na migration e atualizados pelo banco.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * VALIDATES - regras de validação para criação de itens na fila
     *
     * @var array
     */
    const VALIDATES = [
        // campos de dados
        'uri'          => 'required|string|max:255',
        'code'         => 'required|string|max:255',
        'position'     => 'required|int|min:1',
        'is_auction'   => 'required|boolean',

        // chaves estrangeiras
        'account_id'   => 'required|int|exists:account,a_id',
        'customer_id'  => 'required|int|exists:customer,c_id',
        'status_id'    => 'required|int|exists:simple_values,sv_id',
    ];

    /**
     * VALIDATES_UPDATE - regras de validação para atualização
     *
     * @var array
     */
    const VALIDATES_UPDATE = [
        'id'           => 'required|int|exists:music_queue,mq_id',
        // 'account_id' e 'customer_id' geralmente não mudam após a criação
        'account_id'   => 'exclude',
        'customer_id'  => 'exclude',
    ];

    /**
     * FIELDS_MAP - mapeamento dos campos do model para os campos do formulário/API
     *
     * @var array
     */
    const FIELDS_MAP = [
        'id'                  => 'mq_id',
        'uri'                 => 'mq_uri',
        'code'                => 'mq_code',
        'position'            => 'mq_position',
        'is_auction'          => 'mq_is_auction',
        'account_id'          => 'mq_account_fk',
        'customer_id'         => 'mq_customer_fk',
        'status_id'           => 'mq_sv_status_mq_fk',
        'date_created'        => 'mq_dt_created',
        'date_updated'        => 'mq_dt_updated',
        'customer_name'       => 'added_linked_customer_name',
        'status_description'  => 'added_linked_status_description',
    ];

    /**
     * LABELS_MAP - mapeamento dos titulos dos campos do model para exibição amigável
     *
     * @var array
     */
    const LABELS_MAP = [
        'id'                  => '#',
        'uri'                 => 'URI da Música (Link/ID)',
        'code'                => 'Código da Música',
        'position'            => 'Posição na Fila',
        'is_auction'          => 'É Leilão',
        'account_id'          => 'ID da Conta Vinculada',
        'customer_name'       => 'Cliente Solicitante',
        'status_description'  => 'Status da Música',
        'date_created'        => 'Data de Criação',
        'date_updated'        => 'Última Atualização',
    ];

    /**
     * INDEXABLE_COLUMNS - colunas que podem ser indexadas para busca
     *
     * @var array
     */
    const INDEXABLE_COLUMNS = [
        'mq_uri',
        'mq_code',
        'mq_dt_created',
        'added_linked_customer_name',
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
     * getLinkedAccount - Item da fila pertence a uma Conta (a_id).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedAccount(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        // Relação hasOne para manter a convenção, mas funcionalmente atua como belongsTo
        return $this->hasOne(Account::class, 'a_id', 'mq_account_fk');
    }

    /**
     * getLinkedCustomer - Item da fila foi solicitado por um Cliente (c_id).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedCustomer(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(Customers::class, 'c_id', 'mq_customer_fk');
    }

    /**
     * getLinkedStatus - Item da fila tem um Status (SimpleValues).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedStatus(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SV::class, 'sv_id', 'mq_sv_status_mq_fk');
    }

    // --- ACCESSORS ---

    /**
     * getAddedLinkedCustomerNameAttribute - Retorna o nome do cliente solicitante.
     *
     * @return string|null
     */
    public function getAddedLinkedCustomerNameAttribute(): ?string
    {
        $customer = $this->getLinkedCustomer()->first();
        // Assume que o modelo Customers tem a coluna 'c_name'
        return $customer ? $customer->c_name : null;
    }

    /**
     * getAddedLinkedStatusDescriptionAttribute - Retorna a descrição do status.
     *
     * @return string|null
     */
    public function getAddedLinkedStatusDescriptionAttribute(): ?string
    {
        $status = $this->getLinkedStatus()->first();
        // Assume que o modelo SimpleValues tem a coluna 'sv_title'
        return $status ? $status->sv_title : null;
    }
}
