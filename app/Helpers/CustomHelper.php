<?php
    use App\Traits\DefaultResponseTrait as DfResponse;
use Illuminate\Database\Eloquent\Model;

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

    if (!function_exists('convertFieldsMapToFormList')){
        /**
         * convertFieldsMapToFormList - converte os campos de um array multidimensional
         * com base no mapeamento definido no model
         *
         * @param  mixed $data
         * @param  mixed $model
         * @return array
         */
        function convertFieldsMapToFormList(array $data, object $model): array
        {
            return array_map(function ($data) use ($model){
                return convertFieldsMapToForm($data, $model);
            }, $data);
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

            return $rules;
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
        function runValidates(object $request, array $validates, ?array $messages = null): mixed
        {
            try {
                $validated = $request->validate($validates, $messages);
            } catch (\Throwable $th) {
                return DfResponse::error('Erro na requisição: ' . $th->getMessage(), $request->all());
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
            return runValidates($request, $validates, defined(get_class($model) . '::VALIDATES_MESSAGES')? $model::VALIDATES_MESSAGES : []);
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

    if (!function_exists('slugify')){
        /**
         * slugify - retorna o slug de uma string
         *
         * @param  mixed $text
         * @param  mixed $separator
         * @return string
         */
        function slugify(mixed $text, ?string $separator = '-'): ?string
        {
            if (!$text || !is_string($text)) return $text;

            // Convert to lowercase
            $text = mb_strtolower($text, 'UTF-8');

            // Replace non-ASCII characters with their ASCII equivalents (transliteration)
            // This example uses a basic replacement, a library is more robust for full Unicode support
            $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);

            // Remove unwanted characters (keep letters, numbers, and hyphens)
            $text = preg_replace('/[^a-z0-9-]+/', '-', $text);

            // Replace multiple hyphens with a single hyphen
            $text = preg_replace('/-+/', '-', $text);

            // Trim hyphens from the beginning and end
            $text = trim($text, $separator);

            return $text;
        }
    }

    if (!function_exists('getTimeStr')){
        /**
         * getTimeStr - retorna o tempo baseado nos milisegundos informados
         *
         * @param  mixed $text
         * @param  mixed $separator
         * @return string
         */
        function getTimeStr(int $ms): ?string
        {
            return sprintf("%02d", round(($ms / 60000))) . ':' . sprintf("%02d", round(($ms / 10000)));
        }
    }

    if (!function_exists('ordenateAll')){
        /**
         * orderna array multidimensional ou array de objetos pela coluna e ordem informada
         * @param  array  $array
         * @param  string $column
         * @return array
         */
        function ordenateAll(array $array, string $column, bool $asc = true): array
        {
            // definindo na sessão para uso dentro da função de ordenação
            @session_start();
            $_SESSION['ORDENATE_COLUMN'] = $column;
            $_SESSION['ORDENATE_ASC']    = $asc;

            uasort($array, function ($data_a, $data_b) {

                // pegando os valores da coluna indicada conforme o tipo de dado passado
                $vale_a = is_object($data_a)? $data_a->{$_SESSION['ORDENATE_COLUMN']} ?? '' : $data_a[$_SESSION['ORDENATE_COLUMN']] ?? '';
                $vale_b = is_object($data_b)? $data_b->{$_SESSION['ORDENATE_COLUMN']} ?? '' : $data_b[$_SESSION['ORDENATE_COLUMN']] ?? '';

                // verifica se o valor é numero para uma melhor ordenação
                if(is_numeric($vale_a)) $vale_a = (int) $vale_a;
                if(is_numeric($vale_b)) $vale_b = (int) $vale_b;

                // defime qual dos valores é maior
                $b_is_bigger = ($vale_a < $vale_b);
                $a_is_bigger = ($vale_a > $vale_b);

                // se os valores são iguais
                if( $vale_a == $vale_b ) return 0;

                // se a ordenação for ascendente
                if($_SESSION['ORDENATE_ASC']) return ( $b_is_bigger? -1 : 1 );

                // se a ordenação for descendente
                return ( $a_is_bigger? -1 : 1 );
            });

            return $array;
        }
    }

