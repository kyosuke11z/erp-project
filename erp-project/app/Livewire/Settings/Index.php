<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public string $company_name    = '';
    public string $company_address = '';
    public string $company_phone   = '';
    public string $company_email   = '';
    public string $currency        = 'THB';
    public string $currency_symbol = '฿';
    public string $timezone        = 'Asia/Bangkok';
    public string $date_format     = 'd/m/Y';
    public string $items_per_page  = '15';
    public bool   $low_stock_notify = true;

    public bool $saved = false;

    public function mount(): void
    {
        $this->company_name     = Setting::get('company_name', 'My Company');
        $this->company_address  = Setting::get('company_address', '');
        $this->company_phone    = Setting::get('company_phone', '');
        $this->company_email    = Setting::get('company_email', '');
        $this->currency         = Setting::get('currency', 'THB');
        $this->currency_symbol  = Setting::get('currency_symbol', '฿');
        $this->timezone         = Setting::get('timezone', 'Asia/Bangkok');
        $this->date_format      = Setting::get('date_format', 'd/m/Y');
        $this->items_per_page   = Setting::get('items_per_page', '15');
        $this->low_stock_notify = (bool) Setting::get('low_stock_notify', '1');
    }

    public function save(): void
    {
        $this->validate([
            'company_name'   => 'required|string|max:100',
            'company_email'  => 'nullable|email|max:100',
            'company_phone'  => 'nullable|string|max:30',
            'currency'       => 'required|string|max:10',
            'currency_symbol'=> 'required|string|max:5',
            'timezone'       => 'required|string|max:50',
            'date_format'    => 'required|string|max:20',
            'items_per_page' => 'required|integer|min:5|max:100',
        ]);

        Setting::set('company_name',     $this->company_name);
        Setting::set('company_address',  $this->company_address);
        Setting::set('company_phone',    $this->company_phone);
        Setting::set('company_email',    $this->company_email);
        Setting::set('currency',         $this->currency);
        Setting::set('currency_symbol',  $this->currency_symbol);
        Setting::set('timezone',         $this->timezone);
        Setting::set('date_format',      $this->date_format);
        Setting::set('items_per_page',   $this->items_per_page);
        Setting::set('low_stock_notify', $this->low_stock_notify ? '1' : '0');

        $this->saved = true;
        $this->dispatch('setting-saved');
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}
