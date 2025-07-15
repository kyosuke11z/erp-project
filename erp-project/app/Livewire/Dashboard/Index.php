<?php

namespace App\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\FinancialTransaction;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    // คุณสมบัติสำหรับเก็บข้อมูลสรุป (Stat Cards)
    public $totalRevenue = 0;
    public $salesToday = 0;
    public $totalCustomers = 0;
    public $lowStockProductsCount = 0;
    public $monthlyIncome = 0;
    public $monthlyExpense = 0;
    public $monthlyNetBalance = 0;

    // คุณสมบัติสำหรับเก็บข้อมูลในตารางและรายการ
    public $recentSales;
    public $bestSellingProducts;

    // ข้อมูลสำหรับกราฟ (ต้องกำหนดค่าเริ่มต้นเป็น array فاضي)
    public array $monthlySalesData = ['labels' => [], 'data' => []];
    public array $bestSellingProductsChartData = ['labels' => [], 'data' => [], 'percentages' => []];

    // ตัวแปรสำหรับเก็บค่าช่วงวันที่ที่เลือก (ค่าเริ่มต้น: เดือนนี้)
    public $dateRange = 'this_month';

    public function __construct()
    {
        // กำหนดค่าเริ่มต้นให้เป็น Collection ว่าง เพื่อป้องกัน Error
        $this->recentSales = collect();
        $this->bestSellingProducts = collect();
    }

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // กำหนดช่วงวันที่เริ่มต้นและสิ้นสุดตามตัวเลือก
        $endDate = now()->endOfDay();
        $startDate = match ($this->dateRange) {
            '7_days' => now()->subDays(6)->startOfDay(), // แก้ไขให้รวมวันนี้ด้วยเป็น 7 วัน
            'this_month' => now()->startOfMonth(),
            'this_year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        // --- ข้อมูลสรุปจากโมดูลการเงิน (Finance) ---
        if (class_exists(FinancialTransaction::class)) {
            $this->monthlyIncome = FinancialTransaction::where('type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            $this->monthlyExpense = FinancialTransaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
            $this->monthlyNetBalance = $this->monthlyIncome - $this->monthlyExpense;
        }

        // --- ข้อมูลสรุปจากโมดูลการขายและลูกค้า (Sales & Customers) ---
        $this->totalRevenue = SalesOrder::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $this->salesToday = SalesOrder::whereDate('created_at', today())->sum('total_amount');
        $this->totalCustomers = Customer::count();
        $this->recentSales = SalesOrder::with('customer')->whereBetween('created_at', [$startDate, $endDate])->latest()->take(5)->get();

        // --- ข้อมูลสรุปจากโมดูลสินค้า (Products) ---
        $this->lowStockProductsCount = Product::whereColumn('quantity', '<=', 'min_stock_level')->count();

        // --- ข้อมูลสำหรับกราฟยอดขาย (ปรับตามช่วงวันที่) ---
        $groupByFormat = match($this->dateRange) {
            'this_year' => "DATE_FORMAT(created_at, '%Y-%m')",
            default => "DATE_FORMAT(created_at, '%Y-%m-%d')",
        };

        $labelFormatter = match($this->dateRange) {
            'this_year' => fn($date) => Carbon::createFromFormat('Y-m', $date)->format('F'),
            default => fn($date) => Carbon::createFromFormat('Y-m-d', $date)->format('d M'),
        };

        $sales = SalesOrder::select(DB::raw("$groupByFormat as date_group"), DB::raw('SUM(total_amount) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date_group')->orderBy('date_group', 'asc')->get();

        $this->monthlySalesData = [
            'labels' => $sales->pluck('date_group')->map($labelFormatter)->toArray(),
            'data' => $sales->pluck('total')->toArray(),
        ];
        $this->dispatch('update-chart', data: $this->monthlySalesData);

        // --- ข้อมูลสินค้าขายดี (กราฟและรายการ) ---
        if (class_exists(SalesOrderItem::class)) {
            $bestSelling = SalesOrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->whereHas('salesOrder', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->groupBy('product_id')->with('product:id,name,sku')->orderByDesc('total_quantity')->take(5)->get();

            $this->bestSellingProducts = $bestSelling;
            $totalSold = $bestSelling->sum('total_quantity');

            $this->bestSellingProductsChartData = [
                'labels' => $bestSelling->pluck('product.name')->toArray(),
                'data' => $bestSelling->pluck('total_quantity')->toArray(),
                'percentages' => [], // กำหนดค่าเริ่มต้นเป็น array ว่าง
            ];

            // ป้องกันการหารด้วยศูนย์ (Division by zero)
            if ($totalSold > 0) {
                $this->bestSellingProductsChartData['percentages'] = $bestSelling->map(function ($item) use ($totalSold) {
                    return round(($item->total_quantity / $totalSold) * 100, 2);
                })->toArray();
            }
        } else {
            // ถ้าไม่มีโมเดล หรือไม่มีข้อมูล ให้ตั้งค่าเป็นค่าว่างทั้งหมด
            $this->bestSellingProducts = collect();
            $this->bestSellingProductsChartData = ['labels' => [], 'data' => [], 'percentages' => []];
        }
        $this->dispatch('update-best-selling-chart', data: $this->bestSellingProductsChartData);
    }

    public function updatedDateRange()
    {
        $this->loadDashboardData();
    }

    public function render()
    {
        // ส่งตัวแปรทั้งหมดที่เป็น public ไปยัง view โดยอัตโนมัติ
        return view('livewire.dashboard.index');
    }
}
