<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ContactMessage;
use App\Models\Product;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'apptNew'      => Appointment::where('status', 'yeni')->count(),
            'apptTotal'    => Appointment::count(),
            'apptWon'      => Appointment::where('status', 'kazanildi')->count(),
            'messageNew'   => ContactMessage::unread()->count(),
            'productCount' => Product::count(),
            'projectCount' => Project::count(),
            'recentAppts'  => Appointment::latest()->take(8)->get(),
            'recentMsgs'   => ContactMessage::latest()->take(5)->get(),
        ]);
    }
}
