<?php

// use Illuminate\Foundation\Testing\RefreshDatabase;
// uses(RefreshDatabase::class);

use App\Models\Product;

beforeEach(function () {
    $this->userAuthenticated();
});

it('gives back a successful response for the page', function () {
    // $this->withoutExceptionHandling();
    $this->get(route('products.index'))->assertOk();
});


test('it fails if required fields are missing', function () {
    $response = $this->post(route('products.store'), []);

    $response->assertSessionHasErrors([
        'name',
        'purchase_price',
        'sell_price',
        'opening_stock',
        'current_stock',
    ]);
});


// ✅ Sell price must be >= purchase price
test('it fails if sell price is less than purchase price', function () {
    $response = $this->post(route('products.store'), [
        'name' => 'Test Product',
        'purchase_price' => 100,
        'sell_price' => 50, // invalid
        'opening_stock' => 20,
        'current_stock' => 10,
    ]);

    $response->assertSessionHasErrors(['sell_price']);
});

// Current stock must be <= opening stock
test('it fails if current stock is greater than opening stock', function () {
    $response = $this->post(route('products.store'), [
        'name' => 'Test Product',
        'purchase_price' => 100,
        'sell_price' => 150,
        'opening_stock' => 10,
        'current_stock' => 20, // invalid
    ]);

    $response->assertSessionHasErrors(['current_stock']);
});

// Successful store
test('it passes if valid data is provided', function () {
    $response = $this->post(route('products.store'), [
        'name' => 'Valid Product',
        'purchase_price' => 100,
        'sell_price' => 150,
        'opening_stock' => 20,
        'current_stock' => 20,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('products.index'));
    $this->assertDatabaseHas('products', ['name' => 'Valid Product']);
});
