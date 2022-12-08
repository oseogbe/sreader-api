<?php

namespace App\Http\Controllers\Activation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SingleActivation extends Controller
{
    public function __invoke(Request $request, User $student)
    {
        // select class


        // make payment or enter activation code


        // confirm payment or validate activation code


        // activate student?
        $student->update(['status' => 'active', 'activated_at' => now()]);

        // return response
        return response([
            'success' => true,
            'message' => 'Account successfully activated!'
        ]);

    }
}
