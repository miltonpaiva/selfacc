<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Product;
use App\Models\SimpleValues as SV;

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
        'o_observations',
        'o_sv_status_or_fk',
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
        'added_linked_status_description',
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
        'status_id'  => 'required|int|exists:simple_values,sv_id',

        'observations' => 'max:255',
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

    const VALIDATES_MESSAGES =
    [
        'quantity.required'   => 'O campo Quantidade é obrigatório',
        'quantity.integer'    => 'O campo Quantidade deve ser um número inteiro',
        'quantity.min'        => 'O campo Quantidade deve ser no mínimo 1',

        'total.required'      => 'O campo Total é obrigatório',
        'total.numeric'       => 'O campo Total deve ser um valor numérico',
        'total.min'           => 'O campo Total deve ser no mínimo 0.01',

        'account_id.required' => 'O campo ID da Conta é obrigatório',
        'account_id.int'      => 'O campo ID da Conta deve ser um número inteiro',
        'account_id.exists'   => 'O ID da Conta selecionado não existe na base de dados',

        'product_id.required' => 'O campo ID do Produto é obrigatório',
        'product_id.int'      => 'O campo ID do Produto deve ser um número inteiro',
        'product_id.exists'   => 'O ID do Produto selecionado não existe na base de dados',

        'status_id.required' => 'O campo ID do Status é obrigatório',
        'status_id.int'      => 'O campo ID do Status deve ser um número inteiro',
        'status_id.exists'   => 'O ID do Status selecionado não existe na base de dados',

        'observations.string' => 'O campo Observações deve ser um texto (string)',
        'observations.max'    => 'O campo Observações deve ter no máximo 255 caracteres',

        'id.required'         => 'O campo ID é obrigatório',
        'id.int'              => 'O campo ID deve ser um número inteiro',
        'id.exists'           => 'O ID selecionado não existe na base de dados do Pedido',

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
        'status_id'           => 'o_sv_status_or_fk',
        'quantity'            => 'o_quantity',
        'observations'        => 'o_observations',
        'total'               => 'o_total',
        'date_created'        => 'o_dt_created',
        'date_updated'        => 'o_dt_updated',
        'product_name'        => 'added_linked_product_name',
        'table_number'        => 'added_linked_account_table_number',
        'status_description'  => 'added_linked_status_description',
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
        'status_description'  => 'Status do Pedido',
        'table_number'        => 'Número da Mesa/Conta',
        'observations'        => 'Observações',
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
        return $this->hasOne(Product::class, 'p_id', 'o_product_fk');
    }

    /**
     * getLinkedStatus - A conta tem um Status (SimpleValues).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedStatus(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SV::class, 'sv_id', 'o_sv_status_or_fk');
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

    // --- ACCESSORS ---

    /**
     * getAddedLinkedProductNameAttribute - Retorna o nome do produto associado.
     *
     * @return string|null
     */
    public function getAddedLinkedProductNameAttribute(): ?string
    {
        $product = $this->getLinkedProduct()->first();
        // Assume que o modelo Product tem a coluna 'p_name'
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

    public static function getActivesByTableNumber(int $table_number): ?array
    {
        $accounts = Account::where(
            [ 
                ['a_table_number', '=', $table_number],
                ['a_sv_status_ac_fk', '!=', SV::getValueId('status_ac', 'Fechada')],
            ]
        )->orderBy('a_dt_updated', 'desc')
         ->get()
         ->toArray();

        $accounts_ids = array_column($accounts, 'a_id');
        $orders       = Order::whereIn('o_account_fk', $accounts_ids)
                        ->orderBy('o_dt_updated', 'desc')
                        ->get()
                        ->toArray();

        $orders = array_map(function ($order) use ($accounts) {
            $initial_status_id = SV::getValueId('status_or', 'Novo');
            $account = searchAll($accounts, 'a_id', $order['o_account_fk'], true);

            $order['customer_name']       = $account['added_linked_customer_name']      ?? 'não identificado';
            $order['status_account_name'] = $account['added_linked_status_description'] ?? 'não identificado';
            $order['status_account_id']   = $account['a_sv_status_ac_fk']               ?? 'não identificado';
            $order['is_new']              = ($initial_status_id == $order['o_sv_status_or_fk']);

            return $order;
        }, $orders);

        return convertFieldsMapToFormList($orders, new self());
    }

    public static function getByAccountsList(array $accounts_ids): array
    {
        $orders = Order::whereIn('o_account_fk', $accounts_ids)
                    ->orderBy('o_dt_updated', 'desc')
                    ->get()
                    ->toArray();
        return convertFieldsMapToFormList($orders, new self());
    }

    public static function agroupOrders(array $orders): array
    {
        $agrouped['new']       = [];
        $agrouped['conclused'] = [];
        $agrouped['all']       = $orders;

        foreach ($orders as $order) {

            $order['is_new'] = ($order['status_id'] == SV::getValueId('status_or', 'Novo'));

            if ($order['is_new']) {
                $agrouped['new'][] = $order;
            }

            if (!$order['is_new']) {
                $key = "product_{$order['product_id']}_account_{$order['account_id']}";
                $conclused_by_product_account[$key][] = $order;
            }
        }

        foreach ($conclused_by_product_account ?? [] as $flag => $conclused_list) {

            foreach ($conclused_list as $order) {

                if (!isset($conclused_list_grouped[$flag]))
                    $conclused_list_grouped[$flag] = $order;

                unset($conclused_list_grouped[$flag]['id']);
                unset($conclused_list_grouped[$flag]['date_created']);
                unset($conclused_list_grouped[$flag]['date_updated']);
                unset($conclused_list_grouped[$flag]['is_new']);

                $conclused_list_grouped[$flag]['observations'] = 'Pedidos concluídos agrupados';

                $conclused_list_grouped[$flag]['quantity'] = 0;
                $conclused_list_grouped[$flag]['total']    = 0;

           }

           foreach ($conclused_list as $order) {
               $conclused_list_grouped[$flag]['quantity'] += $order['quantity'];
               $conclused_list_grouped[$flag]['total']    += $order['total'];
           }
       }

       $agrouped['conclused'] = $conclused_list_grouped ?? [];

        return $agrouped;

    }
}
