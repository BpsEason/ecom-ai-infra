<?php
namespace Modules\Dashboard\Services;
use Modules\Dashboard\Models\DashboardModel;
use App\Services\AIService;
class DashboardService extends AIService
{
    protected $model;
    public function __construct(DashboardModel $model) { $this->model = $model; }
    public function getAll($perPage = 15) { return $this->model->paginate($perPage); }
    public function findById($id) { return $this->model->findOrFail($id); }
    public function create(array $data) { return $this->model->create($data); }
    public function update($id, array $data) { return $this->model->findOrFail($id)->update($data); }
    public function delete($id) { return $this->model->findOrFail($id)->delete(); }
    
}
