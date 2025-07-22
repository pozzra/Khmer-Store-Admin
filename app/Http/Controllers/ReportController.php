<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use PDF;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function exportPDF()
    {
        $products = Product::all();
        $pdf = PDF::loadView('report.pdf', compact('products'));
        return $pdf->download('products.pdf');
    }

    public function index()
    {
        $products = Product::all();
        return Inertia::render('Report/Index', [
            'productss' => $products
        ]);
    }

    public function create()
    {
        return Inertia::render('Report/create');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return Inertia::render('Report/edit', [
            'product' => $product
        ]);
    }

    public function store(Request $request)
    {
        // Implement store logic if needed
        return redirect()->route('report.index');
    }

    public function update(Request $request, $id)
    {
        // Implement update logic if needed
        return redirect()->route('report.index');
    }

    public function destroy($id)
    {
        // Implement destroy logic if needed
        return redirect()->route('report.index');
    }
}
