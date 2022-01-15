//review
Route::get('/reviewList', [ReviewController::class, 'list']);
    Route::get('/reviewGetById/{id}', [ReviewController::class, 'getById']);
    Route::post('/reviewCreate', [ReviewController::class, 'create']);
    Route::put('/reviewUpdate/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviewDelete/{id}', [ReviewController::class, 'delete']);

$categories = $request->categories;

$products = Product::when($categories, function (Builder $query, $categories) {
    return $query->whereHas('categories', function (Builder $query) use ($categories) {
        $query->whereIn('id', $categories);
    });
})->get();