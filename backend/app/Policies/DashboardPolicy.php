<?php
namespace App\Policies;
use App\Models\User;
use Modules\Dashboard\Models\DashboardModel;
class DashboardPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, DashboardModel $model): bool { return true; }
    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, DashboardModel $model): bool { return $user->isAdmin(); }
    public function delete(User $user, DashboardModel $model): bool { return $user->isAdmin(); }
}
