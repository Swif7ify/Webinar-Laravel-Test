<?php

namespace App\Http\Requests\Student;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $task = $this->route('task');
        
        $maxSize = ($task->max_file_size_mb ?? 10) * 1024; // in KB
        $formats = implode(',', $task->accepted_formats ?? ['pdf', 'doc', 'docx', 'zip']);

        return [
            'files'   => 'required|array|min:1',
            'files.*' => [
                'required',
                'file',
                'max:' . $maxSize,
                'mimes:' . $formats,
            ],
        ];
    }
}
