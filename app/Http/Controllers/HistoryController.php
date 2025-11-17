<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        return view('history.index');
    }

    public function detail()
    {
        return view('history.detail');
    }
    public function store()
    {
        
    }
    public function edit()
    {
        return view('history.edit');
    }
    public function destroy()
    {
        return view('history.destroy');
    }
    public function show()
    {
        return view('history.show');
    }
    public function update()
    {
        return view('history.update');
    }
    public function create()
    {
        return view('history.create');
    }
}
