<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest('id')->get(); // Fetch all users
        return view('admin.users.index', compact('users')); // Pass $users to the view
    }

    public function generatePdf(Request $request)
{
    $htmlContent = $request->input('html');
    $transactionId = $request->input('transaction_id');

    $receiptFolder = storage_path("app/public/receipt");
    if (!file_exists($receiptFolder)) {
        mkdir($receiptFolder, 0755, true);
    }

    $pdfPath = "{$receiptFolder}/{$transactionId}_Selling_Report.pdf";

    Browsershot::html($htmlContent)
        ->format('A4')
        ->margins(10, 10, 10, 10)
        ->waitUntilNetworkIdle()
        ->savePdf($pdfPath);

    return response()->file($pdfPath);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
