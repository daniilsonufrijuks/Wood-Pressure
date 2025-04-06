<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use Illuminate\Http\Request;

class BeamController extends Controller
{
    public function calculate(Request $request)
    {
        $B = $request->input('B');
        $H = $request->input('H');
        $f = $request->input('f');
        $L = $request->input('L');

        $W = ($B * pow($H, 2)) / 6;
        $q = (8 * $W * $f) / pow($L, 2);

        Calculation::create([
            'B' => $B,
            'H' => $H,
            'f' => $f,
            'L' => $L,
            'W' => $W,
            'q' => $q,
        ]);

        return response()->json([
            'W' => round($W, 2),
            'q' => round($q, 2),
        ]);
    }
}
