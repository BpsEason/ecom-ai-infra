<?php
namespace Modules\Order\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use Modules\Order\Services\OrderService;
class OrderController extends Controller
{
    protected $service;
    public function __construct(OrderService $service)
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
    public function store(OrderRequest $request)
    {
        $this->authorize('create', $this->service->model);
        $record = $this->service->create($request->validated());
        return response()->json(['message' => 'Order created', 'data' => $record], 201);
    }
    public function update(OrderRequest $request, $id)
    {
        $record = $this->service->findById($id);
        $this->authorize('update', $record);
        $this->service->update($id, $request->validated());
        return response()->json(['message' => 'Order updated']);
    }
    public function destroy($id)
    {
        $record = $this->service->findById($id);
        $this->authorize('delete', $record);
        $this->service->delete($id);
        return response()->json(['message' => 'Order deleted'], 204);
    }
    
    public function eta(Request $request, $id) {
        $this->authorize('view', $this->service->model);
        $result = $this->service->getDeliveryEta($id);
        if (!$result['success']) {
            return response()->json(['message' => 'ETA prediction failed', 'error' => $result['error']], 503);
        }
        return response()->json(['message' => 'ETA retrieved', 'data' => $result['data']]);
    }
}
