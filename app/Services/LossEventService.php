<?php

namespace App\Services;

class LossEventService
{
    public function getLossEvents($date, $machine)
    {
        return [
            [
                "time_range" => "7.00-8.00",
                "event" => "MP1-MB1",
                "status" => "Run",
                "batch" => "12500xxxx",
                "sku" => "Mix White Choco BW",
                "production" => 6000,
                "duration" => "60 menit",
                "loss_lv1" => null,
                "loss_lv2" => null,
                "loss_lv3" => null,
                "notes" => null,
            ],
            [
                "time_range" => "8.00-8.15",
                "event" => "MP1-Recirculation",
                "status" => "Stop",
                "batch" => "12500xxxx",
                "sku" => "Mix White Choco BW",
                "production" => 0,
                "duration" => "15 menit",
                "loss_lv1" => null,
                "loss_lv2" => null,
                "loss_lv3" => null,
                "notes" => null,
            ],
            [
                "time_range" => "8.15-8.30",
                "event" => "MP1-MB1",
                "status" => "Run",
                "batch" => "12500xxxx",
                "sku" => "Mix White Choco BW",
                "production" => 2000,
                "duration" => "15 menit",
                "loss_lv1" => null,
                "loss_lv2" => null,
                "loss_lv3" => null,
                "notes" => null,
            ],
            [
                "time_range" => "8.30-8.50",
                "event" => "MP1-MB1",
                "status" => "Stop",
                "batch" => "12500xxxx",
                "sku" => "Mix White Choco BW",
                "production" => 0,
                "duration" => "20 menit",
                "loss_lv1" => "PDL",
                "loss_lv2" => "Cleaning & Sanitasi",
                "loss_lv3" => "Water Purge",
                "notes" => null,
            ],
        ];
    }
}
