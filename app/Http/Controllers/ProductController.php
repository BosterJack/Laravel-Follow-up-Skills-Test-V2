<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $jsonPath;
    private $xmlPath;

    public function __construct()
    {
        // Define file paths
        $this->jsonPath = storage_path('app/products.json');
        $this->xmlPath = storage_path('app/products.xml');
        
        // Create files if they don't exist
        if (!file_exists($this->jsonPath)) {
            file_put_contents($this->jsonPath, json_encode([]));
        }
        if (!file_exists($this->xmlPath)) {
            $xml = new \SimpleXMLElement('<?xml version="1.0"?><products></products>');
            $xml->asXML($this->xmlPath);
        }
    }

    private function readProducts()
    {
        return json_decode(file_get_contents($this->jsonPath), true) ?? [];
    }

    private function saveProducts($products)
    {
        // Save as JSON
        file_put_contents($this->jsonPath, json_encode($products, JSON_PRETTY_PRINT));

        // Save as XML
        $xml = new \SimpleXMLElement('<?xml version="1.0"?><products></products>');
        foreach ($products as $item) {
            $product = $xml->addChild('product');
            foreach ($item as $key => $value) {
                $product->addChild($key, htmlspecialchars((string)$value));
            }
        }
        $xml->asXML($this->xmlPath);
    }

    public function index()
    {
        $products = $this->readProducts();
        $total = array_sum(array_column($products, 'total_value'));
        return view('products.index', compact('products', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0'
        ]);

        $products = $this->readProducts();

        $newProduct = [
            'id' => count($products) + 1,
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'total_value' => $validated['quantity'] * $validated['price'],
            'submitted_at' => date('Y-m-d H:i:s')
        ];

        array_unshift($products, $newProduct); // Add to beginning for reverse chronological order
        $this->saveProducts($products);

        return response()->json($newProduct);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0'
        ]);

        $products = $this->readProducts();
        
        $index = array_search($id, array_column($products, 'id'));
        
        if ($index !== false) {
            $products[$index] = [
                'id' => $id,
                'name' => $validated['name'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'total_value' => $validated['quantity'] * $validated['price'],
                'submitted_at' => $products[$index]['submitted_at']
            ];
            
            $this->saveProducts($products);
            return response()->json($products[$index]);
        }

        return response()->json(['error' => 'Product not found'], 404);
    }
}
