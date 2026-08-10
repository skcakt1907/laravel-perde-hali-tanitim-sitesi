<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        return view('admin.messages', [
            'messages' => ContactMessage::latest()->paginate(20),
        ]);
    }

    public function update(ContactMessage $message)
    {
        // read_at korumalı alan; explicit atanır.
        $message->read_at = $message->read_at ? null : now();
        $message->save();

        return back()->with('success', 'Mesaj durumu güncellendi.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return back()->with('success', 'Mesaj silindi.');
    }
}
