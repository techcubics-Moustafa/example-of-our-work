<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CurrencyRequest;
use App\Models\Currency;
use App\Traits\Api\ApiResponses;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Currency list,admin'])->only(['index']);
        $this->middleware(['permission:Currency add,admin'])->only(['create']);
        $this->middleware(['permission:Currency edit,admin'])->only(['edit']);
        $this->middleware(['permission:Currency delete,admin'])->only(['destroy']);
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $currencies = Currency::query();
        $currencies = Currency::allCurrencies($currencies);
        $columns = [
            'all' => _trans('All'),
            'code' => _trans('Currency code'),
            'name' => _trans('Currency name'),
        ];
        return view('admin.currency.index', compact('currencies', 'columns'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = false;
        return view('admin.currency.form', compact('edit'));
    }

    public function store(CurrencyRequest $request): string|\Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        Currency::query()->create($data);
        return redirect()->route('admin.currency.index')->with('success', _trans('Done Save Data Successfully'));
    }

    public function edit(Currency $currency): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $edit = true;
        return view('admin.currency.form', compact('edit', 'currency'));
    }

    public function update(CurrencyRequest $request, Currency $currency): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $currency->update($data);
        return redirect()->route('admin.currency.index')->with('success', _trans('Done Updated Data Successfully'));
    }

    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $currency = Currency::query()->findOrFail($request->id);
        if (!$currency) {
            return $this->success(_trans('Not Found'), status: false);
        }
        $status = !$currency->status;
        $currency->update(['status' => $status]);
        return $this->success(_trans('Done Updated Data Successfully'));
    }
}
