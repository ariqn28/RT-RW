<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanStatusHistory;
use Illuminate\Http\Request;

class MobileRiwayatController extends Controller
{
    public function index(Request $request)
    {
        $histories = PengajuanStatusHistory::whereHas('pengajuan', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->latest()
            ->with(['pengajuan', 'changedBy'])
            ->paginate(15);

        return response()->json([
            'data' => $histories->items(),
            'pagination' => [
                'total' => $histories->total(),
                'per_page' => $histories->perPage(),
                'current_page' => $histories->currentPage(),
                'last_page' => $histories->lastPage(),
            ],
        ]);
    }
}

