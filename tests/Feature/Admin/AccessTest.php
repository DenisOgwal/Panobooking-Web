<?php

namespace Tests\Feature\Admin;

use App\Admin\ResourceRegistry;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\ResourceIndex;
use App\Models\User;
use App\Support\CustomerAuth;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;

class AccessTest extends AdminTestCase
{
    public function test_guests_are_sent_to_the_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/r/properties')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()->assertSee('Admin sign in');
    }

    public function test_a_signed_in_customer_is_not_an_admin(): void
    {
        session([CustomerAuth::SESSION_KEY => ['Email' => 'demo@panobooking.test', 'FullNames' => 'Demo']]);

        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_users_without_a_role_are_refused(): void
    {
        $user = User::create(['name' => 'Nobody', 'email' => 'nobody@example.test', 'password' => 'password123']);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admins_sign_in_and_out(): void
    {
        Livewire::test(Login::class)
            ->set('email', 'admin@panobooking.test')->set('password', 'password123')
            ->call('login')
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin());

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_accounts_without_a_role_cannot_sign_in(): void
    {
        User::create(['name' => 'Nobody', 'email' => 'nobody@example.test', 'password' => 'password123']);

        Livewire::test(Login::class)
            ->set('email', 'nobody@example.test')->set('password', 'password123')
            ->call('login')
            ->assertHasErrors('email');
        $this->assertGuest();
    }

    public function test_repeated_wrong_passwords_are_throttled(): void
    {
        $component = Livewire::test(Login::class)->set('email', 'admin@panobooking.test');
        for ($i = 0; $i < 5; $i++) {
            $component->set('password', 'wrong-' . $i)->call('login');
        }

        $component->set('password', 'password123')->call('login')->assertHasErrors('email');
        $this->assertGuest();
        $this->assertStringContainsString('Too many attempts', $component->errors()->first('email'));
    }

    public function test_staff_cannot_open_settings_or_delete(): void
    {
        $this->asStaff()->get('/admin/r/currencies')->assertForbidden();
        $this->get('/admin/r/admin-users')->assertForbidden();
        $this->get('/admin/r/properties')->assertOk()->assertDontSee('Delete');

        $id = \App\Models\HotelsModel::value('IDs');
        Livewire::test(ResourceIndex::class, ['resource' => 'properties'])->call('delete', $id)->assertForbidden();
        $this->assertDatabaseHas('pickuplocations', ['IDs' => $id]);
    }

    public function test_unknown_resources_are_404(): void
    {
        $this->asAdmin()->get('/admin/r/nope')->assertNotFound();
    }

    public function test_the_dashboard_and_bookings_render(): void
    {
        $this->asStaff()->get('/admin')->assertOk()->assertSee('Waiting for approval')->assertSee('Recent bookings');
        $this->get('/admin/bookings')->assertOk()->assertSee('Nile Breeze Hotel');
    }

    public static function resources(): array
    {
        return array_map(fn ($slug) => [$slug], array_combine(array_keys(ResourceRegistry::all()), array_keys(ResourceRegistry::all())));
    }

    /** Catches column or field names that don't exist in a table. */
    #[DataProvider('resources')]
    public function test_every_resource_lists_creates_and_edits(string $slug): void
    {
        $class = ResourceRegistry::find($slug);
        $this->asAdmin()->get("/admin/r/{$slug}")->assertOk()->assertSee($class::label());

        if ($class::canCreate()) {
            $this->get("/admin/r/{$slug}/create")->assertOk();
        }
        if ($id = $class::query()->value($class::newModel()->getKeyName())) {
            $this->get("/admin/r/{$slug}/{$id}/edit")->assertOk();
        }
    }
}
