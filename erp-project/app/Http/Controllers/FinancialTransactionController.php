<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialTransactionController extends Controller
{
    /**
     * คอมเมนต์: แสดงหน้าหลักของระบบการเงิน ซึ่งจะโหลด Livewire component
     *
     * @return View
     */
    public function index(): View
    {
        return view('finance.index');
    }
}