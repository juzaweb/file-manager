<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\FileManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Juzaweb\FileManager\Enums\MediaType;

class StoreFolderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:155',
                Rule::unique('media')
                    ->where('type', MediaType::DIRECTORY->value)
            ],
            'working_dir' => ['nullable', 'uuid']
        ];
    }

    public function messages(): array
    {
        return [
            'name.exists' => trans('file-manager::browser.error-folder-exist'),
        ];
    }
}
