<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Mpdf\Output\Destination;
class GoodsReceiptController extends Controller
{
    /**
     * Generate a PDF document for the given Goods Receipt.
     *
     * @param  \App\Models\GoodsReceipt  $goodsReceipt
     * @return \Illuminate\Http\Response
     */ 
    public function generatePdf(GoodsReceipt $goodsReceipt): Response
    {
        // Eager load all necessary relationships for the PDF view
        $goodsReceipt->load(['purchaseOrder.supplier', 'items.product', 'createdBy']);

        // Render the Blade view to an HTML string
        $html = view('pdf.goods-receipt', ['goodsReceipt' => $goodsReceipt])->render();

        // Configure mPDF for Thai language support (mirroring PurchaseOrderController)
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
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $mpdf->WriteHTML($html);
        $fileName = 'GR-' . $goodsReceipt->receipt_number . '.pdf';

        return new Response($mpdf->Output($fileName, Destination::INLINE), 200, ['Content-Type' => 'application/pdf']);
    }
}