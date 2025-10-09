<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Customers
 *
 * Gerencia a tabela 'customer'. Segue o padrão de mapeamento de campos (FIELDS_MAP),
 * regras de validação (VALIDATES) e desativação de timestamps personalizados.
 */
class Customers extends Model
{
    // Define a chave primária da tabela 'customer'
    protected $primaryKey = 'c_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'c_id',
        'c_name',
        'c_code',
        'c_phone',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
    ];

    /**
     * timestamps - desabilita os campos de controle de criação/atualização
     *
     * NOTA: Os campos 'c_dt_created' e 'c_dt_updated' são gerados pelo banco
     * de dados (migration) e não pelo Eloquent, por isso, desabilitamos $timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * VALIDATES - regras de validação para criação de clientes/fornecedores
     *
     * @var array
     */
    const VALIDATES = [
        // campos de dados
        'name'  => 'required|string|max:255',
        'code'  => 'required|int',
        'phone' => 'nullable|string|max:50',
    ];

    /**
     * VALIDATES_UPDATE - regras de validação para atualização
     *
     * @var array
     */
    const VALIDATES_UPDATE = [
        'id'   => 'required|int|exists:customer,c_id',
        // 'code' é 'exclude' pois geralmente o código é imutável após a criação
        'code' => 'exclude',
    ];

    /**
     * FIELDS_MAP - mapeamento dos campos do model para os campos do formulário/API
     *
     * @var array
     */
    const FIELDS_MAP = [
        'id'                  => 'c_id',
        'name'                => 'c_name',
        'code'                => 'c_code',
        'phone'               => 'c_phone',
        'date_created'        => 'c_dt_created',
        'date_updated'        => 'c_dt_updated',
    ];

    /**
     * LABELS_MAP - mapeamento dos titulos dos campos do model para exibição amigável
     *
     * @var array
     */
    const LABELS_MAP = [
        'id'                  => '#',
        'name'                => 'Nome do Cliente/Fornecedor',
        'code'                => 'Código de Cliente',
        'phone'               => 'Telefone',
        'date_created'        => 'Data de Cadastro',
        'date_updated'        => 'Última Atualização',
    ];

    /**
     * INDEXABLE_COLUMNS - colunas que podem ser indexadas para busca
     *
     * @var array
     */
    const INDEXABLE_COLUMNS = [
        'c_name',
        'c_code',
        'c_phone',
    ];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
    }
}
