<?php

namespace App\Helpers\Chart;

class Chart
{

    public function chartConfig(): array
    {
        return [
            [
                'scales' => [
                    'xAxes' => [
                        'gridLines' => [
                            'display' => false
                        ],
                        'ticks' => [
                            'fontColor' => '#aaa'
                        ]
                    ],
                    'yAxes' => [
                        'gridLines' => [
                            'display' => false
                        ],
                        'ticks' => [
                            'fontColor' => '#aaa',
                            'stepSize' => 10
                        ]
                    ]
                ],
                'responsive' => true,
                'maintainAspectRatio' => false,
                'animations' => [
                    'tension' => [
                        'duration' => 1000,
                        'easing' => 'linear',
                        'from' => 1,
                        'to' => 0,
                        'loop' => 1,
                    ]
                ]
            ]
        ];
    }

    public function backgroundColor(array $colors = []): array
    {
        return array_merge($colors, [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)',
        ]);
    }

    public function borderColor(array $colors = []): array
    {
        return array_merge($colors, [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)',
        ]);
    }

    public function borderWidth(float $width = 1): float|int
    {
        return $width;
    }
}
