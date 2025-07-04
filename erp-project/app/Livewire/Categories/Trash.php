<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Trash extends Component
{
    use WithPagination;

    // เมธอดสำหรับกู้คืนข้อมูล
    public function restore(int $id): void
    {
        // ค้นหาข้อมูลจาก ID แม้ว่าจะถูกลบไปแล้ว
        $category = Category::onlyTrashed()->find($id);
        if ($category) {
            $category->restore();
            session()->flash('success', 'กู้คืนหมวดหมู่เรียบร้อยแล้ว');
        }
    }

    // เมธอดสำหรับลบข้อมูลอย่างถาวร
    public function forceDelete(int $id): void
    {
        $category = Category::onlyTrashed()->find($id);
        if ($category) {
            $category->forceDelete();
            session()->flash('success', 'ลบหมวดหมู่ถาวรเรียบร้อยแล้ว');
        }
    }

    public function render()
    {
        // ดึงข้อมูลเฉพาะที่ถูก Soft Delete (อยู่ในถังขยะ)
        $categories = Category::onlyTrashed()->latest('deleted_at')->paginate(10);

        return view('livewire.categories.trash', compact('categories'))->layout('layouts.app');
    }
}

