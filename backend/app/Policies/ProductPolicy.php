<?php
namespace App\Policies;
use App\Models\User;
use Modules\Product\Models\ProductModel;
class ProductPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, ProductModel $model): bool { return true; }
    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, ProductModel $model): bool { return $user->isAdmin(); }
    public function delete(User $user, ProductModel $model): bool { return $user->isAdmin(); }
}
