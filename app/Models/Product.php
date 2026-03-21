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
        'p_purchase_price',
        'p_expected_profit',
        'p_description',
        'p_image',
        'p_quantity_purchase',
        'p_quantity_sales',
        'p_dt_created',
        'p_sv_category_pd_fk',
        'p_sv_purchasing_unit_pd_fk',
        'p_sv_sales_unit_pd_fk',
        'p_image',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'added_linked_category_description',        // Descrição da categoria (obtido de SimpleValues)
        'added_linked_purchasing_unit_description', // Descrição da unidade de compra (obtido de SimpleValues)
        'added_linked_sales_unit_description',      // Descrição da unidade de venda (obtido de SimpleValues)
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
        'name'              => 'required|string|max:255',
        'price'             => 'required|numeric|min:0.01',
        'purchase_price'    => 'required|numeric|min:0.01',
        'expected_profit'   => 'required|numeric|min:0.01',
        'quantity_purchase' => 'numeric|min:1',
        'quantity_sales'    => 'numeric|min:1',
        'description'       => 'required|string',

        // chaves estrangeiras
        'category_id'        => 'required|int|exists:simple_values,sv_id',
        'purchasing_unit_id' => 'required|int|exists:simple_values,sv_id',
        'sales_unit_id'      => 'required|int|exists:simple_values,sv_id',
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

        'purchase_price.required'    => 'O campo Preço de compra é obrigatório',
        'purchase_price.numeric'     => 'O campo Preço de compra deve ser um valor numérico',
        'purchase_price.min'         => 'O campo Preço de compra deve ser no mínimo 0.01',

        'expected_profit.required'    => 'O campo lucro esperado é obrigatório',
        'expected_profit.numeric'     => 'O campo lucro esperado deve ser um valor numérico',
        'expected_profit.min'         => 'O campo lucro esperado deve ser no mínimo 0.01',

        'quantity_purchase.numeric'     => 'O campo quantidade de compra deve ser um valor numérico',
        'quantity_purchase.min'         => 'O campo quantidade de compra deve ser no mínimo 0.01',

        'quantity_sales.numeric'     => 'O campo quantidade de venda deve ser um valor numérico',
        'quantity_sales.min'         => 'O campo quantidade de venda deve ser no mínimo 0.01',

        'description.required' => 'O campo Descrição é obrigatório',
        'description.string'   => 'O campo Descrição deve ser um texto (string)',

        'category_id.required' => 'O campo ID da Categoria é obrigatório',
        'category_id.int'      => 'O campo ID da Categoria deve ser um número inteiro',
        'category_id.exists'   => 'O ID da Categoria selecionado não existe',

        'purchasing_unit_id.required' => 'O campo ID da Unidade de Compra é obrigatório',
        'purchasing_unit_id.int'      => 'O campo ID da Unidade de Compra deve ser um número inteiro',
        'purchasing_unit_id.exists'   => 'O ID da Unidade de Compra selecionado não existe',

        'sales_unit_id.required' => 'O campo ID da Unidade de Venda é obrigatório',
        'sales_unit_id.int'      => 'O campo ID da Unidade de Venda deve ser um número inteiro',
        'sales_unit_id.exists'   => 'O ID da Unidade de Venda selecionado não existe',
    ];

    /**
     * FIELDS_MAP - mapeamento dos campos do model para os campos do formulário/API
     *
     * @var array
     */
    const FIELDS_MAP = [
        'id'                          => 'p_id',
        'name'                        => 'p_name',
        'price'                       => 'p_price',
        'purchase_price'              => 'p_purchase_price',
        'expected_profit'             => 'p_expected_profit',
        'quantity_purchase'           => 'p_quantity_purchase',
        'quantity_sales'              => 'p_quantity_sales',
        'description'                 => 'p_description',
        'category_id'                 => 'p_sv_category_pd_fk',
        'purchasing_unit_id'          => 'p_sv_purchasing_unit_pd_fk',
        'sales_unit_id'               => 'p_sv_sales_unit_pd_fk',
        'date_created'                => 'p_dt_created',
        'date_updated'                => 'p_dt_updated',
        'image'                       => 'p_image',
        'category_description'        => 'added_linked_category_description',
        'purchasing_unit_description' => 'added_linked_purchasing_unit_description',
        'sales_unit_description'      => 'added_linked_sales_unit_description',
    ];

    /**
     * LABELS_MAP - mapeamento dos titulos dos campos do model para exibição amigável
     *
     * @var array
     */
    const LABELS_MAP = [
        'id'                          => '#',
        'name'                        => 'Nome do Produto',
        'price'                       => 'Preço',
        'purchase_price'              => 'Preço de Compra',
        'expected_profit'             => 'Lucro Esperado',
        'quantity_purchase'           => 'Quantidade de Compra',
        'quantity_sales'              => 'Quantidade de Venda',
        'description'                 => 'Descrição',
        'category_description'        => 'Categoria',
        'purchasing_unit_description' => 'Unidade de Compra',
        'sales_unit_description'      => 'Unidade de Venda',
        'date_created'                => 'Data de Criação',
        'date_updated'                => 'Última Atualização',
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

    /**
     * getLinkedPurchasingUnit - O produto contem uma unidade de compra (SimpleValues).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedPurchasingUnit(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SV::class, 'sv_id', 'p_sv_purchasing_unit_pd_fk');
    }

    /**
     * getLinkedSalesUnit - O produto contem uma unidade de venda (SimpleValues).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedSalesUnit(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SV::class, 'sv_id', 'p_sv_sales_unit_pd_fk');
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
     * getAddedLinkedPurchasingUnitDescriptionAttribute - Retorna a descrição da unidade de compra do produto.
     *
     * @return string|null
     */
    public function getAddedLinkedPurchasingUnitDescriptionAttribute(): ?string
    {
        $purchasing_unit = $this->getLinkedPurchasingUnit()->first();
        // Assume que o modelo SimpleValues tem a coluna 'sv_title'
        return $purchasing_unit ? $purchasing_unit->sv_title : null;
    }

    /**
     * getAddedLinkedSalesUnitDescriptionAttribute - Retorna a descrição da unidade de venda do produto.
     *
     * @return string|null
     */
    public function getAddedLinkedSalesUnitDescriptionAttribute(): ?string
    {
        $sales_unit = $this->getLinkedSalesUnit()->first();
        // Assume que o modelo SimpleValues tem a coluna 'sv_title'
        return $sales_unit ? $sales_unit->sv_title : null;
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
