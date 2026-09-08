<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use App\Models\Due;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function index()
    {
        $paymentRequests = PaymentRequest::where('user_id', auth()->id())
            ->whereIn('due_id', Due::where('is_active', true)->pluck('id'))
            ->get()
            ->keyBy('due_id');

        return view('warga.iuran', [
            'dues' => Due::where('is_active', true)->latest('due_date')->latest()->get(),
            'contact' => ContactSetting::firstOrCreate(['id' => 1]),
            'paymentRequests' => $paymentRequests,
        ]);
    }

    public function choosePaymentMethod(Request $request, Due $due)
    {
        $data = $request->validate([
            'payment_method' => ['required', 'in:qris,cash,transfer'],
        ]);

        abort_unless($due->is_active, 404);

        if (! in_array($data['payment_method'], $due->payment_methods ?? [], true)) {
            return back()->withErrors(['payment_method' => 'Metode pembayaran tersebut tidak tersedia untuk iuran ini.']);
        }

        $paymentRequest = PaymentRequest::updateOrCreate(
            ['due_id' => $due->id, 'user_id' => auth()->id()],
            ['payment_method' => $data['payment_method'], 'status' => 'menunggu']
        );

        return redirect()->route('iuran.invoice', $paymentRequest)->with('success', 'Invoice pembayaran berhasil dibuat.');
    }

    public function invoice(PaymentRequest $paymentRequest)
    {
        abort_unless($paymentRequest->user_id === auth()->id(), 403);

        return view('warga.invoice', [
            'paymentRequest' => $paymentRequest->load('due', 'user'),
        ]);
    }
}
