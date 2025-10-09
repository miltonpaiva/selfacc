<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo SimpleValues
 *
 * Gerencia a tabela 'simple_values', usada para armazenar listas de valores
 * simples categorizados (ex: status, categorias, tipos, canais de venda).
 * Mantém a estrutura de mapeamento e validação com constantes.
 */
class SimpleValues extends Model
{
    // Define a chave primária da tabela 'simple_values'
    protected $primaryKey = 'sv_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'simple_values';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sv_id',
        'sv_title',
        'sv_group',
    ];

    /**
     * timestamps - desabilita os campos de controle de criação/atualização
     *
     * NOTA: Os campos 'sv_dt_created' e 'sv_dt_updated' são gerados pelo banco
     * de dados (migration) e não pelo Eloquent, por isso, desabilitamos $timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * VALIDATES - regras de validação para criação de novos valores
     *
     * @var array
     */
    const VALIDATES = [
        // campos de texto
        'group' => 'required|string|max:255',
        'title' => 'required|string|max:255',
    ];

    /**
     * VALIDATES_UPDATE - regras de validação para atualização
     *
     * @var array
     */
    const VALIDATES_UPDATE = [
        'id'    => 'required|int|exists:simple_values,sv_id',
        'group' => 'required|string|max:255', // Ignora o próprio ID
        'title' => 'required|string|max:255', // Ignora o próprio ID
    ];

    /**
     * FIELDS_MAP - mapeamento dos campos do model para os campos do formulário/API
     *
     * @var array
     */
    const FIELDS_MAP = [
        'id'                  => 'sv_id',
        'title'               => 'sv_title',
        'group'               => 'sv_group',
        'date_created'        => 'sv_dt_created',
        'date_updated'        => 'sv_dt_updated',
    ];

    /**
     * LABELS_MAP - mapeamento dos titulos dos campos do model para exibição amigável
     *
     * @var array
     */
    const LABELS_MAP = [
        'id'                  => '#',
        'group'               => 'Grupo de Valor Simples',
        'title'               => 'Titulo de Valor Simples',
        'date_created'        => 'Data de Criação',
        'date_updated'        => 'Data de Atualização',
    ];

    /**
     * INDEXABLE_COLUMNS - colunas que podem ser indexadas para busca
     *
     * @var array
     */
    const INDEXABLE_COLUMNS = [
        'sv_group',
        'sv_title',
    ];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
    }

    /**
     * getValueId - retorna um id do valor baseado no grupo e titulo
     *
     * @param  string $group
     * @param  string $title
     * @return int
     */
    public static function getValueId(string $group, string $title): ?int
    {
        $value = self::where(
            [
                ['sv_group', '=', $group],
                ['sv_title', '=', $title],
            ]
        )->first('sv_id');

        if (!$value) return null;

        return $value->toArray()['sv_id'] ?? null;
    }

    /**
     * insertOrFind - verifica se um valor ja existe num determinado grupo,
     * se houver retorna o id, se não houver, cria
     *
     * @param  string $group
     * @param  string $title
     * @return int
     */
    public static function insertOrFind(string $group, string $title): ?int
    {
        $exists_id = self::getValueId($group, $title);

        if ($exists_id) return $exists_id;

        $new_value = self::create([
            'sv_group' => $group,
            'sv_title' => $title,
        ]);

        return $new_value->getKey();
    }

}
