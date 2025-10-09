<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SimpleValues as SV;
use Illuminate\Auth\Authenticatable; // Necessário para autenticação básica
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

/**
 * Modelo User
 *
 * Gerencia a tabela 'user', que armazena dados de autenticação
 * e informações básicas do usuário.
 */
class User extends Model implements AuthenticatableContract
{
    // Adiciona o trait Authenticatable para usar com o sistema de autenticação do Laravel
    use Authenticatable;

    // Define a chave primária da tabela 'user'
    protected $primaryKey = 'u_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'u_id',
        'u_name',
        'u_email',
        'u_pass',
        'u_sv_type_us_fk',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'added_linked_user_type', // Descrição do tipo de usuário
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'u_pass', // Esconde a senha ao serializar o modelo para JSON/Array
    ];

    /**
     * timestamps - desabilita os campos de controle de criação/atualização
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * VALIDATES - regras de validação para criação de usuário
     *
     * @var array
     */
    const VALIDATES = [
        // campos de dados
        'name'      => 'required|string|max:255',
        'email'     => 'required|string|email|unique:user,u_email|max:255',
        'password'  => 'required|string|min:8', // Deve ser tratada (hash) no Controller/Service

        // chaves estrangeiras
        'user_type_id' => 'required|int|exists:simple_values,sv_id',
    ];

    /**
     * VALIDATES_UPDATE - regras de validação para atualização de usuário
     *
     * @var array
     */
    const VALIDATES_UPDATE = [
        'id'        => 'required|int|exists:user,u_id',
        'email'     => 'string|email|max:255', // Unique será tratado separadamente (ignore a si mesmo)
        'password'  => 'nullable|string|min:8',
    ];

    /**
     * FIELDS_MAP - mapeamento dos campos do model para os campos do formulário/API
     *
     * @var array
     */
    const FIELDS_MAP = [
        'id'                  => 'u_id',
        'name'                => 'u_name',
        'email'               => 'u_email',
        'password'            => 'u_pass',
        'user_type_id'        => 'u_sv_type_us_fk',
        'date_created'        => 'u_dt_created',
        'date_updated'        => 'u_dt_updated',
        'user_type_description'=> 'added_linked_user_type',
    ];

    /**
     * LABELS_MAP - mapeamento dos titulos dos campos do model para exibição amigável
     *
     * @var array
     */
    const LABELS_MAP = [
        'id'                  => '#',
        'name'                => 'Nome',
        'email'               => 'E-mail',
        'password'            => 'Senha',
        'user_type_description'=> 'Tipo de Usuário',
        'date_created'        => 'Data de Criação',
        'date_updated'        => 'Última Atualização',
    ];

    /**
     * INDEXABLE_COLUMNS - colunas que podem ser indexadas para busca
     *
     * @var array
     */
    const INDEXABLE_COLUMNS = [
        'u_name',
        'u_email',
        'added_linked_user_type',
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
     * getLinkedUserType - O usuário pertence a um Tipo (SimpleValues).
     *
     * @return Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getLinkedUserType(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SV::class, 'sv_id', 'u_sv_type_us_fk');
    }

    // --- ACCESSORS ---

    /**
     * getAddedLinkedUserTypeAttribute - Retorna a descrição do tipo de usuário.
     *
     * @return string|null
     */
    public function getAddedLinkedUserTypeAttribute(): ?string
    {
        $userType = $this->getLinkedUserType()->first();
        // Assume que o modelo SimpleValues tem a coluna 'sv_title'
        return $userType ? $userType->sv_title : null;
    }
}
