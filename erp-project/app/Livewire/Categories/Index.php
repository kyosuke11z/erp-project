<?php

namespace App\Livewire\Categories;

use Livewire\Attributes\Url;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;


class Index extends Component
{
    use WithPagination;

    public bool $showModal = false;
    public bool $showDeleteModal = false;
    // Property สำหรับผูกกับ Input field ในฟอร์ม
    public string $search = '';
    public string $name = '';

    // Property สำหรับเก็บ Category ที่กำลังแก้ไข
    public ?Category $editing = null;
    public ?Category $deleting = null;
    // กำหนดกฎการตรวจสอบข้อมูลแบบไดนามิก
    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:3',
                // ถ้าเป็นการแก้ไข ให้ยกเว้นชื่อของตัวเองในการเช็ค unique
                \Illuminate\Validation\Rule::unique('categories')->ignore($this->editing?->id),
            ],
        ];
    }

    // Method สำหรับเปิด Modal เพื่อ "สร้าง"
    public function openModal(): void
    {
        $this->reset(); // รีเซ็ตค่าทั้งหมด (name, editing)
        $this->resetErrorBag(); // ล้างข้อความ error เก่า
        $this->showModal = true;
    }

    // Method สำหรับเปิด Modal เพื่อ "แก้ไข"
    public function edit(Category $category): void
    {
        $this->editing = $category;
        $this->name = $category->name;
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset('name', 'editing');
    }

    public function confirmDelete(Category $category): void
    {
        $this->deleting = $category;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deleting) {
            $this->deleting->delete();
            session()->flash('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');
        }

        $this->showDeleteModal = false;
        $this->deleting = null;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editing) {
            $this->editing->update(['name' => $this->name]);
            session()->flash('success', 'อัปเดตหมวดหมู่เรียบร้อยแล้ว');
        } else {
            Category::create(['name' => $this->name]);
            session()->flash('success', 'สร้างหมวดหมู่เรียบร้อยแล้ว');
        }

        $this->closeModal();
    }
  // Lifecycle hook: จะทำงานทุกครั้งที่มีการพิมพ์ในช่องค้นหา
    public function updatedSearch(): void
    {
        $this->resetPage(); // รีเซ็ตหน้า pagination กลับไปที่หน้า 1
    }

    public function render()
    {
        $categories = Category::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'asc')
            ->paginate(10);

    return view('livewire.categories.index', compact('categories'))->layout('layouts.app');
}
}