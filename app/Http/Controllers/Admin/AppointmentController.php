<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Ücretsiz ölçü talepleri */
class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('durum');

        return view('admin.appointments', [
            'appointments' => Appointment::when(
                    $status && isset(Appointment::DURUMLAR[$status]),
                    fn ($q) => $q->where('status', $status)
                )
                ->latest()->paginate(20)->withQueryString(),
            'status' => $status,
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Appointment::DURUMLAR))],
        ]);

        // status korumalı alan; admin enum-doğrulamalı olarak forceFill ile yazar.
        $appointment->forceFill($data)->save();

        return back()->with('success', 'Talep durumu güncellendi.');
    }
}
