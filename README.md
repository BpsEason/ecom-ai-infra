# ECOM-AI-INFRA：AI 驅動電商系統骨架

**ECOM-AI-INFRA** 是一個企業級電商平台骨架，旨在整合 AI 技術以提升運營效率與用戶體驗。本專案提供模組化架構，支援產品管理、訂單處理、行銷活動、客服票單與 AI 洞察，適用於快速原型開發或作為企業電商系統的基礎。專案採用現代技術棧，確保可擴展性與維護性，適合企業主尋求高效解決方案或技術團隊進行二次開發。

## 商業價值與應用場景

ECOM-AI-INFRA 提供靈活框架，將傳統業務流程與 AI 功能結合，幫助電商企業優化運營並提升競爭力。作為骨架，專案預留 AI 服務介面，方便後續擴展。

### 商業價值
- **個人化體驗**：預留產品推薦（`recommendation`）介面，支援基於用戶行為的個人化建議，提升購買轉換率。
- **運營效率**：支援交貨時間預測（`eta_prediction`）與客服波次建議（`wave_suggestion`），減少物流與客服成本。
- **行銷自動化**：整合內容生成（`content_generation`）介面，快速產出行銷文案，加速活動部署。
- **數據洞察**：儀表板（`Dashboard`）整合業務與 AI 數據，支援實時決策。

### 應用場景
- **中小電商**：快速搭建產品管理與訂單系統，後續整合 AI 推薦功能。
- **物流平台**：擴展 ETA 預測與波次建議，優化配送與客服效率。
- **企業內部**：作為 ERP 模組，支援行銷與客服自動化。
- **客製化開發**：骨架可擴展為 SaaS 或特定產業解決方案（如生鮮電商）。

本專案為電商企業提供穩固起點，後續可根據需求添加進階 AI 或業務邏輯。

## 技術架構與模組設計

ECOM-AI-INFRA 採用微服務架構，確保模組解耦與高可擴展性。專案骨架使用成熟技術棧，支援快速部署與二次開發，強調安全、性能與測試覆蓋。

### 技術架構圖
以下為系統架構的文字示意圖，展示前端、後端、AI 微服務與基礎設施的互動：

```
+-------------------+       +-------------------+       +-------------------+
|     Frontend      |       |      Backend      |       |   AI Microservices |
| (Vue 3 + Vite)    |<----->|  (Laravel 12)     |<----->|    (FastAPI)       |
| - Views           |  HTTP | - API Controllers |  HTTP | - Prediction Routes|
| - API Client      |       | - Services/Models |       | - Training Routes  |
| - Chart.js        |       | - Sanctum Auth    |       | - AI Models        |
+-------------------+       +-------------------+       +-------------------+
           |                        |                        |
           v                        v                        v
+-------------------+       +-------------------+       +-------------------+
|      NGINX        |       |      MySQL       |       |       Redis       |
| - Reverse Proxy   |       | - Product/Order   |       | - Cache AI Data   |
| - Load Balancing  |       | - Marketing/CS    |       |                   |
+-------------------+       +-------------------+       +-------------------+
           ^
           |
+-------------------+
|  Docker Compose   |
| - Containers      |
| - Volumes         |
| - Health Checks   |
+-------------------+
```

### 模組設計
- **後端模組**：包含 `Product`, `Order`, `Marketing`, `CustomerService`, `Dashboard`，每個模組提供：
  - **Model**：資料結構，映射 MySQL 表。
  - **Service**：業務邏輯，包含 CRUD 與 AI 介面（透過 `AIService` 抽象類與 FastAPI 串接）。
  - **Controller**：處理 API 請求，支援權限（`Policy`）與驗證（`Request`）。
  - **Routes**：RESTful API 與 AI 端點（如 `/product/{id}/recommend`）。
- **前端視圖**：對應後端模組的 Vue Views，提供表單與列表基礎，`DashboardView` 支援 AI 數據展示。
- **AI 微服務**：包含 `content_generation`, `eta_prediction`, `wave_suggestion`, `recommendation`，提供模型、路由與測試骨架，支援後續實作。
- **可擴展性**：模組化設計允許新增服務或模型，Docker 支援水平擴展。

## 程式碼亮點

以下為專案關鍵代碼片段（來自 `ecom-ai-infra.sh`），展示設計優勢與技術亮點：

### 1. **後端 AI 服務抽象類（`AIService.php`）**
```php
<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
abstract class AIService
{
    protected $aiBaseUrl = 'http://ai-services:8000';
    protected function postToAI($service, array $data, $cacheKey = null, $ttl = 3600)
    {
        if ($cacheKey && Cache::has($cacheKey)) {
            return ['success' => true, 'data' => Cache::get($cacheKey), 'cached' => true];
        }
        try {
            $response = Http::timeout(10)->post($this->aiBaseUrl . "/$service/predict", ['data' => $data]);
            if ($response->successful()) {
                $result = $response->json()['result'];
                if ($cacheKey) {
                    Cache::put($cacheKey, $result, $ttl);
                }
                return ['success' => true, 'data' => $result, 'cached' => false];
            }
            return ['success' => false, 'error' => $response->json()['detail'] ?? 'AI Service Error'];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => 'Connection Failed: ' . $e->getMessage()];
        }
    }
}
```
**亮點**：
- **統一 AI 串接**：抽象類封裝 FastAPI 調用邏輯，簡化各模組與 AI 服務的交互。
- **性能優化**：使用 Redis 快取（`$cacheKey`）減少重複請求，TTL 設定靈活（預設 3600 秒）。
- **錯誤處理**：內建超時（10 秒）與異常捕獲，確保健壯性。
- **可擴展性**：模組繼承 `AIService`，可快速新增 AI 功能（如價格預測）。

### 2. **模組化後端服務（`ProductService.php` 示例）**
```php
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
```
**亮點**：
- **模組化解耦**：使用 Laravel Modules 結構，`ProductService` 獨立處理業務邏輯，易於維護。
- **依賴注入**：透過建構函數注入 `ProductModel`，增強測試性與靈活性。
- **AI 整合**：透過繼承 `AIService`，無縫調用推薦服務，保持代碼乾淨。
- **標準化 CRUD**：統一的 CRUD 方法，支援分頁（`perPage`），便於前端展示。

### 3. **前端視圖（`ProductView.vue` 示例）**
```vue
<template>
    <div class="p-4">
        <button @click="showForm = true" class="bg-blue-500 text-white px-4 py-2 rounded">新增 Product</button>
        <div v-if="showForm" class="mt-4 bg-white p-4 rounded shadow">
            <form @submit.prevent="submitForm">
                <div class="mb-2">
                    <label class="block">名稱</label>
                    <input v-model="form.name" type="text" class="w-full border rounded px-2 py-1" required>
                </div>
                <div class="mb-2">
                    <label class="block">描述</label>
                    <textarea v-model="form.description" class="w-full border rounded px-2 py-1"></textarea>
                </div>
                <div class="mb-2">
                    <label class="block">SKU</label>
                    <input v-model="form.sku" type="text" class="w-full border rounded px-2 py-1" required>
                </div>
                <div class="mb-2">
                    <label class="block">價格</label>
                    <input v-model="form.price" type="number" step="0.01" class="w-full border rounded px-2 py-1" required>
                </div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">保存</button>
                <button type="button" @click="showForm = false" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">取消</button>
            </form>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Product 列表</h2>
            <div v-if="loading">載入中...</div>
            <div v-else>
                <div v-for="item in items" :key="item.id" class="border-b py-2">
                    <span>{{ item.name }} (ID: {{ item.id }})</span>
                    <button @click="deleteItem(item.id)" class="ml-4 text-red-500">刪除</button>
                </div>
                <div v-if="!items.length" class="text-gray-500">無 Product 數據</div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import { fetchProductList, createProduct, deleteProduct } from '@/api/api';
const items = ref([]);
const loading = ref(false);
const showForm = ref(false);
const form = ref({});
async function loadItems() {
    loading.value = true;
    const response = await fetchProductList();
    items.value = response.success ? response.data.data : [];
    loading.value = false;
}
async function submitForm() {
    const response = await createProduct(form.value);
    if (response.success) {
        showForm.value = false;
        form.value = {};
        loadItems();
    }
}
async function deleteItem(id) {
    const response = await deleteProduct(id);
    if (response.success) {
        loadItems();
    }
}
loadItems();
</script>
```
**亮點**：
- **響應式設計**：使用 Vue 3 Composition API，`ref` 管理狀態，確保高效渲染。
- **模組化 API 調用**：透過 `@/api/api` 封裝 API 請求，簡化前端邏輯與維護。
- **動態表單**：條件渲染（`v-if`）控制表單顯示，支援動態字段（如 `sku`, `price`）。
- **用戶體驗**：即時載入狀態（`loading`）與空數據提示，提升交互友好性。

### 4. **Docker Compose 配置（`docker-compose.yml`）**
```yaml
version: '3.8'
services:
    nginx:
        build: ./infrastructure/nginx
        ports:
            - "80:80"
        volumes:
            - ./infrastructure/nginx/nginx.conf:/etc/nginx/conf.d/default.conf
            - frontend_dist:/usr/share/nginx/html
        depends_on:
            backend: { condition: service_healthy }
            ai_content: { condition: service_healthy }
            ai_eta: { condition: service_started }
            ai_wave: { condition: service_started }
            ai_reco: { condition: service_started }
    backend:
        build: ./backend
        volumes:
            - ./backend:/app
        depends_on:
            db: { condition: service_healthy }
            redis: { condition: service_started }
        environment:
            - DB_HOST=db
            - DB_DATABASE=ecommerce
            - DB_USERNAME=root
            - DB_PASSWORD=root
            - REDIS_HOST=redis
            - CACHE_DRIVER=redis
        healthcheck:
            test: ["CMD", "curl", "-f", "http://localhost:9000"]
            interval: 30s
            timeout: 5s
            retries: 3
    db:
        image: mysql:8.0
        environment:
            MYSQL_ROOT_PASSWORD: root
            MYSQL_DATABASE: ecommerce
        volumes:
            - db_data:/var/lib/mysql
        healthcheck:
            test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
            interval: 10s
            timeout: 5s
            retries: 3
    redis:
        image: redis:7.0-alpine
volumes:
    db_data:
    ai_data:
    frontend_dist:
```
**亮點**：
- **容器化部署**：Docker Compose 整合前端、後端、AI 服務、MySQL 與 Redis，實現一鍵部署。
- **健康檢查**：為 `backend`, `ai_content`, `db` 配置健康檢查，確保服務穩定性。
- **依賴管理**：明確定義服務依賴（如 `backend` 依賴 `db`），避免啟動順序問題。
- **靈活配置**：環境變數與卷（`volumes`）支援快速調整與資料持久化。

這些代碼片段展示專案的高品質設計，確保功能性、可維護性與企業級可靠性。

## 如何擴展

ECOM-AI-INFRA 的模組化設計與微服務架構使其易於擴展，以下為主要擴展路徑：

- **新增後端模組**：
  - 在 `backend/Modules` 新增模組（如 `Inventory`），複製 `Product` 結構，包含 `Model`, `Service`, `Controller`, `Policy`, `Request`, `Routes`。
  - 更新 `database/migrations` 定義新表，執行 `php artisan migrate`。
- **新增 AI 服務**：
  - 在 `ai-services/services` 新增目錄（如 `price_optimization`），複製 `content_generation` 結構。
  - 實作模型邏輯（如 `transformers` NLP 模型），更新 `routes/prediction.py` 與 `tests/test_api.py`。
  - 在 `docker-compose.yml` 添加新服務，更新 NGINX `upstream ai_services`。
- **前端功能擴展**：
  - 在 `frontend/src/views` 新增視圖（如 `InventoryView.vue`），對應新模組。
  - 在 `frontend/src/api/api.js` 新增 API 調用，支援新模組 CRUD。
  - 使用 Tailwind CSS 或 Vuetify 增強 UI。
- **基礎設施升級**：
  - 部署到 Kubernetes，實現高可用性與自動擴展。
  - 整合 CI/CD（如 GitHub Actions），自動化測試與部署。
  - 新增監控（如 Prometheus + Grafana），追蹤性能與負載。

## 客製化建議

ECOM-AI-INFRA 骨架可根據需求客製化，以下為場景建議：

- **中小電商**：
  - 實作進階推薦模型（如協同過濾或 `torch` 模型），提升推薦精準度。
  - 加入多語言支援，使用 `i18n` 套件與後端語言欄位。
- **物流型電商**：
  - 整合物流數據（如 GPS）到 `eta_prediction`，使用時間序列模型（如 Prophet）。
  - 為 `wave_suggestion` 加入優先級排序，動態調整客服波次。
- **企業內部系統**：
  - 透過 API 串接 SAP 或 Odoo，實現資料同步。
  - 為 `content_generation` 加入品牌語氣客製化，使用預訓練模型（如 BERT）。
- **SaaS 平台**：
  - 實現多租戶架構，新增租戶識別欄位，確保資料隔離。
  - 提供 API 管理介面（如 Swagger），方便自訂 AI 參數。
- **進階 AI 功能**：
  - 替換 `scikit-learn` 為 `Hugging Face transformers` 或 `TensorFlow`。
  - 使用 MLflow 管理模型版本與實驗。

## 部署方式與測試覆蓋

ECOM-AI-INFRA 骨架設計為一鍵部署，支援本地或雲端環境，並內建測試框架。

### 部署方式
1. **環境準備**：安裝 Docker 與 Docker Compose。
2. **啟動專案**：在 `ecom-ai-infra` 目錄執行：
   ```bash
   docker compose up -d --build
   ```
   啟動所有容器（`frontend`, `backend`, `ai_content` 等）。
3. **初始化資料庫**：執行：
   ```bash
   docker compose exec backend php artisan migrate --seed
   ```
4. **訪問系統**：開啟 `http://localhost`，使用 `admin@example.com` / `password` 登入。
5. **進階配置**：修改 `backend/.env` 調整變數，或使用 Kubernetes 部署。

### 測試覆蓋
- **後端**：PHPUnit 測試骨架，涵蓋 CRUD、權限與 AI 串接，執行：
   ```bash
   docker compose exec backend php artisan test
   ```
- **AI 服務**：Pytest 測試骨架，驗證 API 端點，執行：
   ```bash
   docker compose exec ai_content pytest
   ```
   （替換服務名稱，如 `ai_eta`）。
- **前端**：Vitest 測試視圖渲染與 API 互動，執行：
   ```bash
   docker compose exec frontend npm run test
   ```
- **整體**：Docker 健康檢查確保容器穩定，支援模擬生產環境測試。

### 除錯指南
- **端口衝突**：確認 80、3306 等端口未被占用。
- **服務失敗**：檢查日誌：`docker compose logs <service>`。
- **資料庫問題**：驗證遷移：`docker compose exec backend php artisan migrate:status`。

## 結語

ECOM-AI-INFRA 提供模組化、可擴展的電商平台骨架，結合 AI 潛力，適合快速原型或企業級開發。專案結構清晰，技術棧成熟，歡迎企業與開發者基於此骨架打造創新解決方案。欲了解更多或貢獻代碼，請參考 [GitHub 儲存庫](#)。
