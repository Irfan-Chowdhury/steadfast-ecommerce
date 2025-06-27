<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\SaleItem;
use App\Services\AccountingService;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{

    protected $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }



    public function index()
    {
        $sales = Sale::withCount('saleItems')->latest()->get();

        return view('admin.pages.sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all();

        return view('admin.pages.sales.form', compact('products'));
    }




    private function saleStore($request)
    {
        do {
            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . mt_rand(100, 999);
        } while (Sale::where('invoice_number', $invoiceNumber)->exists());

        return Sale::create([
            'invoice_number' =>  $invoiceNumber,
            'date' => $request->sale_date,
            'discount' => $request->discount,
            'vat_percentage' => $request->vat_percent,
            'vat_amount' => $request->vat_amount,
            'total_amount' => $request->total_amount,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $request->due_amount,
        ]);
    }

    private function saleItemStore($request, $saleId)
    {
        $saleItems = [];
        $productQuantities = [];

        foreach ($request->products as $item) {
            $item = (object) $item;

            $productQuantities[$item->product_id] = $item->quantity;

            $saleItems[] = [
                'sale_id' => $saleId,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        SaleItem::insert($saleItems);

        self::productStockUpdate($productQuantities);
    }

    private function productStockUpdate($productQuantities): void
    {
        $products = Product::whereIn('id', array_keys($productQuantities))->get();
        foreach ($products as $product) {
            $product->decrement('current_stock', $productQuantities[$product->id]);
        }
    }



    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            try {
                $sale = self::saleStore($request);

                self::saleItemStore($request, $sale->id);

                $this->accountingService->recordSaleTransaction($sale);

                DB::commit();

            } catch (Exception $exception) {

                return $exception->getMessage();

                return redirect()->back()->with('error', 'Something went wrong! Please try again.')->withInput();
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sale created successfully!');
    }


    public function show(Sale $sale)
    {
        //
    }


    public function edit(Sale $sale)
    {
        //
    }

    public function update(Request $request, Sale $sale)
    {
        //
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->back();
    }
}
