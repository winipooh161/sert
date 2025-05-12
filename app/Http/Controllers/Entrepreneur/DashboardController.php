<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Создание нового экземпляра контроллера.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:predprinimatel']);
    }

    /**
     * Отображение панели предпринимателя.
     */
    public function index()
    {
        $user = Auth::user();
        
        $totalCertificates = $user->certificates()->count();
        $activeCertificates = $user->certificates()->where('status', 'active')->count();
        $expiredCertificates = $user->certificates()->where('status', 'expired')->count();
        $usedCertificates = $user->certificates()->where('status', 'used')->count();
        
        $totalAmount = $user->certificates()
            ->where('status', '!=', 'canceled')
            ->sum('amount');
            
        $recentCertificates = $user->certificates()
            ->with('template')
            ->latest()
            ->take(5)
            ->get();

        return view('entrepreneur.dashboard', compact(
            'totalCertificates',
            'activeCertificates',
            'expiredCertificates',
            'usedCertificates',
            'totalAmount',
            'recentCertificates'
        ));
    }
}
