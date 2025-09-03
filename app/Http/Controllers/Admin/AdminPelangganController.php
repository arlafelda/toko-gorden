<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $pelanggan = User::when($query, function ($qbuilder) use ($query) {
                $qbuilder->where(function ($subquery) use ($query) {
                    $subquery->where('name', 'like', '%' . $query . '%')
                             ->orWhere('address', 'like', '%' . $query . '%');
                });
            })
            ->with('orders')
            ->get();

        return view('Admin.DaftarPelanggan', compact('pelanggan'));
    }
}
