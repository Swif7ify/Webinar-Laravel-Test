<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'max_file_size_mb' => 20,
            'accepted_formats' => ['pdf', 'docx', 'xlsx', 'pptx', 'jpg', 'png'],
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'instructions' => ['nullable', 'string'],
            'due_date' => ['required', 'date_format:Y-m-d'],
            'due_time' => ['required', 'date_format:H:i'],
            'accepted_formats' => ['required', 'array', 'min:1'],
            'accepted_formats.*' => ['in:pdf,docx,xlsx,pptx,jpg,png'],
            'max_file_size_mb' => ['required', 'integer', 'in:5,10,20,50'],
            'reference_files' => ['nullable', 'array', 'max:5'],
            'reference_files.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png'],
        ];
    }

    public function messages(): array
    {
        return [
            'accepted_formats.min' => 'Select at least one accepted file format.',
            'reference_files.max' => 'You may attach a maximum of 5 reference files.',
            'reference_files.*.max' => 'Each reference file must not exceed 10 MB.',
        ];
    }
}
