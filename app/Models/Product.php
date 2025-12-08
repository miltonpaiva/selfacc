<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SimpleValues as SV;

/**
 * Modelo Product
 *
 * Gerencia a tabela 'product', que armazena informações de produtos,
 * incluindo nome, preço e categoria.
 */
class Product extends Model
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
        'p_dt_created',
        'p_sv_category_pd_fk',
        'p_image',
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

    const VALIDATES_MESSAGES =
    [
        'name.required'     => 'O campo Nome é obrigatório',
        'name.string'       => 'O campo Nome deve ser um texto (string)',
        'name.max'          => 'O campo Nome deve ter no máximo 255 caracteres',

        'price.required'    => 'O campo Preço é obrigatório',
        'price.numeric'     => 'O campo Preço deve ser um valor numérico',
        'price.min'         => 'O campo Preço deve ser no mínimo 0.01',

        'description.required' => 'O campo Descrição é obrigatório',
        'description.string'   => 'O campo Descrição deve ser um texto (string)',

        'category_id.required' => 'O campo ID da Categoria é obrigatório',
        'category_id.int'      => 'O campo ID da Categoria deve ser um número inteiro',
        'category_id.exists'   => 'O ID da Categoria selecionado não existe',
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
        'image'               => 'p_image',
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

    /**
     * getProductsAgrouped - Retorna os produtos agrupados por categoria.
     *
     * @return array
     */
    public static function getProductsAgrouped(): array
    {
        $products_form = convertFieldsMapToFormList(Product::all()->toArray(), new Product());

        foreach($products_form as $key => $product) {
            $category = $product['category_description'] ?? 'Sem Categoria';
            $c_slug   = slugify($category);

            $grouped_products[$c_slug]['category_name'] = $category;
            $grouped_products[$c_slug]['category_slug'] = $c_slug;
            $grouped_products[$c_slug]['products'][]    = $product;
        }

        return $grouped_products ?? [];
    }
}
