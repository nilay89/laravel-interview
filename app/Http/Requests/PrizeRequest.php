<?php

namespace App\Http\Requests;

use App\Models\Prize;
use Illuminate\Foundation\Http\FormRequest;

class PrizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
   
        public function rules()
        {
            $remainingProbability = 100 - Prize::sum('probability');
        
            return [
                'title' => 'required|string|max:255',
                'probability' => ['required', 'numeric', 'min:0', "max:$remainingProbability"],
            ];
        }
        
    
}
