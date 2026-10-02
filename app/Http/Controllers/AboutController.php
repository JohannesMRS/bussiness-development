<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        $number = User::normalizeWhatsappNumber(config('bizdev.admin_whatsapp'));
        $adminWhatsappUrl = $number === null || $number === ''
            ? null
            : 'https://wa.me/'.$number;
        $isDemoContact = config('bizdev.is_demo_contact');

        return view('public.about', compact('adminWhatsappUrl', 'isDemoContact'));
    }
}
