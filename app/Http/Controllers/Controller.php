<?php

namespace App\Http\Controllers;

use App\Traits\DefaultResponseTrait as DfResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\Search;

abstract class Controller
{
    use DfResponse;

    /**
     * newOrUpdateModel - cria ou atualiza a o model informado após realizar aas validações configuradas no model
     *
     * @param  mixed $request
     * @param  mixed $model
     * @param  array $messages
     * @param  mixed $column_hashable
     * @param  mixed $is_update
     * @return Illuminate\Http\JsonResponse
     */
    public function newOrUpdateModel(object $request, object $model, ?string $column_hashable = null, ?bool $is_update): \Illuminate\Http\JsonResponse
    {
        $validated_data = runModelValidates($request, $model, $is_update);
        if(is_object($validated_data)) return $validated_data;

        if($is_update) $entity = $model::find($request->input('id'));

        $model_data = convertFieldsMapToModel($validated_data, $model);

        // criando hash da coluna informada para hash
        if(isset($model_data[$column_hashable]) && !empty($model_data[$column_hashable]))
            $model_data[$column_hashable] = Hash::make($model_data[$column_hashable]);

        if($is_update){
            try {
                $entity->update($model_data);
            } catch (\Throwable $th) {
                return self::error('Não foi possivel atualizar: ' . $th->getMessage(), $model_data, 500);
            }

            // realizando a indexação do usuario para busca
            (new Search($entity, ''))::runIndexes($entity->toArray());

            return self::success('Atualizado !', convertFieldsMapToForm($entity->toArray(), $model));
        }

        try {
            $entity = $model::create($model_data);
        } catch (\Throwable $th) {
            return self::error('Não foi possivel criar: ' . $th->getMessage(), $model_data, 500);
        }

        // realizando a indexação do usuario para busca
        (new Search($entity, ''))::runIndexes($entity->toArray());

        return self::success("Criado !", convertFieldsMapToForm($entity->toArray(), $model));
    }

    /**
     * listModel - retorna  da lista de registros com base nos argumentos enviados e nos dados da model passada
     *
     * @param  mixed $request
     * @param  mixed $model
     * @return Illuminate\Http\JsonResponse
     */
    public function listModel(object $request, object $model): \Illuminate\Http\JsonResponse
    {
        $limit_per_page = $request->get('limit_per_page') ?? 10;
        $paginate_data  = $model::paginate($limit_per_page)->toArray();

        $max_page       = $paginate_data['last_page'] ?? 0;
        $total_listed   = $paginate_data['total']     ?? 0;
        $labels_map     = $model::LABELS_MAP;
        $columns        = $request->get('columns') ?? array_values(array_flip($labels_map));
        $columns[]      = 'id'; // garantindo que o id sempre virá
        $labels_map     = array_intersect_key($labels_map, array_flip($columns));

        if (empty($paginate_data['data']))
            return self::error('Não há registros para a pagina informada', $request->all(), 404);

        // convertendo os dados para o formato do form e limitando as colunas
        foreach ($paginate_data['data'] as $entity){
            $entity = convertFieldsMapToForm($entity, $model);
            $entity = array_intersect_key($entity, array_flip($columns));

            $list[$entity['id']] = $entity;
        }

        return self::success('Lista de registros', [
            'max_page'          => $max_page,
            'total_listed'      => $total_listed,
            'list'              => $list ?? [],
            'labels'            => $labels_map,
            'available_columns' => $model::LABELS_MAP,
        ]);
    }
}
