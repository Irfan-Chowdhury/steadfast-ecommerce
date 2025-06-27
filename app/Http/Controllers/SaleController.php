<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\SaleItem;
use App\Services\AccountingService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{

    protected $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }



    public function index()
    {
        $sales = Sale::withCount('saleItems')
            ->orderBy('date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin.pages.sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all();

        return view('admin.pages.sales.create', compact('products'));
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

                self::setLogError($exception);

                return redirect()->back()->withErrors(['errors'=> ['Something went wrong']]);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sale created successfully!');
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


    public function show(Sale $sale)
    {
        $saleItems = $sale->saleItems;

        $products = Product::all();

        return view('admin.pages.sales.show', compact('sale','products','saleItems'));
    }


    public function edit(Sale $sale)
    {
        $saleItems = $sale->saleItems;

        $products = Product::all();

        return view('admin.pages.sales.edit', compact('sale','products','saleItems'));
    }

    public function update(Request $request, Sale $sale)
    {
        //
    }

    public function destroy(Sale $sale)
    {
        try {

            $sale->delete();

        } catch (Exception $exception) {

            Log::error(["error"=>$exception->getMessage()]);

            return redirect()->back()->withErrors(['errors'=> ['Something went wrong']]);
        }

        return redirect()->back()->with('success', 'Sale deleted successfully!');
    }

    private function setLogError($exception): void
    {
        Log::error('Error occurred', [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTraceAsString() // Be careful with this in production
        ]);
    }
}
