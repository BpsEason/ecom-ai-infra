<?php
namespace Modules\Product\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Modules\Product\Services\ProductService;
class ProductController extends Controller
{
    protected $service;
    public function __construct(ProductService $service)
    {
        $this->service = $service;
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }
    public function index()
    {
        $this->authorize('viewAny', $this->service->model);
        return response()->json(['data' => $this->service->getAll()]);
    }
    public function show($id)
    {
        $record = $this->service->findById($id);
        $this->authorize('view', $record);
        return response()->json(['data' => $record]);
    }
    public function store(ProductRequest $request)
    {
        $this->authorize('create', $this->service->model);
        $record = $this->service->create($request->validated());
        return response()->json(['message' => 'Product created', 'data' => $record], 201);
    }
    public function update(ProductRequest $request, $id)
    {
        $record = $this->service->findById($id);
        $this->authorize('update', $record);
        $this->service->update($id, $request->validated());
        return response()->json(['message' => 'Product updated']);
    }
    public function destroy($id)
    {
        $record = $this->service->findById($id);
        $this->authorize('delete', $record);
        $this->service->delete($id);
        return response()->json(['message' => 'Product deleted'], 204);
    }
    
    public function recommend(Request $request, $id) {
        $this->authorize('view', $this->service->model);
        $result = $this->service->getRecommendations($id);
        if (!$result['success']) {
            return response()->json(['message' => 'Recommendation failed', 'error' => $result['error']], 503);
        }
        return response()->json(['message' => 'Recommendations retrieved', 'data' => $result['data']]);
    }
}
