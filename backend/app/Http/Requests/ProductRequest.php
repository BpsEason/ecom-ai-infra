<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ProductRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    
    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . ($this->route('product')?->id ?: 'NULL') . ',id',
            'price' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:1000'
        ];
    }
}
