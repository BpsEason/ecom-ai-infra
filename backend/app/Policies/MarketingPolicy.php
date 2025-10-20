<?php
namespace App\Policies;
use App\Models\User;
use Modules\Marketing\Models\MarketingModel;
class MarketingPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, MarketingModel $model): bool { return true; }
    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, MarketingModel $model): bool { return $user->isAdmin(); }
    public function delete(User $user, MarketingModel $model): bool { return $user->isAdmin(); }
}
