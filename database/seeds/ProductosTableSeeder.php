<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Models\Producto;
use App\Models\Especie;
use App\Models\Calibre;
use App\Models\Formato;
use App\Models\Trim;
use App\Models\Calidad;
use App\Models\Envase;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        $productos = [];

        $productos [] = ['producto_nombre' => 'Trim E',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 1,//600-900
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 2,//TE
            'producto_calidad_id' => 6,//Superior
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim E',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 1,//600-900
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 2,//TE
            'producto_calidad_id' => 7, //M
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim E',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 2,//900-1350
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 2,//TE
            'producto_calidad_id' => 6, //Superior
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim E',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 2,//900-1350
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 2,//TE
            'producto_calidad_id' => 7, //M
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim E',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 3,//1350-1800
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 2,//TE
            'producto_calidad_id' => 6, //Superior
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim E',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 3,//1350-1800
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 2,//TE
            'producto_calidad_id' => 7, //M
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim E',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 4,//S/C
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 2,//TE
            'producto_calidad_id' => 7, //M
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        //TD

        $productos [] = ['producto_nombre' => 'Trim D',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 1,//600-900
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 1,//TD
            'producto_calidad_id' => 8, //Ind
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim D',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 1,//600-900
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 1,//TD
            'producto_calidad_id' => 7, //M
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim D',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 2,//900-1350
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 1,//TD
            'producto_calidad_id' => 8, //Ind
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim D',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 2,//900-1350
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 1,//TD
            'producto_calidad_id' => 7, //M
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim D',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 3,//1350-1800
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 1,//TD
            'producto_calidad_id' => 8, //Ind
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        $productos [] = ['producto_nombre' => 'Trim D',
            'producto_especie_id' => 1,
            'producto_calibre_id' => 3,//1350-1800
            'producto_condicion_id' => 2,
            'producto_formato_id' => 1,
            'producto_trim_id' => 1,//TD
            'producto_calidad_id' => 7, //M
            'producto_v2_id' => 1,
            'producto_codigo' => $faker->randomDigit,
        ];

        Producto::insert($productos);
    }
}
