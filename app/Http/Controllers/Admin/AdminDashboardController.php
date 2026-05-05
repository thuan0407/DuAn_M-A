<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Deal;
use App\Models\User;

class AdminDashboardController extends Controller
{

public function index()
{
    $companies = Company::count();
    $deals = Deal::count();
    $users = User::count();

    return view('admin.dashboard', compact('companies', 'deals', 'users'));
}
}
