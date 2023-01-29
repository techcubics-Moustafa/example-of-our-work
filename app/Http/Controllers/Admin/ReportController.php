<?php

namespace App\Http\Controllers\Admin;

use App\Charts\ClinicChart;
use App\Enums\ChartType;
use App\Enums\OrderStatus;
use App\Helpers\Chart\Chart;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Country;
use App\Models\Discount;
use App\Models\Governorate;
use App\Models\MedicalOffer;
use App\Models\Order;
use App\Models\Rate;
use App\Models\Region;
use App\Models\Service;
use App\Models\SpecialOffer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function CountClinicEveryGovernorate(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $governorates = Governorate::query()
            ->when($request->country, function ($query) use ($request) {
                $query->where('country_id', '=', $request->country);
            })
            ->has('clinics')
            ->withCount(['clinics'])
            ->get();
        $keys = [];
        foreach ($governorates as $governorate) {
            $keys[] = [
                'names' => $governorate->translateOrDefault(locale())?->name,
                'counts' => $governorate->clinics_count,
            ];
        }
        $names = collect($keys)->pluck('names');
        $counts = collect($keys)->pluck('counts');
        $report->labels($names);
        $report->dataset(_trans('Count Governorate'), ChartType::Bar->value, $counts)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1
            ]);
        $report->options($chart->chartConfig());
        $countries = Country::query()->has('governorates')->get();
        return view('admin.report.count-clinics-every-governorate', compact('report', 'countries'));
    }

    public function CountClinicEveryRegion(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $regions = Region::query()
            ->when($request->country_id && $request->governorate_id, function ($query) use ($request) {
                $query->where('country_id', '=', $request->country_id)
                    ->where('governorate_id', '=', $request->governorate_id);
            })
            ->has('clinics')
            ->withCount(['clinics'])
            ->get();
        $keys = [];
        foreach ($regions as $region) {
            $keys[] = [
                'names' => $region->translateOrDefault(locale())?->name,
                'counts' => $region->clinics_count,
            ];
        }
        $names = collect($keys)->pluck('names');
        $counts = collect($keys)->pluck('counts');
        $report->labels($names);
        $report->dataset(_trans('Count Region'), ChartType::Bar->value, $counts)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1
            ]);
        $report->options($chart->chartConfig());
        $countries = Country::query()->has('regions')->get();
        return view('admin.report.count-clinics-every-region', compact('report', 'countries'));
    }

    public function CountOrderEveryClinic(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $orders = Order::query()
            ->select(['created_at', 'order_status'])
            ->when($request->period && $request->start && $request->end, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('created_at', '>=', formatDate('Y-m-d', $request->start))
                        ->where('created_at', '<=', formatDate('Y-m-d', $request->end));
                });
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('order_status', '=', $request->status);
            })
            ->when($request->clinic, function ($query) use ($request) {
                $query->where('clinic_id', '=', $request->clinic);
            })
            ->get()
            ->groupBy(function ($row) use ($request) {
                if ($request->period == 'monthly') {
                    return formatDate('m-Y', $row->created_at);
                } elseif ($request->period == 'yearly') {
                    return formatDate('Y', $row->created_at);
                } else {
                    return formatDate('d-m-Y', $row->created_at);
                }
            });
        $keys = [];
        foreach ($orders as $key => $order) {
            $keys[] = [
                'date' => $key,
                'all' => $order->count(),
                'pending' => $order->where('order_status', '=', OrderStatus::pending->value)->count(),
                'approved' => $order->where('order_status', '=', OrderStatus::approved->value)->count(),
                'processing' => $order->where('order_status', '=', OrderStatus::processing->value)->count(),
                'canceled' => $order->where('order_status', '=', OrderStatus::canceled->value)->count(),
                'completed' => $order->where('order_status', '=', OrderStatus::completed->value)->count(),
            ];
        }
        $dates = collect($keys)->pluck('date');
        $all = collect($keys)->pluck('all');
        $pending = collect($keys)->pluck('pending');
        $approved = collect($keys)->pluck('approved');
        $processing = collect($keys)->pluck('processing');
        $canceled = collect($keys)->pluck('canceled');
        $completed = collect($keys)->pluck('completed');

        $report->labels($dates);

        if (!$request->status) {
            $report->dataset(_trans('Count Orders'), ChartType::Line->value, $all)->options([
                'borderColor' => 'MediumPurple',
                'borderWidth' => 1,
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'fill' => true,
                'tension' => 0.1
            ]);
        }
        $report->dataset(_trans('Count Order Pending'), ChartType::Line->value, $pending)->options([
            'borderColor' => 'Gold',
            'borderWidth' => 1,
            'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
            'fill' => true,
            'tension' => 0.2
        ]);

        $report->dataset('Count Approved', ChartType::Line->value, $approved)->options([
            'borderColor' => 'DodgerBlue',
            'borderWidth' => 1,
            'backgroundColor' => 'rgba(255, 205, 86, 0.2)',
            'fill' => true,
            'tension' => 0.1
        ]);

        $report->dataset('Count Processing', ChartType::Line->value, $processing)->options([
            'borderColor' => 'Crimson',
            'borderWidth' => 1,
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
            'fill' => true,
            'tension' => 0.3
        ]);

        $report->dataset('Count Canceled', ChartType::Line->value, $canceled)->options([
            'borderColor' => 'rgb(255, 99, 132)',
            'borderWidth' => 1,
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
        ]);

        $report->dataset('Count completed', ChartType::Line->value, $completed)->options([
            'borderColor' => 'rgb(75, 192, 192)',
            'borderWidth' => 1,
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
        ]);


        $report->options($chart->chartConfig());

        $clinics = Clinic::query()->has('orders')->get();
        return view('admin.report.count-orders-every-clinic', compact('report', 'clinics'));
    }

    public function CountServicesEveryClinic(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $clinics = Clinic::query()
            ->has('services')
            ->when($request->name, function ($query) use ($request) {
                $query->whereTranslationLike('name', "%{$request->name}%", locale());
            })
            ->withCount(['services'])
            ->get();
        $keys = [];
        foreach ($clinics as $clinic) {
            $keys[] = [
                'names' => $clinic->translateOrDefault(locale())?->name,
                'counts' => $clinic->services_count,
            ];
        }
        $names = collect($keys)->pluck('names');
        $counts = collect($keys)->pluck('counts');
        $report->labels($names);
        $report->dataset(_trans('Count Services'), ChartType::Bar->value, $counts)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1,
            ]);
        $report->options($chart->chartConfig());

        return view('admin.report.count-services-every-clinic', compact('report'));
    }

    public function CountCustomersEveryGovernorateClinic(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $clinics = DB::table('clinics')
            ->join('orders', 'clinics.id', '=', 'orders.clinic_id');
        if ($request->country_id && $request->governorate_id) {
            $clinics = $clinics
                ->where('clinics.country_id', '=', $request->country_id)
                ->where('clinics.governorate_id', '=', $request->governorate_id);
        }
        if ($request->clinic_id) {
            $clinics = $clinics->where('clinics.id', '=', $request->clinic_id);
        }
        if ($request->start && $request->end) {
            $clinics = $clinics->where('orders.created_at', '>=', formatDate('Y-m-d', $request->start))
                ->where('orders.created_at', '<=', formatDate('Y-m-d', $request->end));
        }
        $clinics = $clinics->select([
            'clinics.id as id',
            'orders.customer_id as customer_id',
        ])
            ->distinct(['id', 'customer_id'])
            ->get()
            ->groupBy('id');
        $keys = [];
        foreach ($clinics as $key => $clinic) {
            $clinicDetail = Clinic::query()->find($key);
            $keys[] = [
                'names' => $clinicDetail?->translateOrDefault(locale())?->name,
                'counts' => count($clinic),
            ];
        }
        $names = collect($keys)->pluck('names');
        $counts = collect($keys)->pluck('counts');
        $report->labels($names);
        $chartType = ChartType::Bar->value;
        $report->dataset(_trans('Count Customer'), $chartType, $counts)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1,
            ]);
        $report->options($chart->chartConfig());


        $clinics = $countries = [];
        if (\request()->routeIs('admin.report.count-customers-every-governorate')) {
            $countries = Country::query()->get();
        } else {
            $clinics = Clinic::query()->orderByDesc('created_at')->get();
        }

        return view('admin.report.count-customers-every-clinic', compact('report', 'countries', 'clinics'));
    }

    public function CountCustomersEveryServiceClinic(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $orders = DB::table('orders')
            ->join('order_details', 'order_details.order_id', '=', 'orders.id')
            ->join('service_translations', 'order_details.modelable_id', '=', 'service_translations.service_id')
            ->where('order_details.modelable_type', 'LIKE', '%Service%');
        if ($request->name) {
            $orders = $orders->where('service_translations.name', 'LIKE', "%{$request->name}%")
                ->where('service_translations.locale', '=', locale());
        }
        if ($request->clinic_id) {
            $orders = $orders->where('orders.clinic_id', '=', $request->clinic_id);
        }
        if ($request->start && $request->end) {
            $orders = $orders->where('orders.created_at', '>=', formatDate('Y-m-d', $request->start))
                ->where('orders.created_at', '<=', formatDate('Y-m-d', $request->end));
        }
        $orders = $orders->select([
            'orders.customer_id as customer_id',
            'orders.clinic_id as clinic_id',
            'order_details.modelable_id as service_id'
        ])
            ->distinct(['customer_id', 'clinic_id', 'service_id'])
            ->get()
            ->groupBy('service_id');
        $keys = [];
        foreach ($orders as $key => $order) {
            $service = Service::query()->find($key);
            $keys[] = [
                'services' => $service?->translateOrDefault(locale())?->name,
                'customers' => count($order),
            ];
        }
        $services = collect($keys)->pluck('services');
        $customers = collect($keys)->pluck('customers');
        $report->labels($services);
        $report->dataset(_trans('Count Customer'), ChartType::Bar->value, $customers)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1,
            ]);
        $report->options($chart->chartConfig());

        $clinics = [];
        if (\request()->routeIs('admin.report.count-customers-every-services-in-clinic')) {
            $clinics = Clinic::query()->orderByDesc('created_at')->get();
        }

        return view('admin.report.count-customers-every-services', compact('report', 'clinics'));
    }

    public function CountOrdersEveryService(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $stores = DB::table('orders')
            ->join('order_details', 'order_details.order_id', '=', 'orders.id')
            ->join('service_translations', 'order_details.modelable_id', '=', 'service_translations.service_id')
            ->where('order_details.modelable_type', 'LIKE', '%Service%')
            ->where('orders.order_status', '=', OrderStatus::completed->value);
        if ($request->name) {
            $stores = $stores->where('service_translations.name', 'LIKE', "%{$request->name}%")
                ->where('service_translations.locale', '=', locale());
        }
        if ($request->start && $request->end) {
            $stores = $stores->where('orders.created_at', '>=', formatDate('Y-m-d', $request->start))
                ->where('orders.created_at', '<=', formatDate('Y-m-d', $request->end));
        }
        $stores = $stores->select([
            'orders.customer_id as customer_id',
            'orders.clinic_id as clinic_id',
            'order_details.modelable_id as service_id'
        ])
            ->distinct(['customer_id', 'clinic_id', 'service_id'])
            ->get()
            ->groupBy('service_id');
        $keys = [];
        foreach ($stores as $key => $store) {
            $service = Service::query()->find($key);
            $keys[] = [
                'services' => $service?->translateOrDefault(locale())?->name,
                'customers' => count($store),
            ];
        }
        $services = collect($keys)->pluck('services');
        $customers = collect($keys)->pluck('customers');
        $report->labels($services);
        $report->dataset(_trans('Count Order Completed'), ChartType::Bar->value, $customers)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1,
            ]);
        $report->options($chart->chartConfig());

        return view('admin.report.count-orders-every-service', compact('report'));
    }

    public function CountCustomersEveryOfferClinic(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $stores = DB::table('orders')
            ->join('order_details', 'order_details.order_id', '=', 'orders.id')
            ->join('medical_offer_translations', 'order_details.modelable_id', '=', 'medical_offer_translations.medical_offer_id')
            ->where('order_details.modelable_type', 'LIKE', '%MedicalOffer%');
        if ($request->name) {
            $stores = $stores->where('medical_offer_translations.name', 'LIKE', "%{$request->name}%")
                ->where('medical_offer_translations.locale', '=', locale());
        }
        if ($request->start && $request->end) {
            $stores = $stores->where('orders.created_at', '>=', formatDate('Y-m-d', $request->start))
                ->where('orders.created_at', '<=', formatDate('Y-m-d', $request->end));
        }
        $stores = $stores->select([
            'orders.customer_id as customer_id',
            'orders.clinic_id as clinic_id',
            'order_details.modelable_id as medical_offer_id'
        ])
            ->distinct(['customer_id', 'clinic_id', 'medical_offer_id'])
            ->get()
            ->groupBy('medical_offer_id');
        $keys = [];
        foreach ($stores as $key => $store) {
            $medicalOffer = MedicalOffer::query()->find($key);
            $keys[] = [
                'medical_offers' => $medicalOffer?->translateOrDefault(locale())?->name,
                'customers' => count($store),
            ];
        }
        $medicalOffers = collect($keys)->pluck('medical_offers');
        $customers = collect($keys)->pluck('customers');
        $report->labels($medicalOffers);
        $report->dataset(_trans('Count Customer'), ChartType::Bar->value, $customers)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1,
            ]);
        $report->options($chart->chartConfig());

        return view('admin.report.count-customers-every-medical-offers', compact('report'));
    }

    public function CountCustomersEverySaveClinic(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $stores = DB::table('orders')
            ->join('order_details', 'order_details.order_id', '=', 'orders.id')
            ->join('special_offer_translations', 'order_details.modelable_id', '=', 'special_offer_translations.special_offer_id')
            ->where('order_details.modelable_type', 'LIKE', '%SpecialOffer%');
        if ($request->name) {
            $stores = $stores->where('special_offer_translations.name', 'LIKE', "%{$request->name}%")
                ->where('special_offer_translations.locale', '=', locale());
        }
        if ($request->start && $request->end) {
            $stores = $stores->where('orders.created_at', '>=', formatDate('Y-m-d', $request->start))
                ->where('orders.created_at', '<=', formatDate('Y-m-d', $request->end));
        }
        $stores = $stores->select([
            'orders.customer_id as customer_id',
            'orders.clinic_id as clinic_id',
            'order_details.modelable_id as special_offer_id',
        ])
            ->distinct(['customer_id', 'clinic_id', 'special_offer_id'])
            ->get()
            ->groupBy('special_offer_id');
        $keys = [];
        foreach ($stores as $key => $store) {
            $special_offer = SpecialOffer::query()->find($key);
            $keys[] = [
                'special_offers' => $special_offer?->translateOrDefault(locale())?->name,
                'customers' => count($store),
            ];
        }
        $special_offers = collect($keys)->pluck('special_offers');
        $customers = collect($keys)->pluck('customers');
        $report->labels($special_offers);
        $report->dataset(_trans('Count Customer'), ChartType::Bar->value, $customers)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1,
            ]);
        $report->options($chart->chartConfig());

        return view('admin.report.count-customers-every-special-offer', compact('report'));
    }

    public function CountCustomersEveryDiscountClinic(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $stores = DB::table('orders')
            ->join('order_details', 'order_details.order_id', '=', 'orders.id')
            ->join('discounts', 'order_details.modelable_id', '=', 'discounts.id')
            ->where('order_details.modelable_type', 'LIKE', '%Discount%');
        if ($request->name) {
            $stores = $stores->join('service_translations', 'discounts.service_id', '=', 'service_translations.service_id')
                ->where('service_translations.name', 'LIKE', "%{$request->name}%")
                ->where('service_translations.locale', '=', locale());
        }
        if ($request->start && $request->end) {
            $stores = $stores->where('orders.created_at', '>=', formatDate('Y-m-d', $request->start))
                ->where('orders.created_at', '<=', formatDate('Y-m-d', $request->end));
        }
        $stores = $stores->select([
            'orders.customer_id as customer_id',
            'orders.clinic_id as clinic_id',
            'order_details.modelable_id as discount_id',
        ])
            ->distinct(['customer_id', 'clinic_id', 'discount_id'])
            ->get()
            ->groupBy('discount_id');
        $keys = [];
        foreach ($stores as $key => $store) {
            $discount = Discount::query()->with(['service'])->find($key);
            $keys[] = [
                'discounts' => $discount?->service?->translateOrDefault(locale())?->name,
                'customers' => count($store),
            ];
        }
        $discounts = collect($keys)->pluck('discounts');
        $customers = collect($keys)->pluck('customers');
        $report->labels($discounts);
        $report->dataset(_trans('Count Customer'), ChartType::Bar->value, $customers)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1,
            ]);
        $report->options($chart->chartConfig());

        return view('admin.report.count-customers-every-discounts', compact('report'));
    }

    public function RatingClinicsEveryGovernorate(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $countries = Country::query()->has('governorates')->get();
        $rates = Rate::query()
            ->select(['rate_type', 'rate_id'])
            ->where('rate_type', '=', Clinic::class)
            ->when($request->country_id && $request->governorate_id, function (Builder $query) use ($request) {
                $query->whereHasMorph('rate', [Clinic::class], function (Builder $query) use ($request) {
                    $query->where('country_id', '=', $request->country_id)
                        ->where('governorate_id', '=', $request->governorate_id);
                });
            })
            ->when($request->start && $request->end, function ($query) use ($request) {
                $query->where('created_at', '>=', formatDate('Y-m-d', $request->start))
                    ->where('created_at', '<=', formatDate('Y-m-d', $request->end));
            })
            ->get()
            ->groupBy('rate_id');
        $keys = [];
        foreach ($rates as $key => $rate) {
            $clinic = Clinic::query()->find($key);
            $keys[] = [
                'clinic' => $clinic->translateOrDefault(locale())?->name,
                'counts' => $rate->count(),
            ];
        }
        $names = collect($keys)->pluck('clinic');
        $counts = collect($keys)->pluck('counts');
        $report->labels($names);
        $report->dataset(_trans('Count Rates'), ChartType::Bar->value, $counts)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1
            ]);
        $report->options($chart->chartConfig());
        return view('admin.report.rating-clinics-every-governorate', compact('report', 'countries'));

    }

    public function RatingServicesEveryGovernorate(Request $request)
    {
        $report = new ClinicChart();
        $chart = new Chart();
        $clinics = $countries = [];
        if (request()->routeIs('admin.report.services-highest-rated-clinics')) {
            $clinics = Clinic::query()->orderByDesc('created_at')->get();
        } else {
            $countries = Country::query()->has('governorates')->get();
        }
        $rates = Rate::query()
            ->select(['rate_type', 'rate_id'])
            ->where('rate_type', '=', Service::class)
            ->when($request->country_id && $request->governorate_id, function (Builder $query) use ($request) {
                $query->whereHasMorph('rate', [Service::class], function (Builder $query) use ($request) {
                    $query->whereHas('clinic', function (Builder $query) use ($request) {
                        $query->where('country_id', '=', $request->country_id)
                            ->where('governorate_id', '=', $request->governorate_id);
                    });
                });
            })
            ->when($request->service_name, function ($query) use ($request) {
                $query->whereHasMorph('rate', [Service::class], function (Builder $query) use ($request) {
                    $query->whereTranslationLike('name', "%{$request->service_name}%", default_lang());
                });
            })
            ->when($request->clinic_id, function ($query) use ($request) {
                $query->whereHasMorph('rate', [Service::class], function (Builder $query) use ($request) {
                    $query->where('clinic_id', '=', $request->clinic_id);
                });
            })
            ->when($request->start && $request->end, function ($query) use ($request) {
                $query->where('created_at', '>=', formatDate('Y-m-d', $request->start))
                    ->where('created_at', '<=', formatDate('Y-m-d', $request->end));
            })
            ->get()
            ->groupBy('rate_id');
        $keys = [];
        foreach ($rates as $key => $rate) {
            $service = Service::query()->find($key);
            $keys[] = [
                'services' => $service->translateOrDefault(locale())?->name,
                'counts' => $rate->count(),
            ];
        }
        $services = collect($keys)->pluck('services');
        $counts = collect($keys)->pluck('counts');
        $report->labels($services);
        $chartType = ChartType::Bar->value;
        if (request()->routeIs('admin.report.services-highest-rated-clinics')) {
            $chartType = ChartType::Line->value;
        }
        $report->dataset(_trans('Count Rates'), $chartType, $counts)
            ->options([
                'borderColor' => Arr::random($chart->borderColor()),
                'borderWidth' => $chart->borderWidth(2),
                'backgroundColor' => Arr::random($chart->backgroundColor()),
                'fill' => true,
                'tension' => 0.1
            ]);
        $report->options($chart->chartConfig());
        return view('admin.report.rating-services-every-governorate', compact('report', 'countries', 'clinics'));

    }
}
