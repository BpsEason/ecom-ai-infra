<?php
namespace Modules\Marketing\Services;
use Modules\Marketing\Models\MarketingModel;
use App\Services\AIService;
class MarketingService extends AIService
{
    protected $model;
    public function __construct(MarketingModel $model) { $this->model = $model; }
    public function getAll($perPage = 15) { return $this->model->paginate($perPage); }
    public function findById($id) { return $this->model->findOrFail($id); }
    public function create(array $data) { return $this->model->create($data); }
    public function update($id, array $data) { return $this->model->findOrFail($id)->update($data); }
    public function delete($id) { return $this->model->findOrFail($id)->delete(); }
    
    public function generateAiCopy($campaignId, $description) {
        $cacheKey = 'marketing_copy_' . $campaignId;
        return $this->postToAI('content_generation', ['campaign_id' => $campaignId, 'description' => $description], $cacheKey);
    }
}
