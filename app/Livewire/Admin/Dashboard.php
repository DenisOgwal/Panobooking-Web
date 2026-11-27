<?php

namespace App\Livewire\Admin;

use App\Admin\Flags;
use App\Admin\ResourceRegistry;
use App\Livewire\Admin\Concerns\AdminComponent;
use App\Models\CartModel;
use App\Models\Contact;
use App\Models\UserRegistrationModel;
use Livewire\Component;

/** /admin: what needs attention, totals and recent activity. */
class Dashboard extends Component
{
    use AdminComponent;

    public function render()
    {
        // Records waiting for approval, per resource that has an approval column.
        $pending = [];
        foreach (ResourceRegistry::classes() as $class) {
            $approval = $class::approval();
            if (! $approval || $class::adminOnly()) {
                continue;
            }
            $column = $approval['column'];
            $count = $class::query()->where(fn ($q) => $q->whereNull($column)->orWhere($column, '!=', $approval['on']))->count();
            $pending[] = [
                'label' => $class::label(), 'icon' => $class::icon(), 'count' => $count,
                'url' => route('admin.resources.index', ['resource' => $class::slug(), 'filter' => [$column => '__pending']]),
            ];
        }

        $totals = [];
        foreach (ResourceRegistry::classes() as $class) {
            if ($class::adminOnly()) {
                continue;
            }
            $totals[] = ['label' => $class::label(), 'icon' => $class::icon(), 'count' => $class::query()->count(),
                'url' => route('admin.resources.index', ['resource' => $class::slug()]), 'group' => $class::group()];
        }

        $monthStart = now()->startOfMonth();

        return view('livewire.admin.dashboard', [
            'pending' => $pending,
            'totals' => $totals,
            'pendingBookings' => CartModel::pending()->count(),
            'bookingsThisMonth' => CartModel::where('created_at', '>=', $monthStart)->count(),
            'revenueThisMonth' => (float) CartModel::confirmed()->where('created_at', '>=', $monthStart)->sum('Cost'),
            'newCustomers' => UserRegistrationModel::where('created_at', '>=', now()->subDays(30))->count(),
            'recentBookings' => CartModel::orderByDesc('IDs')->limit(8)->get(),
            'recentCustomers' => UserRegistrationModel::orderByDesc('id')->limit(6)->get(),
            'recentMessages' => Contact::orderByDesc('id')->limit(5)->get(),
            'approvedValue' => Flags::APPROVED,
        ])->layout('components.layouts.admin', $this->adminLayout('Dashboard'));
    }
}
