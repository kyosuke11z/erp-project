<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use PDF;

class FinancialReportController extends Controller
{
    /**
     * สร้างและสตรีมไฟล์ PDF ของรายงานการเงิน
     */
    public function exportPdf(Request $request)
    {
        // ตรวจสอบความถูกต้องของข้อมูลที่ส่งมา
        $validated = $request->validate([
            'startDate' => ['required', 'date'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
        ]);

        $startDate = $validated['startDate'];
        $endDate = $validated['endDate'];

        // ใช้ Logic เดิมในการดึงและประมวลผลข้อมูล
        $query = FinancialTransaction::query()->whereBetween('transaction_date', [$startDate, $endDate]);

        $data = [
            'transactions' => (clone $query)->latest('transaction_date')->get(),
            'totalIncome'  => (clone $query)->where('type', 'income')->sum('amount'),
            'totalExpense' => (clone $query)->where('type', 'expense')->sum('amount'),
            'startDate'    => $startDate,
            'endDate'      => $endDate,
        ];
        $data['netBalance'] = $data['totalIncome'] - $data['totalExpense'];

        $pdf = PDF::loadView('pdf.financial-report', $data);
        $fileName = 'financial-report-' . now()->format('Y-m-d') . '.pdf';

        // สตรีมไฟล์ PDF เพื่อแสดงผลในเบราว์เซอร์
        return $pdf->stream($fileName);
    }
}