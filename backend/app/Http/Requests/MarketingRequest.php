<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class MarketingRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    
    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'budget' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:1000'
        ];
    }
}
