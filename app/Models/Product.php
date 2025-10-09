<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SimpleValues as SV;

/**
 * Modelo Products
 *
 * Gerencia a tabela 'product', que armazena informações de produtos,
 * incluindo nome, preço e categoria.
 */
class Products extends Model
{
    // Define a chave primária da tabela 'product'
    protected $primaryKey = 'p_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'p_id',
        'p_name',
        'p_price',
        'p_description',
        'p_sv_category_pd_fk',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'added_linked_category_description', // Descrição da categoria (obtido de SimpleValues)
    ];

    /**
     * timestamps - desabilita os campos de controle de criação/atualização
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * VALIDATES - regras de validação para criação/atualização de produtos
     *
     * @var array
     */
    const VALIDATES = [
        // campos de dados
        'name'        => 'required|string|max:255',
        'price'       => 'required|numeric|min:0.01',
        'description' => 'required|string',

        // chaves estrangeiras
        'category_id' => 'required|int|exists:simple_values,sv_id',
    ];

    /**
     * VALIDATES_UPDATE - regras de validação para atualização de produtos
     *
     * @var array
     */
    const VALIDATES_UPDATE = [
        'id'          => 'required|int|exists:product,p_id',
    ];

    /**
     * FIELDS_MAP - mapeamento dos campos do model para os campos do formulário/API
     *
     * @var array
     */
    const FIELDS_MAP = [
        'id'                  => 'p_id',
        'name'                => 'p_name',
        'price'               => 'p_price',
        'description'         => 'p_description',
        'category_id'         => 'p_sv_category_pd_fk',
        'date_created'        => 'p_dt_created',
        'date_updated'        => 'p_dt_updated',
        'category_description'=> 'added_linked_category_description',
    ];

    /**
     * LABELS_MAP - mapeamento dos titulos dos campos do model para exibição amigável
     *
     * @var array
     */
    const LABELS_MAP = [
        'id'                  => '#',
        'name'                => 'Nome do Produto',
        'price'               => 'Preço',
        'description'         => 'Descrição',
        'category_description'=> 'Categoria',
        'date_created'        => 'Data de Criação',
        'date_updated'        => 'Última Atualização',
    ];

    /**
     * INDEXABLE_COLUMNS - colunas que podem ser indexadas para busca
     *
     * @var array
     */
    const INDEXABLE_COLUMNS = [
        'p_name',
        'p_price',
        'p_dt_created',
        'added_linked_category_description',
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
     * getLinkedCategory - O produto pertence a uma Categoria (SimpleValues).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedCategory(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SV::class, 'sv_id', 'p_sv_category_pd_fk');
    }

    // --- ACCESSORS ---

    /**
     * getAddedLinkedCategoryDescriptionAttribute - Retorna a descrição da categoria do produto.
     *
     * @return string|null
     */
    public function getAddedLinkedCategoryDescriptionAttribute(): ?string
    {
        $category = $this->getLinkedCategory()->first();
        // Assume que o modelo SimpleValues tem a coluna 'sv_title'
        return $category ? $category->sv_title : null;
    }
}
