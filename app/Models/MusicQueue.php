<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Customer;
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
        'mq_str',
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
        'data'         => 'required|string|max:255',
        'position'     => 'required|int|min:1',
        'is_auction'   => 'boolean',

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

    const VALIDATES_MESSAGES =
    [
        'uri.required'     => 'O campo URI é obrigatório',
        'uri.string'       => 'O campo URI deve ser um texto (string)',
        'uri.max'          => 'O campo URI deve ter no máximo 255 caracteres',

        'code.required'    => 'O campo Código é obrigatório',
        'code.string'      => 'O campo Código deve ser um texto (string)',
        'code.max'         => 'O campo Código deve ter no máximo 255 caracteres',

        'data.required'  => 'O campo dados é obrigatório',
        'data.string'    => 'O campo dados deve ser um texto (string)',
        'data.max'       => 'O campo dados deve ter no máximo 255 caracteres',

        'position.required' => 'O campo Posição é obrigatório',
        'position.int'      => 'O campo Posição deve ser um número inteiro',
        'position.min'      => 'O campo Posição deve ser no mínimo 1',

        'is_auction.required' => 'O campo É Leilão é obrigatório',
        'is_auction.boolean'  => 'O campo É Leilão deve ser verdadeiro ou falso',

        'account_id.required'  => 'O campo ID da Conta é obrigatório',
        'account_id.int'       => 'O campo ID da Conta deve ser um número inteiro',
        'account_id.exists'    => 'O ID da Conta selecionado não existe na base de dados',

        'customer_id.required' => 'O campo ID do Cliente é obrigatório',
        'customer_id.int'      => 'O campo ID do Cliente deve ser um número inteiro',
        'customer_id.exists'   => 'O ID do Cliente selecionado não existe na base de dados',

        'status_id.required'   => 'O campo ID do Status é obrigatório',
        'status_id.int'        => 'O campo ID do Status deve ser um número inteiro',
        'status_id.exists'     => 'O ID do Status selecionado não existe na base de dados',

        'id.required'      => 'O campo ID é obrigatório',
        'id.int'           => 'O campo ID deve ser um número inteiro',
        'id.exists'        => 'O ID selecionado não existe na base de dados da Fila de Música',
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
        'data'                => 'mq_str',
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
        'data'                => 'Dados da musica',
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
        return $this->hasOne(Customer::class, 'c_id', 'mq_customer_fk');
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
        // Assume que o modelo Customer tem a coluna 'c_name'
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

    public static function getQueue(): array
    {
        $queue = self::where([
            ['mq_sv_status_mq_fk', '=', SV::getValueId('status_mq', 'Na Fila')],
        ])->get()->toArray();

        $queue = array_map(function ($item) {
            $data_arr = explode(';', $item['mq_str']);
            $item = [
                'id'           => $item['mq_code'],
                'name'         => $data_arr[0],
                'duration_min' => $data_arr[2],
                'uri'          => $item['mq_uri'],
                'album_name'   => '',
                'artists'      => $data_arr[1],
                'customer'     => $item['added_linked_customer_name'],
            ];

            return $item;
        }, $queue);

        return $queue;
    }

    public static function setReproducing(string $code): void
    {
        $next = self::where([
            ['mq_code',            '=', $code],
            ['mq_sv_status_mq_fk', '=', SV::getValueId('status_mq', 'Na Fila')],
        ])->first();

        $next->mq_sv_status_mq_fk = SV::getValueId('status_mq', 'Reproduzindo');
        $next->save();
    }
}
