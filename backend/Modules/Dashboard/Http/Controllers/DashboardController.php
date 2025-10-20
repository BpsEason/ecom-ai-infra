<?php
namespace Modules\Dashboard\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use Modules\Dashboard\Services\DashboardService;
class DashboardController extends Controller
{
    protected $service;
    public function __construct(DashboardService $service)
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
    public function store(Request $request)
    {
        $this->authorize('create', $this->service->model);
        $record = $this->service->create($request->validated());
        return response()->json(['message' => 'Dashboard created', 'data' => $record], 201);
    }
    public function update(Request $request, $id)
    {
        $record = $this->service->findById($id);
        $this->authorize('update', $record);
        $this->service->update($id, $request->validated());
        return response()->json(['message' => 'Dashboard updated']);
    }
    public function destroy($id)
    {
        $record = $this->service->findById($id);
        $this->authorize('delete', $record);
        $this->service->delete($id);
        return response()->json(['message' => 'Dashboard deleted'], 204);
    }
    
}
