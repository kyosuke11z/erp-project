<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Illuminate\Support\Facades\File;
use Mpdf\Output\Destination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PurchaseOrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Generate and stream a PDF for the given purchase order.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(PurchaseOrder $purchaseOrder)
    {
        // ตรวจสอบสิทธิ์โดยใช้ Policy: ผู้ใช้คนนี้สามารถ 'view' ใบสั่งซื้อใบนี้ได้หรือไม่
        $this->authorize('view', $purchaseOrder);

        // 1. Eager load relationships for efficiency
        $purchaseOrder->load(['supplier', 'items.product']);

        // 2. Render the Blade view to an HTML string
        $html = view('pdf.purchase-order', ['purchaseOrder' => $purchaseOrder])->render();

        // 3. Configure mPDF for Thai language support
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $tempDir = storage_path('app/mpdf/temp');
        if (!File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0755, true, true);
        }

        $mpdf = new Mpdf([
            'tempDir' => $tempDir,
            'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
            'fontdata' => $fontData + [
                'sarabun' => [
                    'R' => 'Sarabun-Regular.ttf',
                    'B' => 'Sarabun-Bold.ttf',
                    'I' => 'Sarabun-Italic.ttf',
                    'BI' => 'Sarabun-BoldItalic.ttf',
                ]
            ],
            'default_font' => 'sarabun',
            'autoScriptToLang' => true, // <-- เพิ่มส่วนนี้เพื่อแก้สระซ้อน
            'autoLangToFont' => true,   // <-- เพิ่มส่วนนี้เพื่อแก้สระซ้อน
        ]);

        $mpdf->WriteHTML($html);
        $fileName = 'PO-' . $purchaseOrder->po_number . '.pdf';

        // 4. Return the PDF as a response to be displayed inline in the browser
        return $mpdf->Output($fileName, Destination::INLINE);
    }
}