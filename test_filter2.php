<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = new \Illuminate\Http\Request();
// Simulate what Postman might send for ?categories=1,2
$request->merge(['categories' => '1,2', 'brands' => '1, 3', 'price' => '100, 500']);

$query = \App\Models\Product::with(['category', 'brandData'])->where('status', 'active');

$catParam = $request->input('category_id', $request->input('categories'));
if (!empty($catParam)) {
    if (is_string($catParam) && str_starts_with($catParam, '[') && str_ends_with($catParam, ']')) {
        $catParam = json_decode($catParam, true);
    }
    $categoryIds = is_array($catParam) ? $catParam : array_map('trim', explode(',', $catParam));
    $query->whereIn('category_id', $categoryIds);
}

$brandParam = $request->input('brand_id', $request->input('brands'));
if (!empty($brandParam)) {
    if (is_string($brandParam) && str_starts_with($brandParam, '[') && str_ends_with($brandParam, ']')) {
        $brandParam = json_decode($brandParam, true);
    }
    $brandIds = is_array($brandParam) ? $brandParam : array_map('trim', explode(',', $brandParam));
    $query->whereIn('brand_id', $brandIds);
}

$minPrice = $request->input('min_price');
$maxPrice = $request->input('max_price');

if ($request->filled('price') && str_contains($request->price, ',')) {
    $prices = array_map('trim', explode(',', $request->price));
    $minPrice = $prices[0] ?? $minPrice;
    $maxPrice = $prices[1] ?? $maxPrice;
}

if (!empty($minPrice)) {
    $query->where('price', '>=', (float) $minPrice);
}
if (!empty($maxPrice)) {
    $query->where('price', '<=', (float) $maxPrice);
}

echo "SQL: " . $query->toSql() . "\n";
echo "Bindings: " . json_encode($query->getBindings()) . "\n";
echo "Count: " . $query->count() . "\n";
