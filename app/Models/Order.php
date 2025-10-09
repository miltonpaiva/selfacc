<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Products;

/**
 * Modelo Order
 *
 * Gerencia a tabela 'order', que armazena os itens de pedido,
 * incluindo quantidade, total e referências à conta e ao produto.
 */
class Order extends Model
{
    // Define a chave primária da tabela 'order'
    protected $primaryKey = 'o_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'o_id',
        'o_account_fk',
        'o_product_fk',
        'o_quantity',
        'o_total',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'added_linked_product_name', // Nome do produto associado
        'added_linked_account_table_number', // Número da mesa/conta
    ];

    /**
     * timestamps - desabilita os campos de controle de criação/atualização
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * VALIDATES - regras de validação para criação de itens de pedido
     *
     * @var array
     */
    const VALIDATES = [
        // campos de dados
        'quantity'   => 'required|integer|min:1',
        // O total será calculado, mas a regra é mantida para validação manual, se necessário.
        'total'      => 'required|numeric|min:0.01',

        // chaves estrangeiras
        'account_id' => 'required|int|exists:account,a_id',
        'product_id' => 'required|int|exists:product,p_id',
    ];

    /**
     * VALIDATES_UPDATE - regras de validação para atualização de itens de pedido
     *
     * @var array
     */
    const VALIDATES_UPDATE = [
        'id'          => 'required|int|exists:order,o_id',
        // Geralmente, o produto e a conta associados não são alterados após a criação
        'account_id'  => 'exclude',
        'product_id'  => 'exclude',
    ];

    /**
     * FIELDS_MAP - mapeamento dos campos do model para os campos do formulário/API
     *
     * @var array
     */
    const FIELDS_MAP = [
        'id'                  => 'o_id',
        'account_id'          => 'o_account_fk',
        'product_id'          => 'o_product_fk',
        'quantity'            => 'o_quantity',
        'total'               => 'o_total',
        'date_created'        => 'o_dt_created',
        'date_updated'        => 'o_dt_updated',
        'product_name'        => 'added_linked_product_name',
        'table_number'        => 'added_linked_account_table_number',
    ];

    /**
     * LABELS_MAP - mapeamento dos titulos dos campos do model para exibição amigável
     *
     * @var array
     */
    const LABELS_MAP = [
        'id'                  => '#',
        'quantity'            => 'Quantidade',
        'total'               => 'Total do Item',
        'product_name'        => 'Produto',
        'table_number'        => 'Número da Mesa/Conta',
        'date_created'        => 'Data do Pedido',
        'date_updated'        => 'Última Atualização',
    ];

    /**
     * INDEXABLE_COLUMNS - colunas que podem ser indexadas para busca
     *
     * @var array
     */
    const INDEXABLE_COLUMNS = [
        'o_dt_created',
        'added_linked_product_name',
        'added_linked_account_table_number',
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
     * getLinkedAccount - O item do pedido pertence a uma Conta (a_id).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedAccount(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(Account::class, 'a_id', 'o_account_fk');
    }

    /**
     * getLinkedProduct - O item do pedido é um Produto (p_id).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedProduct(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(Products::class, 'p_id', 'o_product_fk');
    }

    // --- ACCESSORS ---

    /**
     * getAddedLinkedProductNameAttribute - Retorna o nome do produto associado.
     *
     * @return string|null
     */
    public function getAddedLinkedProductNameAttribute(): ?string
    {
        $product = $this->getLinkedProduct()->first();
        // Assume que o modelo Products tem a coluna 'p_name'
        return $product ? $product->p_name : null;
    }

    /**
     * getAddedLinkedAccountTableNumberAttribute - Retorna o número da mesa/conta associada.
     *
     * @return int|null
     */
    public function getAddedLinkedAccountTableNumberAttribute(): ?int
    {
        $account = $this->getLinkedAccount()->first();
        // Assume que o modelo Account tem a coluna 'a_table_number'
        return $account ? $account->a_table_number : null;
    }
}
