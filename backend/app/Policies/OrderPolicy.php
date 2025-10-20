<?php
namespace App\Policies;
use App\Models\User;
use Modules\Order\Models\OrderModel;
class OrderPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, OrderModel $model): bool { return true; }
    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, OrderModel $model): bool { return $user->isAdmin(); }
    public function delete(User $user, OrderModel $model): bool { return $user->isAdmin(); }
}
