<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// We don't even need Http::get anymore for this
// use Illuminate\Support\Facades\Http; 

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        // Get filters from the user's request, with defaults
        $filters = [
            'date' => $request->query('date', now()->format('Y-m-d')),
            'time_start' => $request->query('time_start', '14:00'),
            'time_end' => $request->query('time_end', '22:00'),
            'machine' => $request->query('machine', 'RIA10'),
        ];
        
        // --- THIS IS THE FIX ---
        // Instead of making an HTTP call, just call our local function.
        $lossEvents = $this->getMockLossEvents($filters);

        // Now, we send this data to our new view
        return view('dashboard', [
            'lossEvents' => $lossEvents,
            'filters' => $filters,
        ]);
    }

    /**
     * This is our new private function containing the mock data.
     * It's the same logic you had in routes/api.php.
     */
    private function getMockLossEvents(array $filters): array
    {
        // You can use the filters if you want, e.g.
        // $dateFilter = $filters['date'];
        
        $data = [
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
                "notes" => "Operator input", // This is a manual entry
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
                "notes" => "Auto populated by system", // This is an auto-entry
            ],
        ];

        return $data;
    }
}