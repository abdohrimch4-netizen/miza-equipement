<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    // هاد الشرط كيقول: إيلا كان الإيميل ديال لي مكونيكطي ماشي هو ديال الأدمن، رجعو للصفحة الرئيسية
    if (auth()->user()->email !== 'admin@miza.com') {
        return redirect('/')->with('error', 'Accès refusé ! Vous n\'êtes pas administrateur.');
    }

    // هنا غتخلي الكود ديالك القديم كيفما كان...
    // مثلا:
    return view('admin.dashboard'); 
}
}