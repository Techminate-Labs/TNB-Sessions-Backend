//session
    Route::get('/sessionList', [SessionController::class, 'sessionList']);
    Route::get('/sessionGetById/{id}', [SessionController::class, 'sessionGetById']);
    Route::post('/sessionCreate', [SessionController::class, 'sessionCreate']);
    Route::put('/sessionUpdate/{id}', [SessionController::class, 'sessionUpdate']);
    Route::delete('/sessionDelete/{id}', [SessionController::class, 'sessionDelete']);


$categories = $request->categories;

$products = Product::when($categories, function (Builder $query, $categories) {
    return $query->whereHas('categories', function (Builder $query) use ($categories) {
        $query->whereIn('id', $categories);
    });
})->get();