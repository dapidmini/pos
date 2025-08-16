<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function index()
    {
        $data = Customer::all();

        return view('customer.index', compact('data'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }
}
