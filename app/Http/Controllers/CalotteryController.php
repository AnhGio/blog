<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CalotteryExport;

class CalotteryController extends Controller
{
    public function index()
    {
    	return view("calottery");
    }

    public function getCsv(Request $request)
    {
    	$fromDate = $request->fromDate;
    	$toDate = $request->toDate;
        $fromId = $request->fromId;
        $toId = $request->toId;
    	$color = $request->color;

        if ($color == 1) {
    	    return (new CalotteryExport)->forYear($fromDate, $toDate)
                                        ->forId($fromId, $toId)
                                        ->forColor($color)
                                        ->download('blue_numbers.xlsx');
        } else {
            return (new CalotteryExport)->forYear($fromDate, $toDate)
                                        ->forId($fromId, $toId)
                                        ->forColor($color)
                                        ->download('red_numbers.xlsx');
        }
    }
}
