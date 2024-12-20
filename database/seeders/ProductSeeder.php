<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private $jsonPath;
    private $xmlPath;

    public function __construct()
    {
        $this->jsonPath = storage_path('app/products.json');
        $this->xmlPath = storage_path('app/products.xml');
    }

    public function run(): void
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Ordinateur Portable',
                'quantity' => 15,
                'price' => 899.99,
                'total_value' => 13499.85,
                'submitted_at' => '2024-12-19 10:00:00'
            ],
            [
                'id' => 2,
                'name' => 'Souris Sans Fil',
                'quantity' => 50,
                'price' => 29.99,
                'total_value' => 1499.50,
                'submitted_at' => '2024-12-19 10:15:00'
            ],
            [
                'id' => 3,
                'name' => 'Clavier Mécanique',
                'quantity' => 30,
                'price' => 89.99,
                'total_value' => 2699.70,
                'submitted_at' => '2024-12-19 10:30:00'
            ],
            [
                'id' => 4,
                'name' => 'Écran 27"',
                'quantity' => 20,
                'price' => 299.99,
                'total_value' => 5999.80,
                'submitted_at' => '2024-12-19 11:00:00'
            ],
            [
                'id' => 5,
                'name' => 'Casque Audio',
                'quantity' => 40,
                'price' => 79.99,
                'total_value' => 3199.60,
                'submitted_at' => '2024-12-19 11:30:00'
            ]
        ];

        // Save as JSON
        if (!file_exists(dirname($this->jsonPath))) {
            mkdir(dirname($this->jsonPath), 0755, true);
        }
        file_put_contents($this->jsonPath, json_encode($products, JSON_PRETTY_PRINT));

        // Save as XML
        $xml = new \SimpleXMLElement('<?xml version="1.0"?><products></products>');
        foreach ($products as $item) {
            $product = $xml->addChild('product');
            foreach ($item as $key => $value) {
                $product->addChild($key, htmlspecialchars((string)$value));
            }
        }
        
        if (!file_exists(dirname($this->xmlPath))) {
            mkdir(dirname($this->xmlPath), 0755, true);
        }
        $xml->asXML($this->xmlPath);

        $this->command->info('Product test data successfully created!');
    }
}