<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

//$user = User::class;
use App\User;
use App\Seller;
use App\Buyer;
use App\Product;
use App\Transaction;
use App\Category;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

// FACTORY USER
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => $verificado =  $faker->randomElement([User::VERIFIED, User::UNVERIFIED]),
        'verification_token' => $verificado == User::VERIFIED ? null : User::generateVerificationToken(),
        'admin' => $faker->randomElement([User::ADMIN, User::GUEST])
    ];
});

// FACTORY CATEGORY
$factory->define(Category::class, function (Faker\Generator $faker) {


    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1)

    ];
});

// FACTORY PRODUCT
$factory->define(Product::class, function (Faker\Generator $faker) {


    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([Product::PRODUCT_NO, Product::PRODUCT_YES]),
        'image' => $faker->randomElement(['legumes.jpg', 'tomatoes.jpg', 'vegetables.jpg']),
        // 'seller_id' => User::inRandomOrder()->first()->id,
        'seller_id' => User::all()->random()->id,

    ];
});

// FACTORY TRANSACTION
$factory->define(Transaction::class, function (Faker\Generator $faker) {

    $vendedor = Seller::has('products')->get()->random();

    $comprador = User::all()->except($vendedor->id)->random();
    return [

        'quantity' => $faker->numberBetween(1, 3),
        'product_id' => $vendedor->products->random()->id,
        'buyer_id' => $comprador->id,
    ];
});
