<?php
namespace App\Policies;
use App\Models\User;
use Modules\CustomerService\Models\CustomerServiceModel;
class CustomerServicePolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, CustomerServiceModel $model): bool { return true; }
    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, CustomerServiceModel $model): bool { return $user->isAdmin(); }
    public function delete(User $user, CustomerServiceModel $model): bool { return $user->isAdmin(); }
}
