<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\SimpleValues as SV; // Usando o alias SV
use Illuminate\Http\JsonResponse;

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
        'total_consumed' => 'numeric|min:0',

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

    const VALIDATES_MESSAGES =
    [
        'table_number.required' => 'O campo Número da Mesa é obrigatório',
        'table_number.int'      => 'O campo Número da Mesa deve ser um número inteiro',
        'table_number.min'      => 'O campo Número da Mesa deve ser no mínimo 1',

        'total_consumed.numeric'  => 'O campo Total Consumido deve ser um valor numérico',
        'total_consumed.min'      => 'O campo Total Consumido deve ser no mínimo 0',
        'customer_id.required'  => 'O campo ID do Cliente é obrigatório',
        'customer_id.int'       => 'O campo ID do Cliente deve ser um número inteiro',
        'customer_id.exists'    => 'O ID do Cliente selecionado não existe na base de dados',

        'status_id.required'    => 'O campo ID do Status é obrigatório',
        'status_id.int'         => 'O campo ID do Status deve ser um número inteiro',
        'status_id.exists'      => 'O ID do Status selecionado não existe na base de dados',

        'id.required'           => 'O campo ID é obrigatório',
        'id.int'                => 'O campo ID deve ser um número inteiro',
        'id.exists'             => 'O ID selecionado não existe na base de dados da Conta',
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
        return $this->hasOne(Customer::class, 'c_id', 'a_customer_fk');
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

    public static function updateTotal(int $id): array
    {
        $orders = Order::where('o_account_fk', $id)->get();

        if (!$orders) return [];

        $total = $orders->sum('o_total');

        self::find($id)->update(['a_total_consumed' => $total]);

        return convertFieldsMapToForm(self::find($id)->toArray(), new self());
    }

    public static function updateStatus(int $id): array
    {
        self::find($id)->update(['a_sv_status_ac_fk' => SV::getValueId('status_ac', 'Aberta')]);

        return convertFieldsMapToForm(self::find($id)->toArray(), new self());
    }

    public static function getActives(): array
    {
        $accounts = self::where(
                        'a_sv_status_ac_fk',
                        SV::getValueId('status_ac', 'Aberta')
                    )->orderBy('a_dt_updated', 'desc')
                     ->get();

        if (!$accounts) return [];

        $accounts     = convertFieldsMapToFormList($accounts->toArray(), new Account());
        $accounts_ids = array_column($accounts, 'id');
        $orders       = Order::getByAccountsList($accounts_ids);

        foreach ($orders as $key => $order) {
            $account_key = searchAll($accounts, 'id', $order['account_id']);

            if(is_null($account_key)) continue;

            $orders[$key]['customer_name'] = $accounts[$account_key]['customer_name'];
        }

        $orders = Order::agroupOrders($orders);

        $join_orders = array_merge(
            $orders['new']       ?? [],
            $orders['conclused'] ?? []
        );

        foreach ($join_orders as $order) {
            $account_key = searchAll($accounts, 'id', $order['account_id']);

            if(is_null($account_key)) continue;

            $accounts[$account_key]['orders'][] = $order;

            // calculando a quantidade de novos pedidos por mesa
            if (isset($order['is_new']) && $order['is_new']) {
                if (!isset($new_qtd[$order['table_number']]))
                    $new_qtd[$order['table_number']] = 0;

                $new_qtd[$order['table_number']]++;
            }

        }

        $tables = [];
        foreach ($accounts as $account) {

            $table = $tables[$account['table_number']] ?? [];

            if (!isset($table['number']))                     $table['number']                     = $account['table_number'];
            if (!isset($table['total']))                      $table['total']                      = 0.0;
            if (!isset($table['total_credit_card']))          $table['total_credit_card']          = 0.0;
            if (!isset($table['total_debit_card']))           $table['total_debit_card']           = 0.0;
            if (!isset($table['total_formated']))             $table['total_formated']             = '00,00';
            if (!isset($table['total_credit_card_formated'])) $table['total_credit_card_formated'] = '00,00';
            if (!isset($table['total_debit_card_formated']))  $table['total_debit_card_formated']  = '00,00';
            if (!isset($table['customers']))                  $table['customers']                  = [];
            if (!isset($table['orders']))                     $table['orders']                     = [];
            if (!isset($table['new_order_qtd']))              $table['new_order_qtd']              = $new_qtd[$account['table_number']] ?? 0;
            if (!isset($table['updated_date']))               $table['updated_date']               = $account['date_updated'];
            if (!isset($table['updated_time']))               $table['updated_time']               = strtotime($account['date_updated']);

            $table['customers'][] = [
                'id'             => $account['customer_id'],
                'account_id'     => $account['id'],
                'name'           => $account['customer_name'],
                'total_consumed' => $account['total_consumed'],
            ];

            $table['orders'] = array_merge(
                $table['orders']   ?? [],
                $account['orders'] ?? []
            );

            // calculando o total da mesa
            $table['total'] += $account['total_consumed'];

            $table['total_credit_card'] = round(($table['total'] * (4.98 / 100)) + $table['total'], 2);
            $table['total_debit_card']  = round(($table['total'] * (1.99 / 100)) + $table['total'], 2);

            $table['total_credit_card_formated'] = number_format($table['total_credit_card'], 2, ',', '.');
            $table['total_debit_card_formated']  = number_format($table['total_debit_card'], 2, ',', '.');
            $table['total_formated']             = number_format($table['total'], 2, ',', '.');

            $tables[$account['table_number']] = $table;
        }

        $tables = ordenateAll($tables, 'updated_time', false);

        return array_values($tables) ?? [];
    }

    public static function closeTableByNumber(int $table_number): object
    {
        $accounts = Account::where(
            [ ['a_table_number', '=', $table_number], ]
        )->get();

        if (!$accounts) return \App\Traits\DefaultResponseTrait::error('Nenhuma conta encontrada para o número da mesa informado.');

        $closed_status_id = SV::getValueId('status_ac', 'Fechada');

        foreach ($accounts as $account) {
            $account->a_sv_status_ac_fk = $closed_status_id;
            $account->save();
        }

        $tables = Account::getActives();
        return \App\Traits\DefaultResponseTrait::success('Mesa fechada com sucesso.', ['tables' => $tables]);
    }

    public static function closeAccount(int $account_id): JsonResponse
    {
        $account = Account::find($account_id);

        if (!$account) return \App\Traits\DefaultResponseTrait::error('Conta não encontrada para o ID informado.');

        $closed_status_id = SV::getValueId('status_ac', 'Fechada');

        $account->a_sv_status_ac_fk = $closed_status_id;
        $account->save();

        $tables = Account::getActives();
        return \App\Traits\DefaultResponseTrait::success('Conta fechada com sucesso.', ['tables' => $tables]);
    }
}
