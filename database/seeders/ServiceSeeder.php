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
                'description' => 'Serviço completo de limpeza interna focado na remoção de sujeiras, odores, poeira, manchas leves e bactérias presentes no veículo. Inclui aspiração detalhada, limpeza de bancos, painéis, portas, carpetes e cantos difíceis. Deixa o interior mais limpo, cheiroso e agradável, trazendo sensação de carro novo e maior conforto no dia a dia.',
                'price' => 'R$ 80',
                'icon' => 'bi bi-stars',
                'bg' => '#ede9fe',
            ],
            [
                'name' => 'Lavagem Externa',
                'description' => 'Limpeza rápida e eficiente da parte externa do veículo, removendo poeira, barro, manchas e sujeiras acumuladas no dia a dia. Inclui lavagem da lataria, rodas, pneus e vidros externos, deixando o carro com brilho renovado e visual muito mais bonito.',
                'price' => 'R$ 40',
                'icon' => 'bi bi-droplet',
                'bg' => '#dbeafe',
            ],
            [
                'name' => 'Lavagem Completa',
                'description' => 'Lavagem detalhada da parte externa e interna do veículo, utilizando produtos automotivos específicos para preservar a pintura e os acabamentos. Inclui limpeza da lataria, rodas, pneus, vidros e aspiração interna. Um serviço ideal pra manter o carro sempre bonito, conservado e com aparência impecável.',
                'price' => 'R$ 110',
                'icon' => 'bi bi-bucket',
                'bg' => '#dcfce7',
            ],
            [
                'name' => 'Proteção de Pintura',
                'description' => 'Aplicação de produtos protetores de alta qualidade que criam uma camada protetora sobre a pintura do veículo. Ajuda a preservar o brilho, reduz danos causados pelo sol, chuva, poluição e sujeiras do dia a dia. Além de aumentar a durabilidade da pintura, facilita futuras lavagens e mantém o carro com aspecto de recém-polido por mais tempo.',
                'price' => 'R$ 150',
                'icon' => 'bi bi-shield-lock',
                'bg' => '#fef3c7',
            ],
            [
                'name' => 'Polimento Técnico',
                'description' => 'Processo especializado para revitalização da pintura automotiva, removendo riscos superficiais, marcas de lavagem, hologramas e queimaduras leves do verniz. O polimento devolve brilho intenso e profundidade à pintura, deixando o carro com aparência muito mais nova e sofisticada. Ideal pra quem quer recuperar o visual premium do veículo.',
                'price' => 'R$ 200',
                'icon' => 'bi bi-gem',
                'bg' => '#fbcfe8',
            ],
            [
                'name' => 'Cristalização dos Vidros',
                'description' => 'A cristalização dos vidros cria uma camada protetora hidrofóbica que repele água, poeira e sujeiras, melhorando drasticamente a visibilidade em dias de chuva. Além de deixar os vidros com aparência mais limpa e brilhante, ajuda a evitar manchas causadas pelo tempo e reduz o acúmulo de resíduos. Ideal pra quem busca mais segurança, conforto ao dirigir e um acabamento premium no veículo.',
                'price' => 'R$ 120',
                'icon' => 'bi bi-eye',
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
