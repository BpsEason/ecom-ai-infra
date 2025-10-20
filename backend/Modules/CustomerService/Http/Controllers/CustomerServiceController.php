<?php
namespace Modules\CustomerService\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerServiceRequest;
use Modules\CustomerService\Services\CustomerServiceService;
class CustomerServiceController extends Controller
{
    protected $service;
    public function __construct(CustomerServiceService $service)
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
    public function store(CustomerServiceRequest $request)
    {
        $this->authorize('create', $this->service->model);
        $record = $this->service->create($request->validated());
        return response()->json(['message' => 'CustomerService created', 'data' => $record], 201);
    }
    public function update(CustomerServiceRequest $request, $id)
    {
        $record = $this->service->findById($id);
        $this->authorize('update', $record);
        $this->service->update($id, $request->validated());
        return response()->json(['message' => 'CustomerService updated']);
    }
    public function destroy($id)
    {
        $record = $this->service->findById($id);
        $this->authorize('delete', $record);
        $this->service->delete($id);
        return response()->json(['message' => 'CustomerService deleted'], 204);
    }
    
    public function suggestWave(Request $request, $id) {
        $this->authorize('view', $this->service->model);
        $result = $this->service->suggestWave($id);
        if (!$result['success']) {
            return response()->json(['message' => 'Wave suggestion failed', 'error' => $result['error']], 503);
        }
        return response()->json(['message' => 'Wave suggested', 'data' => $result['data']]);
    }
}
