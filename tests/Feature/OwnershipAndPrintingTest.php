<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PrintHouse;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Covers two storefront rules:
 *
 * 1. a digital book can only ever be bought once per account, and the API has to
 *    refuse it both when the book enters the cart and when the cart is paid for;
 * 2. readers can order a physical copy of a book and printing houses can apply
 *    for a cooperation contract.
 */
class OwnershipAndPrintingTest extends TestCase
{
    use RefreshDatabase;

    private function reader(): User
    {
        return User::factory()->create();
    }

    private function book(string $slug, int $price = 500000): Product
    {
        $category = Category::query()->firstOrCreate(['slug' => 'books'], ['name' => 'کتاب‌ها']);

        return Product::query()->create([
            'title' => 'کتاب '.$slug,
            'slug' => $slug,
            'category_id' => $category->id,
            'status' => 'active',
            'availability' => true,
            'original_price' => $price,
            'price' => $price,
        ]);
    }

    private function purchase(User $user, Product $product): void
    {
        $order = Order::query()->create([
            'user_id' => $user->id,
            'status' => 'paid',
            'total_amount' => (int) $product->final_price,
            'paid_at' => now(),
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_title' => $product->title,
            'unit_price' => (int) $product->final_price,
            'quantity' => 1,
            'subtotal' => (int) $product->final_price,
        ]);
    }

    private function house(array $attributes = []): PrintHouse
    {
        return PrintHouse::query()->create(array_merge([
            'name' => 'چاپخانه‌ی نقش جهان',
            'slug' => 'naqsh-e-jahan',
            'city' => 'تهران',
            'province' => 'تهران',
            'capabilities' => ['شومیز', 'گالینگور'],
            'unit_price' => 245000,
            'hardcover_surcharge' => 40000,
            'delivery_fee' => 65000,
            'delivery_days' => 5,
            'min_quantity' => 1,
            'max_quantity' => 20,
            'rating' => 4.8,
            'status' => 'active',
        ], $attributes));
    }

    public function test_a_purchased_book_cannot_be_added_to_the_cart_again(): void
    {
        $user = $this->reader();
        $book = $this->book('owned-book');
        $this->purchase($user, $book);

        Sanctum::actingAs($user);

        $this->postJson('/api/v1/cart/items', ['product_id' => $book->id])
            ->assertStatus(422)
            ->assertJsonPath('reason', 'already_owned')
            ->assertJsonPath('product_id', $book->id);

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_a_book_already_in_the_cart_is_not_added_twice(): void
    {
        $user = $this->reader();
        $book = $this->book('fresh-book');

        Sanctum::actingAs($user);

        $this->postJson('/api/v1/cart/items', ['product_id' => $book->id])->assertStatus(201);

        $this->postJson('/api/v1/cart/items', ['product_id' => $book->id])
            ->assertStatus(422)
            ->assertJsonPath('reason', 'already_in_cart');

        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_a_cart_line_is_always_a_single_copy(): void
    {
        $user = $this->reader();
        $book = $this->book('single-copy');

        Sanctum::actingAs($user);

        $itemId = $this->postJson('/api/v1/cart/items', ['product_id' => $book->id])
            ->assertStatus(201)
            ->json('data.items.0.id');

        $this->putJson('/api/v1/cart/items/'.$itemId, ['quantity' => 5])
            ->assertOk()
            ->assertJsonPath('data.items.0.quantity', 1);
    }

    public function test_checkout_drops_books_the_reader_already_owns(): void
    {
        $user = $this->reader();
        $owned = $this->book('already-owned');
        $wanted = $this->book('still-wanted', 300000);
        $this->purchase($user, $owned);

        Sanctum::actingAs($user);

        $this->postJson('/api/v1/cart/items', ['product_id' => $wanted->id])->assertStatus(201);

        // Simulate a line that reached the cart before the book was bought elsewhere.
        $cart = Cart::query()->where('user_id', $user->id)->firstOrFail();
        $cart->items()->create([
            'product_id' => $owned->id,
            'quantity' => 1,
            'unit_price' => (int) $owned->final_price,
            'subtotal' => (int) $owned->final_price,
        ]);

        $response = $this->postJson('/api/v1/checkout', ['payment_method' => 'wallet'])
            ->assertStatus(422)
            ->assertJsonPath('reason', 'already_owned');

        $this->assertContains($owned->id, array_map('intval', $response->json('owned_product_ids')));

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $owned->id,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $wanted->id,
        ]);
    }

    public function test_the_library_endpoint_lists_owned_books(): void
    {
        $user = $this->reader();
        $book = $this->book('library-book');
        $this->purchase($user, $book);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/account/library')->assertOk();

        $this->assertContains($book->id, array_map('intval', $response->json('data.product_ids')));
        $response->assertJsonPath('data.stats.owned_books', 1)
            ->assertJsonPath('data.products.0.slug', 'library-book');
    }

    public function test_the_print_directory_lists_partner_houses_with_their_options(): void
    {
        $this->house();
        $this->house(['city' => 'اصفهان', 'slug' => 'aftab', 'name' => 'چاپخانه‌ی آفتاب']);

        $response = $this->getJson('/api/v1/print-houses')->assertOk();

        $this->assertCount(2, $response->json('data.print_houses'));
        $response->assertJsonPath('data.stats.print_houses', 2)
            ->assertJsonPath('data.options.paper_types.0.value', 'standard')
            ->assertJsonPath('data.options.bindings.0.value', 'paperback');

        $this->assertSame(0.15, (float) $response->json('data.options.digital_owner_discount'));

        $this->getJson('/api/v1/print-houses?'.http_build_query(['city' => 'اصفهان']))
            ->assertOk()
            ->assertJsonPath('data.stats.print_houses', 1);
    }

    public function test_a_printing_house_can_request_a_cooperation_contract(): void
    {
        $this->postJson('/api/v1/print-houses/contract-requests', [
            'company_name' => 'چاپ و نشر البرز',
            'contact_name' => 'سارا محمدی',
            'phone' => '02122334455',
            'email' => 'info@alborz-print.ir',
            'city' => 'کرج',
            'monthly_capacity' => 12000,
            'capabilities' => ['شومیز', 'فنر'],
            'message' => 'ظرفیت چاپ رنگی و صحافی سخت داریم.',
        ])
            ->assertStatus(201)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('print_contract_requests', [
            'company_name' => 'چاپ و نشر البرز',
            'status' => 'pending',
        ]);
    }

    public function test_a_contract_request_needs_the_printing_house_details(): void
    {
        $this->postJson('/api/v1/print-houses/contract-requests', ['company_name' => 'ناقص'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['contact_name', 'phone', 'city']);
    }

    public function test_the_print_quote_charges_the_digital_owner_discount(): void
    {
        $house = $this->house();
        $book = $this->book('printed-book');
        $options = [
            'print_house_id' => $house->id,
            'quantity' => 1,
            'paper_type' => 'premium',
            'binding' => 'hardcover',
            'color_print' => true,
        ];

        // 245,000 base + 60,000 premium paper + 40,000 hardcover + 45,000 colour.
        $guest = $this->reader();
        Sanctum::actingAs($guest);

        $this->postJson('/api/v1/print-orders/quote', $options)
            ->assertOk()
            ->assertJsonPath('data.unit_price', 390000)
            ->assertJsonPath('data.discount_amount', 0)
            ->assertJsonPath('data.digital_owner', false)
            ->assertJsonPath('data.total_price', 455000);

        $owner = $this->reader();
        $this->purchase($owner, $book);
        Sanctum::actingAs($owner);

        // 15% of 390,000 is 58,500, plus the 65,000 delivery fee.
        $this->postJson('/api/v1/print-orders/quote', array_merge($options, ['product_id' => $book->id]))
            ->assertOk()
            ->assertJsonPath('data.discount_amount', 58500)
            ->assertJsonPath('data.digital_owner', true)
            ->assertJsonPath('data.total_price', 396500);
    }

    public function test_a_reader_can_order_a_printed_copy_of_a_book(): void
    {
        $house = $this->house();
        $book = $this->book('order-printed');
        $user = $this->reader();
        $this->purchase($user, $book);

        Sanctum::actingAs($user);

        $this->postJson('/api/v1/print-orders', [
            'print_house_id' => $house->id,
            'product_id' => $book->id,
            'quantity' => 2,
            'paper_type' => 'cream',
            'binding' => 'paperback',
            'color_print' => false,
            'shipping_name' => 'آرمان رضایی',
            'shipping_phone' => '09121112233',
            'shipping_city' => 'تهران',
            'shipping_address' => 'خیابان آزادی، پلاک ۱۲',
            'postal_code' => '1345678901',
        ])
            ->assertStatus(201)
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.quantity', 2)
            ->assertJsonPath('data.paper_label', 'کاغذ کرافت/کرم')
            // 2 × (245,000 + 35,000) = 560,000 plus the 65,000 delivery fee.
            ->assertJsonPath('data.total_price', 625000);

        $this->assertDatabaseHas('print_orders', [
            'user_id' => $user->id,
            'product_id' => $book->id,
            'status' => 'pending',
        ]);

        $this->getJson('/api/v1/print-orders')
            ->assertOk()
            ->assertJsonPath('data.0.product_id', $book->id)
            ->assertJsonPath('meta.total', 1);
    }

    public function test_print_orders_are_only_visible_to_their_owner(): void
    {
        $house = $this->house();
        $book = $this->book('private-print-order');
        $other = $this->reader();
        $this->purchase($other, $book);

        Sanctum::actingAs($other);
        $this->postJson('/api/v1/print-orders', [
            'print_house_id' => $house->id,
            'product_id' => $book->id,
            'quantity' => 1,
            'paper_type' => 'standard',
            'binding' => 'paperback',
            'shipping_name' => 'کاربر دیگر',
            'shipping_phone' => '09120000000',
            'shipping_city' => 'مشهد',
            'shipping_address' => 'خیابان امام رضا',
        ])->assertStatus(201);

        Sanctum::actingAs($this->reader());

        $this->getJson('/api/v1/print-orders')
            ->assertOk()
            ->assertJsonPath('meta.total', 0);
    }

    public function test_only_the_admin_can_manage_print_houses(): void
    {
        $this->house();

        Sanctum::actingAs($this->reader());
        $this->getJson('/api/v1/admin/print-houses')->assertStatus(403);

        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));
        $this->getJson('/api/v1/admin/print-houses')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'naqsh-e-jahan');
    }
}
