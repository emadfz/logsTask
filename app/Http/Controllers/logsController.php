<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logs;
use Illuminate\Support\Facades\DB;

class logsController extends Controller
{

    public function uniqueVisitors()
    {
        $logs = Logs::distinct('client')->orderby('client')->pluck('client');

        return response()->json($logs);
    }

    public function visitCount()
    {
        $count = DB::table('logs')
            ->select('client', DB::raw('count(*) as hits'))
            ->groupBy('client')
            ->get();
        return response()->json($count);
    }
    public function topHits()
    {
        $count = DB::table('logs')
            ->select('client', DB::raw('count(*) as hits'))
            ->groupBy('client')
            ->orderBy('hits','desc')
            ->get();
        return response()->json($count);
    }
}
