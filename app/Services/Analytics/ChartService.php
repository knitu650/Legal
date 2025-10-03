<?php

namespace App\Services\Analytics;

class ChartService
{
    public function generateChart($type, $data)
    {
        return [
            'type' => $type,
            'data' => $data,
            'options' => $this->getDefaultOptions($type)
        ];
    }
    
    public function lineChart($labels, $datasets)
    {
        return [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets
            ],
            'options' => $this->getDefaultOptions('line')
        ];
    }
    
    public function barChart($labels, $datasets)
    {
        return [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets
            ],
            'options' => $this->getDefaultOptions('bar')
        ];
    }
    
    public function pieChart($labels, $data)
    {
        return [
            'type' => 'pie',
            'data' => [
                'labels' => $labels,
                'datasets' => [['data' => $data]]
            ],
            'options' => $this->getDefaultOptions('pie')
        ];
    }
    
    protected function getDefaultOptions($type)
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => ['display' => true],
                'tooltip' => ['enabled' => true]
            ]
        ];
    }
}
