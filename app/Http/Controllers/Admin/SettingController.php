<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings', [
            'settings' => Setting::pluck('deger', 'anahtar')->toArray(),
        ]);
    }

    public function update(Request $request)
    {
        $fields = $request->except(['_token']);
        foreach ($fields as $key => $value) {
            Setting::put($key, $value);
        }
        Setting::flush();

        return back()->with('success', 'Ayarlar kaydedildi.');
    }
}
