<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customers;
use App\Models\SimpleValues as SV; // Usando o alias SV

/**
 * Modelo Account
 *
 * Gerencia a tabela 'account', que rastreia informações de contas, incluindo
 * o cliente vinculado, o status e o total consumido.
 */
class Account extends Model
{
    // Define a chave primária da tabela 'account'
    protected $primaryKey = 'a_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'a_id',
        'a_customer_fk',
        'a_sv_status_ac_fk',
        'a_table_number',
        'a_total_consumed',
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
     * VALIDATES - regras de validação para criação de contas
     *
     * @var array
     */
    const VALIDATES = [
        // campos de dados
        'table_number'   => 'required|int|min:1',
        'total_consumed' => 'required|numeric|min:0',

        // chaves estrangeiras
        'customer_id'    => 'required|int|exists:customer,c_id',
        'status_id'      => 'required|int|exists:simple_values,sv_id',
    ];

    /**
     * VALIDATES_UPDATE - regras de validação para atualização
     *
     * @var array
     */
    const VALIDATES_UPDATE = [
        'id'             => 'required|int|exists:account,a_id',
        'customer_id'    => 'exclude', // Cliente não deve ser alterado após a criação da conta
    ];

    /**
     * FIELDS_MAP - mapeamento dos campos do model para os campos do formulário/API
     *
     * @var array
     */
    const FIELDS_MAP = [
        'id'                  => 'a_id',
        'customer_id'         => 'a_customer_fk',
        'status_id'           => 'a_sv_status_ac_fk',
        'table_number'        => 'a_table_number',
        'total_consumed'      => 'a_total_consumed',
        'date_created'        => 'a_dt_created',
        'date_updated'        => 'a_dt_updated',
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
        'customer_id'         => 'ID do Cliente',
        'customer_name'       => 'Nome do Cliente',
        'status_description'  => 'Status da Conta',
        'table_number'        => 'Número da Mesa',
        'total_consumed'      => 'Total Consumido',
        'date_created'        => 'Data de Abertura',
        'date_updated'        => 'Última Atualização',
    ];

    /**
     * INDEXABLE_COLUMNS - colunas que podem ser indexadas para busca
     *
     * @var array
     */
    const INDEXABLE_COLUMNS = [
        'a_table_number',
        'a_total_consumed',
        'a_dt_created',
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
     * getLinkedCustomer - A conta pertence a um Cliente.
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedCustomer(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        // Neste caso, a relação correta seria belongsTo, mas mantemos o hasOne
        // para seguir estritamente o padrão 'Lots.php' original.
        return $this->hasOne(Customers::class, 'c_id', 'a_customer_fk');
    }

    /**
     * getLinkedStatus - A conta tem um Status (SimpleValues).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedStatus(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SV::class, 'sv_id', 'a_sv_status_ac_fk');
    }

    // --- ACCESSORS ---

    /**
     * getAddedLinkedCustomerNameAttribute - Retorna o nome do cliente relacionado.
     *
     * @return string|null
     */
    public function getAddedLinkedCustomerNameAttribute(): ?string
    {
        $customer = $this->getLinkedCustomer()->first();
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
        return $status ? $status->sv_title : null;
    }
}
