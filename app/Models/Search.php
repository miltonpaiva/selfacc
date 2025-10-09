<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class Search
{
    /**
     * VALIDATES - regras de validação para busca
     *
     * @var array
     */
    const VALIDATES = [
        'term'   => 'required|string|min:3',
        'column' => 'required|string',
    ];

    /** @var mixed $index_disk
     *  instancia do disco de armazenamento local
    */
    private static $index_disk;

    /** @var mixed $index_rules
     * regras do tempo de vida dos arquivos de cache
    */
    public static $index_rules;

    /** @var mixed $model
     * instancia do model que está sendo trabalhado
     */
    public static $model;

    /** @var mixed $index_model_name
     * nome do model que está sendo trabalhado
     */
    public static $index_model_name;

    /** @var mixed $search_column
     * coluna do model trabalhado a ser indexada
     */
    public static $search_column;

    /** @var mixed $pk
     * chave primaria do model trabalhado
     */
    public static $pk;

    /**
     * __construct
     *
     * @param  mixed $model
     * @param  string $column
     * @return void
     */
    function __construct(object $model, string $column, ?array $index_rules = null) {

        // garantindo o horario brasileiro
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        // regras de vida do arquivo de index
        self::$index_rules = $index_rules ?? [
            'index_lifetime' => false, // 2 seconds | 30 minutes | 1 hour | 2 hours | 1 day | 2 days
        ];

        // definindo o disco local para salvar os arquivos de index
        self::$index_disk = Storage::build([
            'driver' => 'local',
            'root'   => storage_path() . '\selfacc_search_local_indexes',
        ]);

        // definindo o model e coluna que será trabalhada
        self::$model            = $model;
        self::$pk               = $model->getKeyName();
        self::$index_model_name = (new \ReflectionClass($model))->getShortName();
        self::$search_column    = array_flip(
                                        convertFieldsMapToModel([$column => 'column'], $model)
                                  )['column'];
    }

    /**
     * modelSearch - realiza uma busca em uma coluna especifica de um model
     *
     * @param  string $search_params
     * @return array
     */
    public static function modelSearch(string $search_term): array
    {
        return self::$model::where(
                    self::$search_column,
                    'LIKE',
                    "%{$search_term}%"
                )->get([self::$pk, self::$search_column])->toArray();
    }

    /**
     * indexSearch - realiza uma busca no index de uma coluna especifica do model informado
     *
     * @param  string $search_term
     * @return array
     */
    public static function indexSearch(string $search_term): array
    {
        $indexes = self::getValidIndex();

        if(!$indexes) return [];

        foreach ($indexes as $index) {
            $search_str = $index[self::$search_column] ?? '';

            if(stripos($search_str, $search_term) === false) continue;

            $results[] = $index;
        }

        return $results ?? [];
    }

    /**
     * runIndexes - executa a criação ou atualização dos indexes para todas as colunas indexaveis do model
     *
     * @param  mixed $specific_data
     * @return array
     */
    public static function runIndexes(?array $specific_data = null): array
    {
        $reflectionClass   = new \ReflectionClass(self::$model);
        $has_index_columns = $reflectionClass->hasConstant('INDEXABLE_COLUMNS');

        $columns = $has_index_columns? self::$model::INDEXABLE_COLUMNS : [];

        foreach ($columns as $column) {
            self::$search_column = $column;
            // criando ou atualizando o index
            $results[$column] = self::index($specific_data);
        }

        return $results ?? [];
    }

    /**
     * index - cria ou atualiza o arquivo de index para a busca
     *
     * @param  mixed $specific_data
     * @return bool
     */
    public static function index(?array $specific_data = null): bool
    {
        $current_index = self::getValidIndex();

        // se ja ouver index e não for uma ciração/atualização de index especifico
        if($current_index && !$specific_data) return true;

        $all_data = $current_index ?? self::$model->all()->toArray();

        // Se houver um dado especifico, atualiza ou cria o mesmo no array de dados
        if($specific_data){

            $specific_data_key = searchAll($all_data, self::$pk, $specific_data[self::$pk]);

            if($specific_data_key)  $all_data[$specific_data_key] = $specific_data;
            if(!$specific_data_key) $all_data[]                   = $specific_data;
        }

        // resumindo os dados para criar o index
        foreach ($all_data as $value) {
            $pk_key              = $value[self::$pk];
            $index_data[$pk_key] =
            [
                self::$pk            => $pk_key,
                self::$search_column => $value[self::$search_column] ?? '',
            ];
        }

        return self::createIndex($index_data);
    }

    /**
     * createIndex - cria um arquivo de index para a busca
     *
     * @param  array $data
     * @return bool
     */
    public static function createIndex(array $data): bool
    {
        $index_file = self::$index_model_name . "_" . self::$search_column . "_index.json";

        return self::$index_disk->put($index_file, json_encode($data, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES));
    }

    /**
     * getValidIndex - obtém o arquivo de index caso o mesmo seja válido
     *
     * @return array
     */
    public static function getValidIndex(): ?array
    {
        $index_file = self::$index_model_name . "_" . self::$search_column . "_index.json";
        $index_data = self::$index_disk->json($index_file);

        if(!$index_data) return null;

        if(!self::validateIndexLife($index_file)) return null;

        return $index_data;
    }

    /**
     * validateIndexLife - valida o tempo de vida do arquivo de index
     * deletando o mesmo caso esteja expirado
     *
     * @param  string $index_file
     * @return bool
     */
    public static function validateIndexLife(string $index_file): bool
    {
        // se não houver regras de vida, sempre retorna true
        if (!self::$index_rules['index_lifetime']) return true;

        $now_time        = strtotime("now");
        $updated_at_date = date("Y-m-d H:i:s", self::$index_disk->lastModified($index_file));
        $rule            = self::$index_rules['index_lifetime'];
        $valid_time      = strtotime("{$updated_at_date} + {$rule}");
        $is_valid_time   = $now_time < $valid_time;

        // se o tempo for inválido, apaga o arquivo de index
        if(!$is_valid_time) self::$index_disk->delete($index_file);

        return $is_valid_time;
    }
}
