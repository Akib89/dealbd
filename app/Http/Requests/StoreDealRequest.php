<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreDealRequest extends FormRequest {
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array {
        return [
            'title'=>['required','string','min:8','max:180'],
            'description'=>['required','string','min:20','max:10000'],
            'category_id'=>['required','exists:categories,id'],
            'store_id'=>['required','exists:stores,id'],
            'deal_url'=>['required','url:http,https','max:2048'],
            'image_url'=>['nullable','url:http,https','max:2048'],
            'price'=>['required','numeric','min:0','max:999999999'],
            'original_price'=>['nullable','numeric','gte:price','max:999999999'],
            'coupon_code'=>['nullable','string','max:80'],
            'payment_method'=>['nullable','string','max:100'],
            'location'=>['nullable','string','max:100'],
            'expires_at'=>['nullable','date'],
            'affiliate_disclosure'=>['nullable','boolean'],
        ];
    }
}
