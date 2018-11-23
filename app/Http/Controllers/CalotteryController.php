<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CalotteryExport;
use App\Account;

class CalotteryController extends Controller
{
    public function index()
    {
        \Log::info("Home fail");
        return null;
    	// return view("calottery");

    }

    public function getCsv(Request $request)
    {
    	$fromDate = $request->fromDate;
    	$toDate = $request->toDate;
        $fromId = $request->fromId;
        $toId = $request->toId;
    	$color = $request->color;

        \Log::info("Dowload clicked");
        dd("Service is maintenance. Please try again later!");

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

    public function showLogin()
    {
        \Log::info("Login");
        return view("login");
    }

    public function login(Request $request)
    {

        \Log::info("Login fail");

        $username = $request->username;
        $password = $request->password;

        $account = Account::where("username", $username)->where("password", $password)->first();
        if ($account) {
            session(['is_login' => '1']);
            return redirect()->route("csv.index");
        } else {
            return view("login", [
                'message' => "Sai username hoặc password",
            ]);
        }
    }

    public function showChangePassword()
    {
        return view("change_password");
    }

    public function changePassword(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $newPassword = $request->newPassword;
        $confirmPassword = $request->confirmPassword;
        $account = Account::where("username", $username)->where("password", $password)->first();
        if (!$account) {
            return view("change_password",  [
                'message' => "Sai username hoặc password",
            ]);
        }
        if ($confirmPassword != $newPassword) {
            return view("change_password",  [
                'message' => "confirmPassword không trùng với newPassword",
            ]);            
        }
        $account->password = $newPassword;
        $account->save();
        session(['is_login' => '0']);
        return redirect()->route("login.show");
    }
}



