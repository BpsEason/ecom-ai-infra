<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CustomerServiceRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    
    public function rules(): array {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'status' => 'in:open,resolved,pending'
        ];
    }
}
