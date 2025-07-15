{{-- Element หลักของ Livewire ต้องมีแค่ 1 ตัว --}}
<div>
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">

        {{-- ส่วนหัวของหน้า --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-500">ภาพรวมข้อมูลทั้งหมดของธุรกิจคุณในที่เดียว</p>
        </div>

        {{-- ส่วนของตัวเลือกช่วงวันที่ --}}
        <div class="mb-6">
            <label for="dateRange" class="block text-sm font-medium text-gray-700">เลือกช่วงวันที่:</label>
            <select wire:model.live="dateRange" id="dateRange" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm rounded-md">
                <option value="this_month">เดือนนี้</option>
                <option value="7_days">7 วันที่ผ่านมา</option>
                <option value="this_year">ปีนี้</option>
            </select>
        </div>

        {{-- โครงสร้าง Layout หลักเป็น 2 ส่วน: เนื้อหาหลัก (ซ้าย) และ แถบข้าง (ขวา) --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            {{-- ส่วนเนื้อหาหลัก (คอลัมน์ซ้าย) --}}
            <div class="space-y-8 lg:col-span-2">

                {{-- การ์ดข้อมูลสรุป (Stat Cards) --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    {{-- การ์ด: รายรับ --}}
                    <div class="flex items-start p-6 bg-green-100 rounded-lg shadow">
                        <div class="p-3 mr-4 text-green-600 bg-white rounded-full shadow-md">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-6-6h12" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-green-800">รายรับ</p>
                            <p class="text-2xl font-bold text-gray-800">฿{{ number_format($monthlyIncome, 2) }}</p>
                        </div>
                    </div>

                    {{-- การ์ด: รายจ่าย --}}
                    <div class="flex items-start p-6 bg-red-100 rounded-lg shadow">
                        <div class="p-3 mr-4 text-red-600 bg-white rounded-full shadow-md">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-red-800">รายจ่าย</p>
                            <p class="text-2xl font-bold text-gray-800">฿{{ number_format($monthlyExpense, 2) }}</p>
                        </div>
                    </div>

                    {{-- การ์ด: ยอดขายวันนี้ --}}
                    <div class="flex items-start p-6 bg-sky-100 rounded-lg shadow">
                        <div class="p-3 mr-4 text-sky-600 bg-white rounded-full shadow-md">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c.51 0 .962-.344 1.087-.835l1.828-6.857A.75.75 0 0 0 16.5 6H5.25" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-sky-800">ยอดขายวันนี้</p>
                            <p class="text-2xl font-bold text-gray-800">฿{{ number_format($salesToday, 2) }}</p>
                        </div>
                    </div>

                    {{-- การ์ด: สินค้าสต็อกต่ำ --}}
                    <div class="flex items-start p-6 bg-amber-100 rounded-lg shadow">
                        <div class="p-3 mr-4 text-amber-600 bg-white rounded-full shadow-md">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-amber-800">สินค้าสต็อกต่ำ</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($lowStockProductsCount) }} <span class="text-lg font-medium text-gray-500">รายการ</span></p>
                        </div>
                    </div>
                </div>

                {{-- กราฟยอดขาย --}}
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800">สรุปยอดขาย</h3>
                    <div class="mt-4 h-96">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>

                {{-- กราฟวงกลมแสดงสัดส่วนสินค้าขายดี --}}
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800">สัดส่วนสินค้าขายดี (5 อันดับแรก)</h3>
                    <div class="mt-4 h-80">
                        <canvas id="bestSellingProductsChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- แถบข้าง (Sidebar - คอลัมน์ขวา) --}}
            <div class="space-y-8 lg:col-span-1">

                {{-- ตารางรายการขายล่าสุด --}}
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800">รายการขายล่าสุด</h3>
                    <div class="mt-4 -mx-6">
                        <table class="min-w-full">
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($recentSales as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-semibold text-gray-800">
                                                <a href="#" class="hover:text-sky-600">{{ $order->order_number }}</a>
                                            </p>
                                            <p class="text-sm text-gray-500">{{ $order->customer->name ?? 'N/A' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <p class="text-sm font-semibold text-gray-800">฿{{ number_format($order->total_amount, 2) }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-10 text-center text-gray-500">ไม่มีรายการขายล่าสุด</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ตารางสินค้าขายดี --}}
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800">5 สินค้าขายดี</h3>
                    <div class="mt-4">
                        {{-- แก้ไขให้ใช้ isNotEmpty() กับ $bestSellingProducts ที่เป็น Collection ได้โดยตรง --}}
                        @if($bestSellingProducts->isNotEmpty())
                            <ul role="list" class="divide-y divide-gray-100">
                                @foreach ($bestSellingProducts as $item)
                                    <li class="flex items-center justify-between py-3">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-800 truncate" title="{{ $item->product->name ?? 'N/A' }}">{{ $item->product->name ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500 truncate">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
                                        </div>
                                        <div class="ml-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                                ขายแล้ว {{ $item->total_quantity }} ชิ้น
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="py-10 text-center text-gray-500">ไม่มีข้อมูลสินค้าขายดี</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', () => {
        // --- ส่วนจัดการกราฟแท่ง (ยอดขาย) ---
        let monthlySalesChart = null;
        const monthlySalesCanvas = document.getElementById('monthlySalesChart');
        const updateMonthlySalesChart = (data) => {
            if (!monthlySalesCanvas) return;
            if (monthlySalesChart) monthlySalesChart.destroy();
            if (data && data.labels && data.data && data.data.length > 0) {
                const ctx = monthlySalesCanvas.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 350);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.6)');
                gradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');
                monthlySalesChart = new Chart(ctx, {
                    type: 'bar',
                    data: { labels: data.labels, datasets: [{ label: 'ยอดขาย', data: data.data, backgroundColor: gradient, borderColor: 'rgba(59, 130, 246, 1)', borderWidth: 2, borderRadius: 8, borderSkipped: false, maxBarThickness: 60 }] },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, border: { dash: [2, 4], display: false }, ticks: { callback: (value) => '฿' + new Intl.NumberFormat('th-TH', { notation: 'compact' }).format(value), padding: 10, color: '#6b7280' }, grid: { color: '#e5e7eb' } }, x: { ticks: { color: '#6b7280' }, grid: { display: false } } }, plugins: { legend: { display: false }, tooltip: { enabled: true, backgroundColor: '#1f2937', titleColor: '#ffffff', bodyColor: '#ffffff', padding: 12, cornerRadius: 6, displayColors: false, callbacks: { label: (context) => 'ยอดขาย: ฿' + new Intl.NumberFormat('th-TH', { minimumFractionDigits: 2 }).format(context.parsed.y) } } } }
                });
            }
        };

        // --- ส่วนจัดการกราฟวงกลม (สินค้าขายดี) ---
        let bestSellingChart = null;
        const bestSellingCanvas = document.getElementById('bestSellingProductsChart');
        const updateBestSellingChart = (data) => {
            if (!bestSellingCanvas) return;
            if (bestSellingChart) bestSellingChart.destroy();
            // ตรวจสอบว่ามีข้อมูล percentages ด้วย
            if (data && data.labels && data.data && data.percentages && data.data.length > 0) {
                const ctx = bestSellingCanvas.getContext('2d');
                bestSellingChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data, // ข้อมูลจำนวนที่ขายได้
                            percentages: data.percentages, // ข้อมูลเปอร์เซ็นต์ที่ส่งมาใหม่
                            backgroundColor: ['rgba(59, 130, 246, 0.8)','rgba(34, 197, 94, 0.8)','rgba(239, 68, 68, 0.8)','rgba(245, 158, 11, 0.8)','rgba(107, 114, 128, 0.8)'],
                            borderColor: '#ffffff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { color: '#374151', usePointStyle: true, padding: 20 } },
                            tooltip: {
                                enabled: true,
                                backgroundColor: '#1f2937',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                padding: 12,
                                cornerRadius: 6,
                                callbacks: {
                                    label: (context) => {
                                        // ดึงค่าเปอร์เซ็นต์จาก custom property ที่เราเพิ่มเข้าไป
                                        const percentage = context.dataset.percentages[context.dataIndex];
                                        return `${context.label}: ${context.raw} ชิ้น (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        };

        // --- การวาดกราฟครั้งแรกและการรอรับข้อมูลอัปเดต ---
        updateMonthlySalesChart(@json($monthlySalesData));
        updateBestSellingChart(@json($bestSellingProductsChartData));

        window.Livewire.on('update-chart', event => updateMonthlySalesChart(event.data));
        window.Livewire.on('update-best-selling-chart', event => updateBestSellingChart(event.data));
    });
</script>
@endpush