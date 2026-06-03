<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        $stats = [];

        if ($role === 'admin') {
            $stats = $this->dashboardService->getAdminStats();
        } elseif ($role === 'bendahara') {
            $stats = $this->dashboardService->getBendaharaStats();
        } elseif ($role === 'kepala sekolah') {
            $stats = $this->dashboardService->getKepsekStats();
        }

        $tingkatList = \Illuminate\Support\Facades\DB::table('kelas')->distinct()->orderBy('tingkat')->pluck('tingkat');
        $kelasList = \Illuminate\Support\Facades\DB::table('kelas')->orderBy('nama')->get();

        $activeTahunAjaran = \Illuminate\Support\Facades\DB::table('tarif_spp')->max('tahun_ajaran');
        if (!$activeTahunAjaran) {
            $currentYear = date('Y');
            $currentMonth = date('n');
            if ($currentMonth >= 7) {
                $activeTahunAjaran = $currentYear . '/' . ($currentYear + 1);
            } else {
                $activeTahunAjaran = ($currentYear - 1) . '/' . $currentYear;
            }
        }

        return view('main.dashboard.index', compact('stats', 'role', 'user', 'tingkatList', 'kelasList', 'activeTahunAjaran'));
    }

    public function getChartData(Request $request)
    {
        $year = $request->year ?? date('Y');
        $tingkat = $request->tingkat;
        $kelasId = $request->kelas_id;

        $data = $this->dashboardService->getIncomeChartData(
            (int)$year, 
            $tingkat ? (int)$tingkat : null, 
            $kelasId ? (int)$kelasId : null
        );

        return response()->json([
            'year' => $year,
            'data' => $data,
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
        ]);
    }
}
