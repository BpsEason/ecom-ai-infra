<?php
namespace Modules\Product\Services;
use Modules\Product\Models\ProductModel;
use App\Services\AIService;
class ProductService extends AIService
{
    protected $model;
    public function __construct(ProductModel $model) { $this->model = $model; }
    public function getAll($perPage = 15) { return $this->model->paginate($perPage); }
    public function findById($id) { return $this->model->findOrFail($id); }
    public function create(array $data) { return $this->model->create($data); }
    public function update($id, array $data) { return $this->model->findOrFail($id)->update($data); }
    public function delete($id) { return $this->model->findOrFail($id)->delete(); }
    
    public function getRecommendations($productId) {
        $cacheKey = 'product_recommendations_' . $productId;
        return $this->postToAI('recommendation', ['product_id' => $productId], $cacheKey);
    }
}
