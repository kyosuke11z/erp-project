<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // ในตัวอย่างนี้ เราจะอนุญาตให้ผู้ใช้ที่ล็อกอินทุกคนดูรายการสินค้าได้
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        // ในตัวอย่างนี้ เราจะอนุญาตให้ผู้ใช้ที่ล็อกอินทุกคนดูสินค้าแต่ละชิ้นได้
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // ใช้ permission ที่เรามีอยู่แล้ว
        return $user->can('create products');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->can('edit products');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->can('delete products');
    }
}