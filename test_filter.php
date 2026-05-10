<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = new \Illuminate\Http\Request();
$request->merge(['category_id' => '[1,2]', 'min_price' => '100']);

$query = \App\Models\Product::with(['category', 'brandData'])->where('status', 'active');

if ($request->filled('category_id')) {
    $category_id = $request->category_id;
    if (is_string($category_id) && str_starts_with($category_id, '[') && str_ends_with($category_id, ']')) {
        $category_id = json_decode($category_id, true);
    }
    $categoryIds = is_array($category_id) ? $category_id : explode(',', $category_id);
    $query->whereIn('category_id', $categoryIds);
} else {
    echo "category_id not filled\n";
}

if ($request->filled('min_price')) {
    $query->where('price', '>=', $request->min_price);
}

echo "SQL: " . $query->toSql() . "\n";
echo "Bindings: " . json_encode($query->getBindings()) . "\n";
echo "Count: " . $query->count() . "\n";
