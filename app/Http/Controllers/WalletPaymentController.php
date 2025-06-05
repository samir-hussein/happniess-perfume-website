<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletPaymentController extends Controller
{
    public function showQrCode(Request $request)
    {
        if (!$request->has('order')) {
            return redirect()->route('home', app()->getLocale());
        }

        try {
            $order = json_decode(decrypt(request('order')));
            // Get invoice details to display on the page
            return view('wallet-payment', [
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home', app()->getLocale())->with('error', $e->getMessage());
        }
    }
}
