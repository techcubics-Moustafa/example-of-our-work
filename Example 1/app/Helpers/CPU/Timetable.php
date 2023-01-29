<?php

namespace App\Helpers\CPU;

use App\Enums\OrderStatus;
use App\Models\OrderDetail;

class Timetable
{

    public static function booking($workingHours, $data, $type = 'order', $request = null)
    {
        $orders = [];

        foreach ($workingHours as $workingHour) {
            $orderDetails = OrderDetail::query()->whereHas('modelable',function ($query) use($workingHour){
                $query->where('clinic_id',$workingHour->modelable_id);
            })->whereHas('order',function ($query) use($workingHour){
                $query->whereNotIn('order_status',[OrderStatus::canceled->value,OrderStatus::completed->value]);
            })->get();
            foreach ($orderDetails as $order) {
                if ($order->order->order_status != OrderStatus::canceled->value) {
                    $orders[] = [
                        'clinic_id' => $order->order->clinic_id,
                        'service_type_id' => $workingHour->serviceType->id,
                        'month_name' => $order->attributes_info['month_id'] . '/' . $order->attributes_info['year'],
                        'month_id' => $order->attributes_info['month_id'],
                        'year' => $order->attributes_info['year'],
                        'day_id' => $order->attributes_info['day_id'],
                        'from' => $order->attributes_info['from'],
                        'to' => $order->attributes_info['to'],
                    ];
                }
            }
        }

        // check if date is booking or not
        if ($type == 'order') {
            if ($request->month_id >= 10) {
                $formatMonth = "{$request->month_id}";
            } else {
                $formatMonth = "0{$request->month_id}";
            }
            foreach ($orders as $order) {
                if ($request['service_type_id'] == $order['service_type_id']) {
                    if ($formatMonth == $order['month_name']) {
                        if ($request['day_id'] == $order['day_id']) {
                            if (formatDate('H:i', $request['from']) == formatDate('H:i', $order['from'])
                                && formatDate('H:i', $request['to']) == formatDate('H:i', $order['to'])) {
                                return -1;
                            }
                        }
                    }
                }
                /* foreach ($data as $row) {
                     if ($row['service_type_id'] == $order['service_type_id']) {
                         foreach ($row['schedule'] as $schedule) {
                             if ($schedule['month'] == $order['month_name']) {
                                 foreach ($schedule['opening_hours'] as $open) {
                                     if ($open['day_id'] == $order['day_id']) {
                                         foreach ($open['times'] as $time) {
                                             if (formatDate('H:i', $time['from']) == formatDate('H:i', $order['from'])
                                                 && formatDate('H:i', $time['to']) == formatDate('H:i', $order['to'])) {
                                                 return -1;
                                             }
                                         }
                                     }

                                 }
                             }
                         }
                     }
                 }*/
            }
            return null;
        }

        foreach ($data as $row) {
            // check if date is avaibale or not
            switch ($type) {
                case 'time':
                {
                    foreach ($row['schedule'] as $schedule) {
                        foreach ($schedule['opening_hours'] as $open) {
                            foreach ($open['times'] as $time) {
                                if (formatDate('H:i', $time['to']) == now()->format('H:i')) {
                                    if (now()->format('H:i') > formatDate('H:i', $request['to'])) {
                                        return 1;
                                    }
                                }
                            }
                        }
                    }
                    return null;
                }
                case 'request':
                {
                    if ($request->month_id >= 10) {
                        $formatMonth = "{$request->month_id}";
                    } else {
                        $formatMonth = "0{$request->month_id}";
                    }
                    if ($row['service_type_id'] == $request->service_type_id) {
                        foreach ($row['schedule'] as $schedule) {
                            if ($schedule['month'] == "{$formatMonth}/{$request->year}") {
                                foreach ($schedule['opening_hours'] as $open) {
                                    if ($open['day_id'] == $request->day_id) {
                                        foreach ($open['times'] as $time) {
                                            if (formatDate('H:i', $time['from']) == formatDate('H:i', $request->from)
                                                && formatDate('H:i', $time['to']) == formatDate('H:i', $request->to)) {
                                                return 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    return null;
                }
            }
        }
    }
}
