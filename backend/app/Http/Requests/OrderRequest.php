<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class OrderRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    
    public function rules(): array {
        return [
            'customer_id' => 'required|integer|exists:users,id',
            'total_amount' => 'required|numeric|min:0.01',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ];
    }
}
