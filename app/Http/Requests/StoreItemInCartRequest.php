<?php

namespace App\Http\Requests;

use App\Models\DesignOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreItemInCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'design_id' => ['required', 'integer', Rule::exists('designs', 'id')],
            'design_option_ids' => ['required', 'array', 'min:1'],
            'design_option_ids.*' => ['required', 'integer', 'distinct', Rule::exists('design_options', 'id')],
            'measurement_id' => ['required', 'integer', Rule::exists('measurements', 'id')],
            'quantity' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                // If basic validation already failed, don't continue.
                if ($validator->errors()->isNotEmpty())
                    return;

                $designId = (int) $this->input('design_id');
                $ids = array_values(array_unique($this->input('design_option_ids', [])));

                $measurementId = (int) $this->input('measurement_id');

                $measurementExistsForDesign = DB::table('design_measurement')
                    ->where('design_id', $designId)
                    ->where('measurement_id', $measurementId)
                    ->exists();

                if (!$measurementExistsForDesign) {
                    $validator->errors()->add('measurement_id', 'The selected measurement is not attached to this design.');
                    return;
                }

                // 1) All required types for THIS design (distinct)
                $requiredTypes = DesignOption::query()
                    ->join('design_design_option as p', 'p.design_option_id', '=', 'design_options.id')
                    ->where('p.design_id', $designId)
                    ->distinct()
                    ->pluck('design_options.type')
                    ->all();

                // 2) Options the user selected that are attached to THIS design (id + type)
                $selected = DesignOption::query()
                    ->select('design_options.id', 'design_options.type')
                    ->join('design_design_option as p', 'p.design_option_id', '=', 'design_options.id')
                    ->where('p.design_id', $designId)
                    ->whereIn('design_options.id', $ids)
                    ->get();

                // A) Make sure every sent option actually belongs to this design
                if ($selected->count() !== count($ids)) {
                    $validator->errors()->add(
                        'design_option_ids',
                        'One or more design options are not attached to this design.'
                    );
                    return;
                }

                // B) Exactly ONE per type:
                // - same number of selected options as required types
                // - each required type appears exactly once
                $countsByType = $selected->groupBy('type')->map->count()->all();

                $missingTypes = array_values(array_diff($requiredTypes, array_keys($countsByType)));
                $duplicateTypes = array_keys(array_filter($countsByType, fn($c) => $c > 1));

                if (count($ids) !== count($requiredTypes) || !empty($missingTypes) || !empty($duplicateTypes)) {
                    if (!empty($missingTypes)) {
                        $validator->errors()->add(
                            'design_option_ids',
                            'You must send exactly one option for each type. Missing: ' . implode(', ', $missingTypes)
                        );
                    }

                    if (!empty($duplicateTypes)) {
                        $validator->errors()->add(
                            'design_option_ids',
                            'You must send exactly one option per type (duplicates found for: ' . implode(', ', $duplicateTypes) . ').'
                        );
                    }

                    if (count($ids) !== count($requiredTypes) && empty($missingTypes) && empty($duplicateTypes)) {
                        $validator->errors()->add(
                            'design_option_ids',
                            'You must send exactly one option for each type attached to this design.'
                        );
                    }
                }
            }
        ];
    }
}
