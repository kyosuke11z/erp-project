<?php

namespace App\Observers;

use App\Exceptions\InsufficientStockException;
use App\Models\SalesOrderItem;

class SalesOrderItemObserver
{
    /**
     * Handle the SalesOrderItem "creating" event.
     * This method validates if there is enough stock before the order item is created.
     *
     * @throws \App\Exceptions\InsufficientStockException
     */
    public function creating(SalesOrderItem $salesOrderItem): void
    {
        // Check if the requested quantity exceeds the available stock.
        if ($salesOrderItem->product->quantity < $salesOrderItem->quantity) {
            throw new InsufficientStockException(
                "Product \"{$salesOrderItem->product->name}\" has insufficient stock. Available: {$salesOrderItem->product->quantity}, Requested: {$salesOrderItem->quantity}"
            );
        }
    }

    /**
     * Handle the SalesOrderItem "created" event.
     *
     * This method is automatically called by Laravel whenever a new SalesOrderItem is saved to the database.
     */
    public function created(SalesOrderItem $salesOrderItem): void
    {
        // Get the related product from the sales order item
        $product = $salesOrderItem->product;

        // Ensure the product exists before attempting to decrement stock
        if ($product) {
            // Decrement the product's quantity by the amount ordered
            $product->decrement('quantity', $salesOrderItem->quantity);
        }
    }

    /**
     * Handle the SalesOrderItem "updated" event.
     */
    public function updated(SalesOrderItem $salesOrderItem): void
    {
        //
    }

    /**
     * Handle the SalesOrderItem "deleted" event.
     */
    public function deleted(SalesOrderItem $salesOrderItem): void
    {
        // Get the related product from the sales order item
        $product = $salesOrderItem->product;

        // Ensure the product exists before attempting to increment stock
        if ($product) {
            // Increment the product's quantity by the amount that was ordered
            $product->increment('quantity', $salesOrderItem->quantity);
        }
    }
}