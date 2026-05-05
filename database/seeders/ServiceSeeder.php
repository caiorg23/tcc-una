<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'name' => 'Higienização Interna',
                'description' => 'Limpeza profunda do interior do veículo',
                'price' => 'R$ 80',
                'icon' => '✨',
                'bg' => '#ede9fe',
            ],
            [
                'name' => 'Lavagem Externa',
                'description' => 'Lavagem completa da parte externa',
                'price' => 'R$ 40',
                'icon' => '💧',
                'bg' => '#dbeafe',
            ],
            [
                'name' => 'Lavagem Completa',
                'description' => 'Higienização interna e lavagem externa',
                'price' => 'R$ 110',
                'icon' => '🌀',
                'bg' => '#dcfce7',
            ],
            [
                'name' => 'Proteção de Pintura',
                'description' => 'Proteção e conservação da pintura',
                'price' => 'R$ 150',
                'icon' => '🛡️',
                'bg' => '#fef3c7',
            ],
            [
                'name' => 'Polimento Técnico',
                'description' => 'Polimento profissional da pintura',
                'price' => 'R$ 200',
                'icon' => '💎',
                'bg' => '#fbcfe8',
            ],
            [
                'name' => 'Cristalização dos Vidros',
                'description' => 'Tratamento especial para os vidros',
                'price' => 'R$ 120',
                'icon' => '👁️',
                'bg' => '#dbeafe',
            ],
        ];

        Service::whereIn('name', [
            'Consultoria de Carreira',
            'Mentoria de Negócios',
            'Planejamento Financeiro',
        ])->delete();

        foreach ($services as $service) {
            Service::updateOrCreate([
                'name' => $service['name'],
            ], $service);
        }
    }
}
