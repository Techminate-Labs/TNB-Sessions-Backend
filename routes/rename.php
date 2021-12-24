//cartItem
    Route::get('/cartItemList', [CartItemController::class, 'cartItemList']);
    Route::get('/cartItemGetById/{id}', [CartItemController::class, 'cartItemGetById']);
    Route::post('/cartItemCreate', [CartItemController::class, 'cartItemCreate']);
    Route::put('/cartItemUpdate/{id}', [CartItemController::class, 'cartItemUpdate']);
    Route::delete('/cartItemDelete/{id}', [CartItemController::class, 'cartItemDelete']);


$categories = $request->categories;

$products = Product::when($categories, function (Builder $query, $categories) {
    return $query->whereHas('categories', function (Builder $query) use ($categories) {
        $query->whereIn('id', $categories);
    });
})->get();