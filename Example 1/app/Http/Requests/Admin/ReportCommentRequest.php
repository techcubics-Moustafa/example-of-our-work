<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onData(): array
    {
        return [
            'ranking' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function onCreate(): array
    {
        $rules = [];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.title' => ['required', 'string', 'min:2', 'max:255', Rule::unique('report_comment_translations', 'title')],
            ];
        }
        return array_merge($this->onData(), $rules);
    }

    protected function onUpdate(): array
    {
        $rules = [
            'ranking' => _trans('ranking')
        ];
        foreach (locales() as $locale) {
            $rules += [
                $locale . '.title' => ['required', 'string', 'min:2', 'max:100',
                    Rule::unique('report_comment_translations', 'title')
                        ->ignore(request()->segment(3), 'report_comment_id')],
            ];
        }
        return array_merge($this->onData(), $rules);
    }

    public function rules(): array
    {
        if (request()->routeIs('admin.report-comment.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.report-comment.update')) {
            return $this->onUpdate();
        } else {
            return [];
        }
    }
}
