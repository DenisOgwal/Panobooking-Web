<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Migrations;
use App\Livewire\Admin\ResourceForm;
use App\Livewire\Admin\ResourceIndex;
use App\Models\User;
use Database\Seeders\AdminAccountsSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

class DeveloperTest extends AdminTestCase
{
    private function developer(): User
    {
        return User::where('email', 'developer@panobooking.test')->firstOrFail();
    }

    public function test_only_developers_see_and_use_the_migrations_page(): void
    {
        $this->asAdmin()->get('/admin/system/migrations')->assertForbidden();
        $this->get('/admin')->assertDontSee('Database migrations');
        $this->asStaff()->get('/admin/system/migrations')->assertForbidden();

        $this->actingAs($this->developer())->get('/admin')->assertSee('Database migrations');
        $this->get('/admin/system/migrations')->assertOk()
            ->assertSee('2022_02_01_000001_create_accessibility_table')->assertSee('0 pending', false);
    }

    public function test_running_migrations_from_the_admin_is_idempotent(): void
    {
        $this->actingAs($this->developer());

        Livewire::test(Migrations::class)->call('run')->assertSet('outputOk', true)->assertSee('Nothing to migrate');
        Livewire::test(Migrations::class)->call('preview')->assertSet('outputOk', true);

        // Forget that everything ran (as on a database that already has the tables) and run again:
        // every migration must skip what exists instead of failing.
        \DB::table('migrations')->delete();
        Livewire::test(Migrations::class)->call('run')->assertSet('outputOk', true);
        $this->assertSame(count(glob(database_path('migrations/*.php'))), \DB::table('migrations')->count());

        $this->actingAs($this->admin());
        Livewire::test(Migrations::class)->assertForbidden();
    }

    public function test_the_deployment_seeder_creates_both_accounts_once_and_keeps_changed_passwords(): void
    {
        $seed = fn () => Artisan::call('db:seed', ['--class' => AdminAccountsSeeder::class, '--force' => true]);
        config(['panobooking.accounts' => [
            'admin' => ['name' => 'Ops', 'email' => 'ops@example.test', 'password' => 'admin-secret-1'],
            'developer' => ['name' => 'Dev', 'email' => 'dev@example.test', 'password' => 'dev-secret-12'],
        ]]);

        $seed();
        $this->assertSame('admin', User::where('email', 'ops@example.test')->value('role'));
        $this->assertSame('developer', User::where('email', 'dev@example.test')->value('role'));
        $this->assertTrue(Hash::check('dev-secret-12', User::where('email', 'dev@example.test')->value('password')));

        // A password changed after deployment survives the next deployment.
        User::where('email', 'dev@example.test')->first()->forceFill(['password' => 'changed-later'])->save();
        $seed();
        $this->assertTrue(Hash::check('changed-later', User::where('email', 'dev@example.test')->value('password')));
        $this->assertSame(1, User::where('email', 'dev@example.test')->count());

        // Missing values: nothing is created with a default password.
        config(['panobooking.accounts.admin' => ['name' => 'X', 'email' => 'nobody@example.test', 'password' => '']]);
        $seed();
        $this->assertDatabaseMissing('users', ['email' => 'nobody@example.test']);
    }

    public function test_admins_cannot_create_promote_or_delete_developers(): void
    {
        $this->asAdmin();

        Livewire::test(ResourceForm::class, ['resource' => 'admin-users'])
            ->set('data.name', 'Sneaky')->set('data.email', 'sneaky@example.test')
            ->set('data.role', 'developer')->set('data.password', 'long-password')
            ->call('save')->assertHasErrors('data.role');
        $this->assertDatabaseMissing('users', ['email' => 'sneaky@example.test']);

        Livewire::test(ResourceIndex::class, ['resource' => 'admin-users'])->call('delete', $this->developer()->id);
        $this->assertDatabaseHas('users', ['id' => $this->developer()->id]);

        // Developers can.
        $this->actingAs($this->developer());
        Livewire::test(ResourceForm::class, ['resource' => 'admin-users'])
            ->set('data.name', 'Second Dev')->set('data.email', 'dev2@example.test')
            ->set('data.role', 'developer')->set('data.password', 'long-password')
            ->call('save')->assertHasNoErrors();
        $this->assertSame('developer', User::where('email', 'dev2@example.test')->value('role'));
    }
}
