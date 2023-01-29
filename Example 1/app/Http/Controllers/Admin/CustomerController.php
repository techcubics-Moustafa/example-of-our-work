<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\Order;
use App\Traits\Api\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Propaganistas\LaravelPhone\PhoneNumber;

class CustomerController extends Controller
{
    use ApiResponses;

    public function __construct()
    {
        $this->middleware(['permission:Customer list,admin'])->only(['index']);

    }

    public function index(Request $request)
    {
        try {
            $customers = Customer::query()
                ->with([
                    'user' => ['country']
                ])
                ->orderBy('created_at', 'ASC')
                ->when($request->clinic, function ($q) use ($request) {
                    $q->whereHas('orders', function ($q) use ($request) {
                        return $q->whereHas('clinic', function ($query) use ($request) {
                            $query->whereTranslation('name', $request->clinic, default_lang());
                        });
                    });
                });

            $customers = $customers->withCount(['orders']);
            $data = Customer::allCustomers($customers);
            $data->each(function ($customer) {
                if ($customer->phone) {
                    $customer->phone = PhoneNumber::make($customer->phone, $customer->user->country->code)
                        ->formatForMobileDialingInCountry($customer->user->country->code);
                }
            });
            $columns = [
                'all' => _trans('All'),
                'name' => _trans('Name'),
                'email' => _trans('Email'),
                'phone' => _trans('Phone'),
                'clinic' => _trans('Clinic'),
            ];
            return view('admin.customer.index', compact('data', 'columns'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function show($id)
    {
        $customer = Customer::query()->with([
            'user' => ['country', 'governorate', 'region']
        ])
            ->withCount(['orders'])
            ->withSum('orders', 'price')
            ->withSum('orders', 'coupon_price')
            ->findOrFail($id);
        if ($customer->user?->country) {
            $customer->phone = PhoneNumber::make($customer->phone, $customer->user?->country->code)->formatForMobileDialingInCountry($customer->user?->country->code);
        }
        return view('admin.customer.show', compact('customer'));
    }


    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            $customer = Customer::query()->find($request->id);
            if (!$customer) {
                return $this->success(_trans('Not Found'), false);
            }
            $status = !$customer->user->status;
            if ($customer->user->update(['status' => $status])) {
                $customer->user->tokens()->delete();
                DB::commit();
                return $this->success(_trans('Done Updated Data Successfully'));
            }
            return $this->success(_trans('Some failed errors'), false);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->success($exception->getMessage(), false);
        }
    }
}
