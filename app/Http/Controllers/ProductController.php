<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\OrderItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Quantity;
use App\Models\ProductImage;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function getHome(Request $request)
    {
        $products = Product::all();
        return view('product.homeShop', ['products' => $products]);
    }
    public function getListProduct(Request $request)
    {
        $products = Product::all();
        return view('product.listProduct', ['products' => $products]);
    }
    public function getProduct(Request $request)
    {
        $products = Product::all();
        $oldSearchForm = [];
        if ($request->isMethod('post')) {
            $size = $request->size;
            $brand = $request->brand;
            $color = $request->color;
            $minRange = $request->minRange;
            $maxRange = $request->maxRange;
            $formData = [
                'size' => $size,
                'brand' => $brand,
                'color' => $color,
                'minRange' => $minRange,
                'maxRange' => $maxRange
            ];
            $oldSearchForm = $formData;
            $products = $this->searchProduct($formData);
            // $totalResults = $products->count();
        } else {
            $products = $this->searchProduct();
        }
        $brandList = $this->getBrandList();
        $sizeList = $this->getSizeList();
        $colorList = $this->getColorList();

        return view('product.searchProduct', ['sizeList' => $sizeList, 'colorList' => $colorList, 'brandList' => $brandList, 'products' => $products, 'searchForm' => $oldSearchForm]);
    }

    public function getBrandList()
    {
        $brandList = Product::select('brand')->distinct()->get();
        return $brandList;
    }
    public function getSizeList()
    {
        $sizeList = Quantity::select('size')->distinct()->get();
        return $sizeList;
    }

    public function getColorList()
    {
        $colorList = Quantity::select('color')->distinct()->get();
        return $colorList;
        dd($colorList);
    }

    public function searchProduct($formData = null)
    {
        $products = Product::with(['quantities']);
        // dd($formData);
        if ($formData && $formData['size']) {
            $size = $formData['size'];
            $products->whereHas('quantities', function ($query) use ($size) {
                $query->where('size', $size);
            });
        }

        if ($formData && $formData['brand']) {
            $brand = $formData['brand'];
            $products->whereIn('brand', $brand);
            // dd($products)->get();
        }

        if ($formData && $formData['color']) {
            $color = $formData['color'];
            $products->with(['quantities' => function ($q) use ($color) {
                $q->whereIn('color', $color);
            }])->whereHas('quantities', function ($query) use ($color) {
                $query->whereIn('color', $color);
            });
        }

        if ($formData && $formData['maxRange'] && $formData['minRange']) {
            $minRange = floatval(str_replace(',', '', substr($formData['minRange'], 1)));
            $maxRange = floatval(str_replace(',', '', substr($formData['maxRange'], 1)));
            $products->where('price', '>=', $minRange)
                ->where('price', '<=', $maxRange);
            //  dd($products)->get();
        }
        return $products->get();
    }

    // public function buyCart(Request $request) {
    //     $carts = Cart::all();
    //     if($request->isMethod('post')){

    //     }
    // }
    public function getDetailProduct(Request $request)
    {
        $productId = $request->productId;
        $product = Product::with('quantities')->find($productId);
        $sizes = Quantity::select('size')->distinct()->where('product_id', $productId)->get();
        $colors = Quantity::select('color')->distinct()->where('product_id', $productId)->get();

        $bestItems = OrderItem::select('order_items.product_id', DB::raw('COUNT(order_items.product_id) as occurence'), 'products.product_name', 'products.thumbnail_url', 'products.price')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->groupBy('order_items.product_id', 'products.product_name', 'products.thumbnail_url', 'products.price')
            ->orderBy('occurence', 'DESC')
            ->get();
        return view('product.detailProduct', ['product' => $product, 'sizes' => $sizes, 'colors' => $colors, 'bestItems' => $bestItems]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $products = Product::where('product_name', 'like', '%' . $keyword . '%')->get();
        return view('product.listProduct', ['products' => $products]);
    }

    // public function pay(Request $request) {
    //     $product = Product::with('quantities')->where('product_id', $request->productId)->get();

    // }    
}
