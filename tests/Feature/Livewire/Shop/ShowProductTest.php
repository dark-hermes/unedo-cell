<?php

namespace Tests\Feature\Livewire\Shop;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\ProductCategory;
use App\Livewire\Shop\ShowProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class ShowProductTest extends TestCase
{
    use RefreshDatabase;

    protected $product;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'user']);

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com', // Menggunakan email test yang konsisten
            'password' => bcrypt('password'),
            'is_active' => true,
            'phone' => '081234567890', // Nomor tetap untuk testing
        ])->assignRole('user');

        $category = ProductCategory::create([
            'name' => 'Headset',
            'code' => 'HS',
            'image' => 'product_categories/headset.png',
        ]);

        $this->product = Product::create([
            'sku' => 'HS-VD8572',
            'name' => 'Headset Original Vivo',
            'category_id' => $category->id,
            'sale_price' => 25000,
            'buy_price' => 11500,
            'min_stock' => 1,
            'description' => 'Deskripsi produk untuk testing',
            'show' => true,
            'image' => 'products/orivivo.jpg',
        ]);

        $this->product->stockEntries()->create([
            'quantity' => 100,
            'source' => 'purchase',
            'user_id' => $this->user->id,
            'received_at' => now(),
        ]);
    }

    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ShowProduct::class, ['slug' => $this->product->slug])
            ->assertStatus(200)
            ->assertSee($this->product->name)
            ->assertSee('Headset Original Vivo') // Nama produk spesifik
            ->assertSee('Deskripsi produk untuk testing') // Deskripsi spesifik
            ->assertSee('Rp 25.000'); // Format harga
    }

    /** @test */
    public function initial_quantity_is_one()
    {
        Livewire::test(ShowProduct::class, ['slug' => $this->product->slug])
            ->assertSet('quantity', 1);
    }

    /** @test */
    public function can_increment_quantity()
    {
        Livewire::test(ShowProduct::class, ['slug' => $this->product->slug])
            ->call('increment')
            ->assertSet('quantity', 2);
    }

    /** @test */
    public function cannot_increment_beyond_stock()
    {
        // Buat produk dengan stok terbatas
        $limitedProduct = Product::create([
            'sku' => 'HS-LIMITED',
            'name' => 'Headset Limited Stock',
            'category_id' => $this->product->category_id,
            'sale_price' => 30000,
            'buy_price' => 15000,
            'min_stock' => 1,
            'description' => 'Produk stok terbatas',
            'show' => true,
            'image' => 'products/limited.jpg',
        ]);

        $limitedProduct->stockEntries()->create([
            'quantity' => 1,
            'source' => 'purchase',
            'user_id' => $this->user->id,
            'received_at' => now(),
        ]);

        Livewire::test(ShowProduct::class, ['slug' => $limitedProduct->slug])
            ->call('increment') // Sudah 1, coba tambah ke 2
            ->assertSet('quantity', 1)
            ->assertDispatched('show-toast');
    }

    /** @test */
    public function can_decrement_quantity()
    {
        Livewire::test(ShowProduct::class, ['slug' => $this->product->slug])
            ->set('quantity', 2)
            ->call('decrement')
            ->assertSet('quantity', 1);
    }

    /** @test */
    public function cannot_decrement_below_one()
    {
        Livewire::test(ShowProduct::class, ['slug' => $this->product->slug])
            ->call('decrement')
            ->assertSet('quantity', 1)
            ->assertDispatched('show-toast');
    }

    /** @test */
    public function can_add_product_to_cart()
    {
        $this->actingAs($this->user);

        Livewire::test(ShowProduct::class, ['slug' => $this->product->slug])
            ->set('quantity', 2)
            ->call('addToCart')
            ->assertDispatched('swal');

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);
    }

    /** @test */
    public function updates_quantity_if_product_already_in_cart()
    {
        $this->actingAs($this->user);

        // Tambahkan produk ke keranjang pertama kali
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        Livewire::test(ShowProduct::class, ['slug' => $this->product->slug])
            ->set('quantity', 3)
            ->call('addToCart')
            ->assertDispatched('swal');

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 4
        ]);
    }

    /** @test */
    public function shows_correct_stock_information()
    {
        Livewire::test(ShowProduct::class, ['slug' => $this->product->slug])
            ->assertSee('Stok: 100');
    }
}