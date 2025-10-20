<?php
namespace Modules\CustomerService\Services;
use Modules\CustomerService\Models\CustomerServiceModel;
use App\Services\AIService;
class CustomerServiceService extends AIService
{
    protected $model;
    public function __construct(CustomerServiceModel $model) { $this->model = $model; }
    public function getAll($perPage = 15) { return $this->model->paginate($perPage); }
    public function findById($id) { return $this->model->findOrFail($id); }
    public function create(array $data) { return $this->model->create($data); }
    public function update($id, array $data) { return $this->model->findOrFail($id)->update($data); }
    public function delete($id) { return $this->model->findOrFail($id)->delete(); }
    
    public function suggestWave($ticketId) {
        $cacheKey = 'wave_suggestion_' . $ticketId;
        return $this->postToAI('wave_suggestion', ['ticket_id' => $ticketId], $cacheKey);
    }
}
