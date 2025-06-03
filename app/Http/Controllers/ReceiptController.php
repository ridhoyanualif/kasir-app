<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class ReceiptController extends Controller
{
    public function generatePdf(Request $request)
    {
        $htmlContent = $request->input('html');
        $invoice = $request->input('invoice');
        $receiptFolder = storage_path("app/public/receipt");
        if (!file_exists($receiptFolder)) {
            mkdir($receiptFolder, 0755, true);
        }
        $pdfPath = "{$receiptFolder}/{$invoice} Receipt.pdf";

        Browsershot::html($htmlContent)
            ->format('A4')
            ->margins(10, 10, 10, 10)
            ->save($pdfPath);

        return response()->file($pdfPath);
    }
}
