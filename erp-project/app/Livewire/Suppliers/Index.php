<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    // บอกให้ Livewire ใช้ Pagination View ของ Tailwind ที่เราปรับแต่งไว้
    protected $paginationTheme = 'tailwind';

    public ?int $supplierId = null;


    #[Rule('required|string|max:255')]
    public $name;

    #[Rule('nullable|string|max:255')]
    public $contact_person;

    #[Rule('nullable|email|max:255')]
    public string $email = '';

    #[Rule('nullable|string|max:255')]
    public $phone;

    #[Rule('nullable|string')]
    public $address;

    public $search = '';
    public $showModal = false;
    public $isEditMode = false;

    public function create()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function edit(Supplier $supplier)
    {
        $this->resetValidation();
        $this->supplierId = $supplier->id;
        $this->name = $supplier->name;
        $this->contact_person = $supplier->contact_person;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        // สร้าง Rule สำหรับ email uniqueness ตอนแก้ไข
        $this->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $this->supplierId,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        // ใช้ updateOrCreate เพื่อให้โค้ดกระชับขึ้น (เหมือนใน CustomerPage)
        Supplier::updateOrCreate(
            ['id' => $this->supplierId],
            [
                'name' => $this->name,
                'contact_person' => $this->contact_person,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ]
        );

        session()->flash('success', 'บันทึกข้อมูลซัพพลายเออร์เรียบร้อยแล้ว');
        $this->closeModal();
    }

    public function delete(Supplier $supplier)
    {
        $supplier->delete();
        session()->flash('success', 'ลบข้อมูลซัพพลายเออร์เรียบร้อยแล้ว');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * รีเซ็ตค่าในฟอร์มทั้งหมด
     */
    private function resetForm(): void
    {
        $this->reset(['supplierId', 'name', 'contact_person', 'email', 'phone', 'address', 'isEditMode']);
    }

    public function render()
    {
        $suppliers = Supplier::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.suppliers.index', [
            'suppliers' => $suppliers,
        ]);
    }
}