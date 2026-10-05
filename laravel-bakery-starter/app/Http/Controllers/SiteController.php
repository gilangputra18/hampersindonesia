<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        $c = config('site.categories');
        return view('home', [
            'gifts'  => ['hampers', 'cakes', 'cookies', 'breads'],
            'treats' => $c['light-bites']['items'],
            'best'   => array_slice($c['cakes']['items'], 0, 5),
        ]);
    }

    public function category(string $slug)
    {
        $cat = config("site.categories.$slug") ?? abort(404);
        return view('category', compact('slug', 'cat'));
    }

    public function reservations() { return view('reservations'); }
    public function contact()      { return view('contact'); }

    public function sendContact(Request $r)
    {
        $r->validate([
            'name' => 'required|max:80',
            'email' => 'required|email',
            'message' => 'required|max:2000',
        ]);
        // TODO: kirim email (Mail::to(config('site.email'))->send(...)) atau simpan ke database
        return back()->with('ok', 'Thank you! We will be in touch as soon as possible.');
    }

    public function track(Request $r)
    {
        // TODO: sambungkan ke database/API pesanan
        return view('track', ['invoice' => $r->query('invoice')]);
    }
}
