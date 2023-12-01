<?php

namespace Database\Seeders;

use App\Models\Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = collect([
            [
                'name' => 'Toyota',
                'models' => [
                    'Corolla',
                    'Yaris',
                    'Camry',
                    'Prius',
                    'RAV4',
                    'Highlander',
                    'Sienna',
                    'Tacoma',
                    'Tundra',
                    '4Runner',
                    'Sequoia',
                    'Land Cruiser',
                    'Avalon',
                    'C-HR',
                    'Supra',
                    'Mirai',
                    'Venza',
                ]
            ],
            [
                'name' => 'Honda',
                'models' => [
                    'Civic',
                    'Accord',
                    'CR-V',
                    'HR-V',
                    'Pilot',
                    'Odyssey',
                    'Passport',
                    'Ridgeline',
                    'Insight',
                    'Clarity',
                    'Fit',
                    'Civic Type R',
                    'CR-Z',
                    'Element',
                    'FCX Clarity',
                    'Fit EV',
                    'S2000',
                ]
            ],
            [
                'name' => 'Ford',
                'models' => [
                    'Fiesta',
                    'Focus',
                    'Fusion',
                    'Taurus',
                    'Mustang',
                    'EcoSport',
                    'Escape',
                    'Edge',
                    'Flex',
                    'Explorer',
                    'Expedition',
                    'Ranger',
                    'F-150',
                    'Super Duty (F-250, F-350, F-450)'
                ]
            ],
            [
                'name' => 'Chevrolet',
                'models' => [
                    'Spark',
                    'Sonic',
                    'Cruze',
                    'Malibu',
                    'Impala',
                    'Volt',
                    'Bolt EV',
                    'Trax',
                    'Equinox',
                    'Blazer',
                    'Traverse',
                    'Tahoe',
                    'Suburban',
                    'Colorado',
                    'Silverado',
                    'Corvette',
                    'Camaro',
                    'Express',
                ]
            ],
            [
                'name' => 'Volkswagen',
                'models' => [
                    'Jetta',
                    'Passat',
                    'Arteon',
                    'Golf',
                    'GTI',
                    'Golf R',
                    'e-Golf',
                    'Beetle',
                    'Golf SportWagen',
                    'Golf Alltrack',
                    'Tiguan',
                    'Atlas',
                    'Touareg',
                    'e-Golf',
                    'ID.4',
                ]    
            ],
            [
                'name'=> 'Nissan',
                'models' => [
                    'Versa',
                    'Sentra',
                    'Altima',
                    'Maxima',
                    'LEAF',
                    'Juke',
                    'Kicks',
                    'Rogue Sport',
                    'Rogue',
                    'Murano',
                    'Pathfinder',
                    'Armada',
                    'Frontier',
                    'Titan',
                    'NV200',
                    'NV Cargo',
                    'NV Passenger',
                    '370Z',
                    'GT-R',
                ]  
            ],
            [
                'name' => 'BMW',
                'models' => [
                    '2 Series',
                    '3 Series',
                    '4 Series',
                    '5 Series',
                    '7 Series',
                    '8 Series',
                    'Z4',
                    'X1',
                    'X2',
                    'X3',
                    'X4',
                    'X5',
                    'X6',
                    'X7',
                    'M2',
                    'M3',
                    'M4',
                    'M5',
                    'M8',
                    'i3',
                    'i8',
                ]
                
            ],
            [
                'name' => 'Mercedes-Benz',
                'models' => [
                    'A-Class',
                    'C-Class',
                    'E-Class',
                    'S-Class',
                    'CLA',
                    'CLS',
                    'S-Class',
                    'GLA',
                    'GLB',
                    'GLC',
                    'GLE',
                    'GLS',
                    'G-Class',
                    'SLC',
                    'SL-Class',
                    'AMG GT',
                    'Sprinter',
                    'Metris',
                    'EQC',
                    'EQS',
                    'EQV',
                ]
            ],
            [
                'name' => 'Audi',
                'models' => [
                    'A3',
                    'A4',
                    'A5',
                    'A6',
                    'A7',
                    'A8',
                    'Q3',
                    'Q5',
                    'Q7',
                    'Q8',
                    'TT',
                    'R8',
                    'e-tron',
                    'e-tron Sportback',
                ]
            ],
            [
                'name' => 'Hyundai',
                'models' => [
                    'Accent',
                    'Elantra',
                    'Sonata',
                    'Veloster',
                    'Ioniq',
                    'Kona',
                    'Venue',
                    'Santa Fe',
                    'Tucson',
                    'Nexo',
                    'Palisade',
                    'Genesis',
                ]
            ],
            [
                'name' => 'Kia',
                'models' => [
                    'Rio',
                    'Forte',
                    'Optima',
                    'Cadenza',
                    'Stinger',
                    'K5',
                    'K900',
                    'Soul',
                    'Seltos',
                    'Sportage',
                    'Sorento',
                    'Telluride',
                    'Niro',
                    'Sedona',
                    'Carnival',
                    'EV6',
                ]
            ],
            [
                'name' => 'Mazda',
                'models' => [
                    'Mazda3',
                    'Mazda6',
                    'MX-5 Miata',
                    'CX-3',
                    'CX-30',
                    'CX-5',
                    'CX-9',
                    'MX-30',
                ]
            ],
            [
                'name' => 'Lexus',
                'models' => [
                    'IS',
                    'ES',
                    'GS',
                    'LS',
                    'LC',
                    'UX',
                    'NX',
                    'RX',
                    'GX',
                    'LX',
                    'RC',
                    'RC F',
                    'LC',
                    'LFA',
                    'UX 300e',
                    'NX 300h',
                    'RX 450h',
                    'LC 500h',
                    'LS 500h',
                ]
            ],
            [
                'name' => 'Subaru',
                'models' => [
                    'Impreza',
                    'WRX',
                    'Legacy'
                ]
            ],
            [
                'name' => 'Jeep',
                'models' => [
                    'Renegade',
                    'Compass',
                    'Cherokee',
                    'Grand Cherokee',
                    'Wrangler',
                    'Gladiator',
                    'Grand Wagoneer',
                    'Wagoneer',
                ]
            ]
        ]);

        Brand::factory(count($brands))->sequence( fn($sequence) => [
            'name' => $brands[$sequence->index]['name']
        ])->create()->each(function($brand) use ($brands) {
            $models = $brands->where('name', $brand->name)->first()['models'];
            Model::factory(count($models))->sequence( fn($sequence) => [
                'name' => $models[$sequence->index],
                'brand_id' => $brand->id,
            ])->create();
        });
    }
}
