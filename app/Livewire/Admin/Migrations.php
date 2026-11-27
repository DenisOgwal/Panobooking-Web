<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\AdminComponent;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

/**
 * /admin/system/migrations (developers only): see which migrations have run and run the pending
 * ones. Every migration checks what already exists before changing anything, so running them
 * again, or against a database that already has the tables, is safe.
 */
class Migrations extends Component
{
    use AdminComponent;

    /** Output of the last run or preview, shown on the page. */
    public string $output = '';

    public string $outputTitle = '';

    public bool $outputOk = true;

    public function mount(): void
    {
        Gate::authorize('admin.develop');
    }

    /** Run the pending migrations (`php artisan migrate --force`). */
    public function run(): void
    {
        $this->artisan(['--force' => true], 'Migration run', function () {
            Cache::forget('home.content');
        });
    }

    /** Show the SQL the pending migrations would execute, without running it. */
    public function preview(): void
    {
        $this->artisan(['--force' => true, '--pretend' => true], 'Preview (nothing was changed)');
    }

    private function artisan(array $options, string $title, ?callable $after = null): void
    {
        Gate::authorize('admin.develop');

        $lock = Cache::lock('admin:migrations', 600);
        if (! $lock->get()) {
            $this->toast('Migrations are already running. Try again in a moment.', 'warning');

            return;
        }

        try {
            $code = Artisan::call('migrate', $options);
            $this->output = trim(Artisan::output()) ?: 'Nothing to migrate.';
            $this->outputOk = $code === 0;
            if ($after && $code === 0) {
                $after();
            }
        } catch (Throwable $e) {
            report($e);
            $this->output = $e->getMessage();
            $this->outputOk = false;
        } finally {
            $lock->release();
        }

        $this->outputTitle = $title;
        if (empty($options['--pretend'])) {
            Log::info('Migrations run from the admin backend', ['by' => Auth::user()?->email, 'ok' => $this->outputOk]);
        }
        $this->toast($this->outputOk ? "{$title} finished." : "{$title} failed: see the output below.", $this->outputOk ? 'success' : 'error');
    }

    /** @return list<array{name: string, ran: bool, batch: ?int}> */
    private function migrations(): array
    {
        $migrator = app('migrator');
        $files = $migrator->getMigrationFiles([database_path('migrations'), ...$migrator->paths()]);

        $ran = [];
        if ($migrator->repositoryExists()) {
            $ran = $migrator->getRepository()->getMigrationBatches();
        }

        $rows = [];
        foreach (array_keys($files) as $name) {
            $rows[] = ['name' => $name, 'ran' => array_key_exists($name, $ran), 'batch' => $ran[$name] ?? null];
        }

        return $rows;
    }

    public function render()
    {
        $rows = $this->migrations();
        $connection = DB::connection();

        return view('livewire.admin.migrations', [
            'rows' => array_reverse($rows),
            'pending' => count(array_filter($rows, fn ($r) => ! $r['ran'])),
            'total' => count($rows),
            'database' => $connection->getDatabaseName(),
            'driver' => $connection->getDriverName(),
        ])->layout('components.layouts.admin', $this->adminLayout('Database migrations'));
    }
}
