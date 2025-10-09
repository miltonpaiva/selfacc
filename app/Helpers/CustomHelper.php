<?php
    use App\Traits\DefaultResponseTrait as DfResponse;

    if (!function_exists('convertFieldsMapToModel')){
        /**
         * convertFieldsMapToModel - converte os campos de um array
         * com base no mapeamento definido no model
         *
         * @param  mixed $data
         * @param  mixed $model
         * @return array
         */
        function convertFieldsMapToModel(array $data, object $model): array
        {
            foreach ($data as $key => $value) {
                $new_key = $model::FIELDS_MAP[$key] ?? $key;
                $new_data[$new_key] = $value;
            }

            return $new_data ?? [];
        }
    }

    if (!function_exists('convertFieldsMapToForm')){
        /**
         * convertFieldsMapToForm - converte os campos de um array
         * com base no mapeamento definido no model
         *
         * @param  mixed $data
         * @param  mixed $model
         * @return array
         */
        function convertFieldsMapToForm(array $data, object $model): array
        {
            $flipped_map = array_flip($model::FIELDS_MAP);

            foreach ($data as $key => $value) {
                $new_key = $flipped_map[$key] ?? $key;
                $new_data[$new_key] = $value;
            }

            return $new_data ?? [];
        }
    }

    if (!function_exists('getModelValidates')){
        /**
         * getModelValidates - retorna as regras de validação
         * de um model qualquer com o prefixo do brechó já aplicado
         *
         * @param  mixed $model
         * @return array
         */
        function getModelValidates(object $model, ?bool $is_update = false): array
        {
            $rules = $model::VALIDATES;

            // substituindo as regras de validação para atualização
            if($is_update && $model::VALIDATES_UPDATE)
                $rules = array_merge($rules, $model::VALIDATES_UPDATE);

            return array_map(function($rule){
                return str_replace('[thrift_store_prefix]', getThriftStorePrefix(), $rule);
            }, $rules);
        }
    }

    if (!function_exists('runValidates')){
        /**
         * runValidates - executa a validação de um request com base nas regras informadas
         *
         * @param  mixed $request
         * @param  mixed $model
         * @param  mixed $is_update
         * @return mixed
         */
        function runValidates(object $request, array $validates): mixed
        {
            try {
                $validated = $request->validate($validates, ERROR_MESSAGES);
            } catch (\Throwable $th) {
                return DfResponse::error(ERROR_MESSAGES['request.error'] . ': ' . $th->getMessage(), $request->all());
            }

            return $validated;
        }
    }

    if (!function_exists('runModelValidates')){
        /**
         * runModelValidates - executa a validação de um request com base nas regras informadas no model
         *
         * @param  mixed $request
         * @param  mixed $model
         * @param  mixed $is_update
         * @return mixed
         */
        function runModelValidates(object $request, object $model, ?bool $is_update = false): mixed
        {
            $validates = getModelValidates($model, $is_update);
            return runValidates($request, $validates);
        }
    }

    if (!function_exists('searchAll')){
        /**
         * procura um valor em uma coluna especifica de um array de objetos ou array
         * multidimensional
         *
         * @param      array        $array         The array
         * @param      string       $column        The column
         * @param      string       $search_value  The search value
         * @param      bool|null    $return_data   The return data
         *
         * @return     null|mixed
         */
        function searchAll(array $array, string $column, string $search_value, ?bool $return_data = false): mixed
        {
            foreach ($array as $key => $array_values) {

                $column_value = null;
                if (isset($array_values->{$column})) $column_value = $array_values->{$column};
                if (isset($array_values[$column]))   $column_value = $column_value = $array_values[$column];

                $is_found = ($column_value == $search_value);

                // se for para retornar os dados
                if ($is_found && $return_data) return $array_values;

                // se for para retornar a key
                if ($is_found) return $key;
            }

            return null;
        }
    }

    if (!function_exists('objToArray')){
        /**
         * objToArray - retorna os dados do objeto passado como um array
         *
         * @param  mixed $obj
         * @return mixed|array
         */
        function objToArray(mixed $obj): mixed
        {
            if(!$obj || !is_object($obj)) return $obj;

            return json_decode(json_encode($obj), true);
        }
    }