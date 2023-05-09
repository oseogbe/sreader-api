<?php

namespace App\Http\Controllers\Activation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SingleActivation extends Controller
{
    public function __invoke(Request $request, $user)
    {
        // select class


        // make payment or enter activation code


        // confirm payment or validate activation code


        // activate student?
        $user->update(['status' => 'active', 'activated_at' => now()]);

        // return response
        return response([
            'success' => true,
            'message' => 'Account successfully activated!'
        ]);

    }
}
