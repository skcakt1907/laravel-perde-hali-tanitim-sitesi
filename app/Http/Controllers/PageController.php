<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Mail\AufmassRequested;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about', [
            'services'     => Service::active()->orderBy('sira')->get(),
            'testimonials' => Testimonial::active()->latest()->take(3)->get(),
        ]);
    }

    public function services()
    {
        return view('pages.services', ['services' => Service::active()->orderBy('sira')->get()]);
    }

    public function serviceShow(Service $service)
    {
        abort_unless($service->durum, 404);

        return view('pages.service-show', [
            'service' => $service,
            'others'  => Service::active()->where('id', '<>', $service->id)->orderBy('sira')->take(6)->get(),
        ]);
    }

    public function blog()
    {
        return view('pages.blog', ['posts' => Post::active()->latest('tarih')->paginate(9)]);
    }

    public function blogShow(Post $post)
    {
        abort_unless($post->durum, 404);

        return view('pages.blog-show', [
            'post'   => $post,
            'others' => Post::active()->where('id', '<>', $post->id)->latest('tarih')->take(4)->get(),
        ]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'nullable|email|max:120',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:160',
            'message' => 'required|string|max:2000',
            'privacy' => 'accepted',
        ]);

        unset($data['privacy']);
        $data['locale'] = app()->getLocale();

        $message = ContactMessage::create($data);

        try {
            if ($adminMail = setting('eposta')) {
                Mail::to($adminMail)->send(new ContactMessageMail($message));
            }
        } catch (\Throwable $e) {
            Log::error('İletişim maili gönderilemedi', ['err' => $e->getMessage()]);
        }

        return back()->with('success', __('site.contact.sent'));
    }

    /** Kostenloses Aufmaß & Beratung formu */
    public function aufmass()
    {
        return view('pages.aufmass', [
            'categories' => Category::active()->whereNull('parent_id')->orderBy('sira')->get(),
        ]);
    }

    public function aufmassStore(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'phone'   => 'required|string|max:30',
            'email'   => 'nullable|email|max:120',
            'subject' => 'nullable|string|max:160',
            'zip'     => 'nullable|string|max:16',
            'city'    => 'nullable|string|max:80',
            'address' => 'nullable|string|max:255',
            'date'    => 'nullable|date',
            'time'    => 'nullable|string|max:20',
            'note'    => 'nullable|string|max:1500',
            'privacy' => 'accepted',
        ]);

        unset($data['privacy']);
        $data['locale'] = app()->getLocale();

        $appointment = Appointment::create($data);

        try {
            if ($adminMail = setting('eposta')) {
                Mail::to($adminMail)->send(new AufmassRequested($appointment));
            }
        } catch (\Throwable $e) {
            Log::error('Aufmaß bildirim maili gönderilemedi', ['err' => $e->getMessage()]);
        }

        return back()->with('success', __('site.aufmass.sent'));
    }
}
