# ECOM-AI-INFRA：AI 驅動電商系統骨架

**ECOM-AI-INFRA** 是一個企業級電商平台骨架，旨在整合 AI 技術以提升電商運營效率與用戶體驗。本專案提供模組化架構，支援產品管理、訂單處理、行銷活動、客服票單與 AI 洞察，適用於快速原型開發或作為企業電商系統的基礎。專案採用現代技術棧，確保可擴展性與維護性，適合企業主尋求高效解決方案或技術團隊進行二次開發。

## 商業價值與應用場景

ECOM-AI-INFRA 為電商企業提供一個靈活的框架，將傳統業務流程與 AI 功能結合，幫助優化運營並提升競爭力。作為骨架，專案已預留 AI 服務介面，方便後續擴展。

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

本專案為電商企業提供一個穩固的起點，後續可根據需求添加進階 AI 或業務邏輯。

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
- **AI 微服務**：包含 `content_generation`, `eta_prediction`, `wave_suggestion`, `recommendation`，每個服務提供模型、路由與測試骨架，支援後續實作 NLP、回歸或聚類模型。
- **可擴展性**：模組化設計允許新增服務或模型，Docker 支援水平擴展。

骨架提供清晰的結構，方便團隊快速實作業務邏輯或整合進階 AI 模型。

## 如何擴展

ECOM-AI-INFRA 的模組化設計與微服務架構使其易於擴展，以下為主要擴展路徑：

- **新增後端模組**：
  - 在 `backend/Modules` 新增模組（如 `Inventory`），複製 `Product` 模組結構，包含 `Model`, `Service`, `Controller`, `Policy`, `Request` 與 `Routes`。
  - 更新 `database/migrations` 定義新表結構，執行 `php artisan migrate`。
- **新增 AI 服務**：
  - 在 `ai-services/services` 新增服務目錄（如 `price_optimization`），複製 `content_generation` 結構。
  - 實作模型邏輯（如基於 `transformers` 的進階 NLP），並更新 `routes/prediction.py` 與 `tests/test_api.py`。
  - 在 `docker-compose.yml` 加入新服務配置，確保 NGINX `upstream ai_services` 包含新服務。
- **前端功能擴展**：
  - 在 `frontend/src/views` 新增視圖（如 `InventoryView.vue`），對應新後端模組。
  - 在 `frontend/src/api/api.js` 新增 API 調用函數，支援新模組的 CRUD。
  - 使用 Tailwind CSS 或 Vuetify 增強 UI，提升視覺體驗。
- **基礎設施升級**：
  - 部署到 Kubernetes，取代 Docker Compose，實現高可用性與自動擴展。
  - 整合 CI/CD 工具（如 GitHub Actions），自動化測試與部署。
  - 新增監控工具（如 Prometheus + Grafana），追蹤 API 性能與 AI 服務負載。

## 客製化建議

ECOM-AI-INFRA 骨架可根據特定需求客製化，以下為針對不同場景的建議：

- **中小電商**：
  - 實作進階推薦模型（如協同過濾或基於深度學習的 `torch` 模型），提升產品推薦精準度。
  - 加入多語言支援，在前端使用 `i18n` 套件，後端新增語言欄位。
- **物流型電商**：
  - 整合真實物流數據（如 GPS 追蹤）到 `eta_prediction`，使用時間序列模型（如 Prophet）提升預測準確性。
  - 為 `wave_suggestion` 加入優先級排序，根據票單緊急程度動態調整客服波次。
- **企業內部系統**：
  - 與現有 ERP 整合，透過 API 串接 SAP 或 Odoo，實現資料同步。
  - 為 `content_generation` 加入品牌語氣客製化，使用預訓練模型（如 BERT）生成符合品牌風格的文案。
- **SaaS 平台**：
  - 實現多租戶架構，在 `backend` 新增租戶識別欄位，確保資料隔離。
  - 提供 API 管理介面（如 Swagger），方便客戶自訂 AI 服務參數。
- **進階 AI 功能**：
  - 替換簡化模型（如 `scikit-learn`）為進階框架（如 `Hugging Face transformers` 或 `TensorFlow`）。
  - 加入模型訓練管道，使用 MLflow 管理模型版本與實驗。

這些建議可根據業務需求逐步實作，骨架的靈活性確保快速適應不同場景。

## 部署方式與測試覆蓋

ECOM-AI-INFRA 骨架設計為一鍵部署，支援本地或雲端環境，並內建測試框架以確保品質。

### 部署方式
1. **環境準備**：安裝 Docker 與 Docker Compose。
2. **啟動專案**：在 `ecom-ai-infra` 目錄執行：
   ```bash
   docker compose up -d --build
   ```
   啟動所有容器（`frontend`, `backend`, `ai_content` 等）。
3. **初始化資料庫**：執行以下命令載入遷移與種子資料：
   ```bash
   docker compose exec backend php artisan migrate --seed
   ```
4. **訪問系統**：開啟 `http://localhost`，使用 `admin@example.com` / `password` 登入。
5. **進階配置**：修改 `backend/.env` 調整環境變數，或使用 Kubernetes 部署。

### 測試覆蓋
- **後端**：PHPUnit 測試骨架，涵蓋 CRUD、權限與 AI 串接，執行：
   ```bash
   docker compose exec backend php artisan test
   ```
- **AI 服務**：Pytest 測試骨架，支援 API 端點驗證，執行：
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

ECOM-AI-INFRA 提供一個模組化、可擴展的電商平台骨架，結合 AI 潛力，適合快速原型或企業級開發。專案結構清晰，技術棧成熟，歡迎企業與開發者基於此骨架打造創新解決方案。欲了解更多或貢獻代碼，請參考 [GitHub 儲存庫](#)。
