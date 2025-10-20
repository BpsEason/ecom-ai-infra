<?php
namespace Modules\Marketing\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MarketingRequest;
use Modules\Marketing\Services\MarketingService;
class MarketingController extends Controller
{
    protected $service;
    public function __construct(MarketingService $service)
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
    public function store(MarketingRequest $request)
    {
        $this->authorize('create', $this->service->model);
        $record = $this->service->create($request->validated());
        return response()->json(['message' => 'Marketing created', 'data' => $record], 201);
    }
    public function update(MarketingRequest $request, $id)
    {
        $record = $this->service->findById($id);
        $this->authorize('update', $record);
        $this->service->update($id, $request->validated());
        return response()->json(['message' => 'Marketing updated']);
    }
    public function destroy($id)
    {
        $record = $this->service->findById($id);
        $this->authorize('delete', $record);
        $this->service->delete($id);
        return response()->json(['message' => 'Marketing deleted'], 204);
    }
    
    public function generateCopy(Request $request) {
        $this->authorize('create', $this->service->model);
        $validated = $request->validate(['description' => 'required|string|max:1000']);
        $result = $this->service->generateAiCopy(rand(1, 1000), $validated['description']);
        if (!$result['success']) {
            return response()->json(['message' => 'Copy generation failed', 'error' => $result['error']], 503);
        }
        return response()->json(['message' => 'Copy generated', 'data' => $result['data']]);
    }
}
