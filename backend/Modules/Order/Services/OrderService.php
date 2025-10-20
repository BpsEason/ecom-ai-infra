<?php
namespace Modules\Order\Services;
use Modules\Order\Models\OrderModel;
use App\Services\AIService;
class OrderService extends AIService
{
    protected $model;
    public function __construct(OrderModel $model) { $this->model = $model; }
    public function getAll($perPage = 15) { return $this->model->paginate($perPage); }
    public function findById($id) { return $this->model->findOrFail($id); }
    public function create(array $data) { return $this->model->create($data); }
    public function update($id, array $data) { return $this->model->findOrFail($id)->update($data); }
    public function delete($id) { return $this->model->findOrFail($id)->delete(); }
    
    public function getDeliveryEta($orderId) {
        $cacheKey = 'order_eta_' . $orderId;
        return $this->postToAI('eta_prediction', ['order_id' => $orderId], $cacheKey);
    }
}
