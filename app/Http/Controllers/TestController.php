<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function createFlashMessage($isSuccess)
    {
        if($isSuccess) {
            return back()
                ->with('success', 'This is success message');

            /*
            return redirect()
                ->route('dashboard')
                ->with('success', 'This is success message');
             */
        } else {
            return back()
                ->with('error', 'This is error message');

            /*
            return redirect()
                ->route('dashboard')
                ->with('error', 'This is error message');
             */
        }
    }
}
