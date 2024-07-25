<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Libs\Libs\WannadsCallback;

class OfferController extends Controller
{
    public function callback(Request $request, $offer_name)
    {
        switch($offer_name) {
            case 'wannads':
                $callback = new WannadsCallback($request->all());

                break;
        }

        /*
"OK" when you receive a new transaction.
"DUP" when you receive a duplicate transaction. In this case, our server will stop further attempts for that transaction.
         */

        echo 'OK';
    }
}
