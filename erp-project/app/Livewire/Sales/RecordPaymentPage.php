<?php

namespace App\Livewire\Sales;

use App\Models\FinanceCategory;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class RecordPaymentPage extends Component
{
    public SalesOrder $salesOrder;

    /**
     * คอมเมนต์: เมธอด mount จะทำงานเมื่อ component ถูกสร้างขึ้น
     * ใช้สำหรับเตรียมข้อมูลและตรวจสอบเงื่อนไขเบื้องต้น
     */
    public function mount(SalesOrder $salesOrder)
    {
        // คอมเมนต์: ตรวจสอบว่าใบสั่งขายนี้สามารถรับชำระเงินได้หรือไม่
        if ($salesOrder->status !== 'pending') {
            // ถ้าสถานะไม่ถูกต้อง ให้ redirect กลับไปหน้าเดิมพร้อมข้อความแจ้งเตือน
            session()->flash('error', 'ไม่สามารถบันทึกการชำระเงินสำหรับใบสั่งขายนี้ได้ (สถานะปัจจุบัน: ' . ucfirst($salesOrder->status) . ')');
            $this->redirectRoute('sales.show', ['salesOrder' => $salesOrder->id], navigate: true);
            return;
        }
        $this->salesOrder = $salesOrder;
    }

    /**
     * คอมเมนต์: เมธอดนี้จะถูกเรียกเมื่อผู้ใช้กดยืนยันการชำระเงิน
     */
    public function savePayment()
    {
        // คอมเมนต์: ตรวจสอบสถานะอีกครั้งก่อนบันทึก เพื่อความปลอดภัยสูงสุด
        if ($this->salesOrder->status !== 'pending') {
            session()->flash('error', 'เกิดข้อผิดพลาด: สถานะของใบสั่งขายมีการเปลี่ยนแปลง');
            $this->redirectRoute('sales.show', ['salesOrder' => $this->salesOrder->id], navigate: true);
            return;
        }

        // คอมเมนต์: ค้นหาหมวดหมู่การเงิน (เหมือนเดิม)
        $salesCategory = FinanceCategory::where('type', 'income')->where('name', 'รายได้จากการขาย')->first();
        if (!$salesCategory) {
            session()->flash('error', 'ไม่พบหมวดหมู่การเงินสำหรับ "รายได้จากการขาย" กรุณาตั้งค่าในระบบการเงินก่อน');
            $this->redirectRoute('sales.show', ['salesOrder' => $this->salesOrder->id], navigate: true);
            return;
        }

        // คอมเมนต์: ใช้ DB Transaction เพื่อความถูกต้องของข้อมูล
        DB::transaction(function () use ($salesCategory) {
            $this->salesOrder->update(['status' => 'paid', 'paid_at' => now()]);

            $this->salesOrder->financialTransactions()->create([
                'user_id' => auth()->id(), 'finance_category_id' => $salesCategory->id, 'type' => 'income',
                'description' => "รายรับจากใบสั่งขาย #{$this->salesOrder->order_number}",
                'amount' => $this->salesOrder->total_amount, 'transaction_date' => now(),
            ]);
        });

        Log::info("Payment recorded for Sales Order #{$this->salesOrder->id} by user " . auth()->id());

        // คอมเมนต์: ส่งข้อความสำเร็จและ redirect กลับไปหน้าแสดงรายละเอียด
        session()->flash('success', 'บันทึกการชำระเงินสำหรับใบสั่งขาย #' . $this->salesOrder->order_number . ' สำเร็จ');
        $this->redirectRoute('sales.show', ['salesOrder' => $this->salesOrder->id], navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.record-payment-page');
    }
}