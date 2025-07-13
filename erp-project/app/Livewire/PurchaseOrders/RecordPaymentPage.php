<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\FinanceCategory;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RecordPaymentPage extends Component
{
    public PurchaseOrder $purchaseOrder;

    public function mount(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            session()->flash('error', 'ไม่สามารถบันทึกการจ่ายเงินสำหรับใบสั่งซื้อนี้ได้ (สถานะปัจจุบัน: ' . ucfirst($purchaseOrder->status) . ')');
            $this->redirectRoute('purchase-orders.show', ['purchaseOrder' => $purchaseOrder->id], navigate: true);
            return;
        }
        $this->purchaseOrder = $purchaseOrder;
    }

    public function savePayment()
    {
        if ($this->purchaseOrder->status !== 'pending') {
            session()->flash('error', 'เกิดข้อผิดพลาด: สถานะของใบสั่งซื้อมีการเปลี่ยนแปลง');
            $this->redirectRoute('purchase-orders.show', ['purchaseOrder' => $this->purchaseOrder->id], navigate: true);
            return;
        }

        // คอมเมนต์: ค้นหาหมวดหมู่การเงินสำหรับ "ค่าใช้จ่ายซื้อสินค้า" หรือ "ต้นทุนขาย"
        // **ข้อควรระวัง**: คุณต้องมี FinanceCategory ที่ชื่อนี้ และมี type เป็น 'expense' อยู่ในระบบก่อน
        $expenseCategory = FinanceCategory::where('type', 'expense')->where('name', 'ค่าใช้จ่ายซื้อสินค้า')->first();
        if (!$expenseCategory) {
            session()->flash('error', 'ไม่พบหมวดหมู่การเงินสำหรับ "ค่าใช้จ่ายซื้อสินค้า" กรุณาตั้งค่าในระบบการเงินก่อน');
            $this->redirectRoute('purchase-orders.show', ['purchaseOrder' => $this->purchaseOrder->id], navigate: true);
            return;
        }

        DB::transaction(function () use ($expenseCategory) {
            $this->purchaseOrder->update(['status' => 'paid', 'paid_at' => now()]);

            $this->purchaseOrder->financialTransactions()->create([
                'user_id' => auth()->id(),
                'finance_category_id' => $expenseCategory->id,
                'type' => 'expense',
                'description' => "รายจ่ายสำหรับใบสั่งซื้อ #{$this->purchaseOrder->id}",
                'amount' => $this->purchaseOrder->total_amount,
                'transaction_date' => now(),
            ]);
        });

        Log::info("Payment recorded for Purchase Order #{$this->purchaseOrder->id} by user " . auth()->id());

        session()->flash('success', 'บันทึกการจ่ายเงินสำหรับใบสั่งซื้อ #' . $this->purchaseOrder->id . ' สำเร็จ');
        $this->redirectRoute('purchase-orders.show', ['purchaseOrder' => $this->purchaseOrder->id], navigate: true);
    }

    public function render()
    {
        return view('livewire.purchase-orders.record-payment-page');
    }
}