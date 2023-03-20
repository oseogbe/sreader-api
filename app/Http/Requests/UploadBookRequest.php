<?php

namespace App\Http\Requests;

use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadBookRequest extends FormRequest
{
    private BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

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
        return [
            'subject_id' => ['required', 'string', 'exists:subjects,id'],
            'level_id' => ['required', 'numeric', 'exists:levels,id'],
            'description' => ['required', 'string'],
            'cover' => ['required', 'mimes:png,jpg', 'max:2084'],
            'file' => ['required', 'mimes:epub', 'max:20000'],
            'is_compulsory' => ['boolean'],
            'department' => ['nullable', 'in:arts,science,commerce'],
            'level_id' => Rule::unique('books')->where(function ($query) {
                return $query->where('subject_id', request()->subject_id);
            }),
            // 'subject_id' => Rule::unique('books')->where(function ($query) {
            //     return $query->where('level_id', request()->level_id);
            // })
        ];
    }

    public function messages()
    {
        return [
            'level_id.unique' => getSubjectNameById(request()->subject_id) . " book for " . getLevelNameById(request()->level_id) . " already exists",
        ];
    }
}
