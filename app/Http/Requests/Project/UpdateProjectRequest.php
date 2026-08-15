<?php

declare(strict_types=1);

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', Rule::exists('clients', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'target_completion_date' => ['required', 'date', 'after_or_equal:start_date'],
            'agreed_budget' => ['nullable', 'numeric', 'min:0', 'decimal:0,2', 'max:9999999999.99'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'client_id.required' => 'Müşteri seçimi zorunludur.',
            'client_id.exists' => 'Seçilen müşteri bulunamadı.',
            'title.required' => 'Proje başlığı zorunludur.',
            'start_date.required' => 'Başlangıç tarihi zorunludur.',
            'target_completion_date.required' => 'Tahmini bitiş tarihi zorunludur.',
            'target_completion_date.after_or_equal' => 'Bitiş tarihi başlangıçtan önce olamaz.',
            'agreed_budget.numeric' => 'Anlaşılan bütçe sayı olmalıdır.',
            'agreed_budget.min' => 'Anlaşılan bütçe negatif olamaz.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('agreed_budget') === '') {
            $this->merge(['agreed_budget' => null]);
        }
    }
}
