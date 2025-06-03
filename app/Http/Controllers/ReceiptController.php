<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class ReceiptController extends Controller
{
    public function generatePdf(Request $request)
    {
        $htmlContent = $request->input('html');
        $transactionId = $request->input('transaction_id'); // Ambil transaction_id
        $receiptFolder = storage_path("app/public/receipt");
        if (!file_exists($receiptFolder)) {
            mkdir($receiptFolder, 0755, true);
        }
        $pdfPath = "{$receiptFolder}/{$transactionId} Receipt.pdf";

        Browsershot::html($htmlContent)
            ->format('A4')
            ->margins(10, 10, 10, 10)
            ->save($pdfPath);

        return response()->file($pdfPath);
    }
}
