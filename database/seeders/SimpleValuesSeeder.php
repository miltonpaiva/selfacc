<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SimpleValues;

class SimpleValuesSeeder extends Seeder
{
    const SEED_VALUES = [
        // VALORES RELACIONADOS A TABELA DE LOTES
        ['sv_title' => 'Aberta',  'sv_group' => 'status_ac'],
        ['sv_title' => 'Fechada', 'sv_group' => 'status_ac'],

        ['sv_title' => 'Arrematante', 'sv_group' => 'status_ma'],
        ['sv_title' => 'Pendente',    'sv_group' => 'status_ma'],
        ['sv_title' => 'Encerrado',   'sv_group' => 'status_ma'],

        ['sv_title' => 'Na Fila',     'sv_group' => 'status_mq'],
        ['sv_title' => 'A Seguir',    'sv_group' => 'status_mq'],
        ['sv_title' => 'Reproduzindo','sv_group' => 'status_mq'],
        ['sv_title' => 'Reproduzido', 'sv_group' => 'status_mq'],

        ['sv_title' => 'created',    'sv_group' => 'status_nt'],
        ['sv_title' => 'sended',     'sv_group' => 'status_nt'],
        ['sv_title' => 'completed',  'sv_group' => 'status_nt'],

        ['sv_title' => 'all_customer',      'sv_group' => 'topic_nt'],
        ['sv_title' => 'all_waiter',        'sv_group' => 'topic_nt'],
        ['sv_title' => 'specific_customer', 'sv_group' => 'topic_nt'],
        ['sv_title' => 'specific_waiter',   'sv_group' => 'topic_nt'],

        ['sv_title' => 'administrator', 'sv_group' => 'type_us'],
        ['sv_title' => 'waiter',        'sv_group' => 'type_us'],
        ['sv_title' => 'kitchen',       'sv_group' => 'type_us'],

        ['sv_title' => 'Bebidas',              'sv_group' => 'category_pd'],
        ['sv_title' => 'Cervejas Litrão',      'sv_group' => 'category_pd'],
        ['sv_title' => 'Cervejas Buchudinhas', 'sv_group' => 'category_pd'],
        ['sv_title' => 'Pratos',               'sv_group' => 'category_pd'],
        ['sv_title' => 'Petiscos e Entradas',  'sv_group' => 'category_pd'],
        ['sv_title' => 'Adicionais',           'sv_group' => 'category_pd'],
        ['sv_title' => 'Guarnições',           'sv_group' => 'category_pd'],
        ['sv_title' => 'Outros',               'sv_group' => 'category_pd'],

    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SimpleValues::truncate();

        foreach (self::SEED_VALUES as $data)
            $results[] = (SimpleValues::create($data)->getKey() ?? null) . ' : ' . json_encode($data);

        print_r($results);
    }
}
