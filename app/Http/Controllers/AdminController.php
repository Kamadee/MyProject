<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Product;
use App\Models\Favourite;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Can;
use PhpParser\Node\Expr\FuncCall;

class AdminController extends Controller
{
    public function getLogin(Request $request)
    {
        return view('admin.login');
    }

    public function logOut()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function getOverview(Request $request)
    {
        return view('admin.overview');
    }
    public function getOrderCustomer(Request $request)
    {
        $orderCustomer = Order::all();
        return view('admin.orderCustomer', ['orderCustomer' => $orderCustomer]);
    }

    public function getDetailOrder(Request $request)
    {
        $orderId = $request->orderId;
        $order = Order::findOrFail($orderId);
        $orderItem = OrderItem::with('order')->where('order_id', $orderId)->get();
        dd($orderId);
        return view('admin.detailOrder', [
            'order' => $order,
            'orderItem' => $orderItem
        ]);
    }

    public function getProductManage(Request $request)
    {
        $products = Product::all();
        return view('admin.productManage', ['products' => $products]);
    }

    public function addProduct(Request $request)
    {
        return view('admin.addProduct');
    }

    public function saveEdit(Request $request)
    {
        $productId = $request->productId;
        $newName = $request->input('product_name');
        $newBrand = $request->input('brand');
        $newPrice = $request->input('price');
        $newImg = $request->input('thumbnail_url');
        $product = Product::findOrFail($productId);
        $validator = Validator::make($request->all(), [
            'newName' => ['required'],
            'newBrand' => ['required'],
            'newPrice' => ['required'],
            'newImg' => ['required'],
            'newImg' => ['required'],
        ]);
        if ($validator->fails()) {
            // Validation failed
            $errors = $validator->errors();
            return back()->withErrors($errors);
            // Handle the errors
        } else {
            $data = [
                'product_name' => $newName,
                'brand' => $newBrand,
                'price' => $newPrice,
                'thumbnail_url' => $newImg,
            ];
        }
        Product::where(['id' => $productId])->update($data);
        return redirect()->route('admin.editForm')->with('Success', 'Chỉnh sửa sản phẩm thành công');
    }

    public function saveAdd(Request $request)
    {
        try {
            $newName = $request->input('product_name');
            $newBrand = $request->input('brand');
            $newPrice = $request->input('price');
            $newImg = $request->input('thumbnail_url');
            $validator = Validator::make($request->all(), [
                'newName' => ['required'],
                'newBrand' => ['required'],
                'newPrice' => ['required'],
                'newImg' => ['required'],
                'newImg' => ['required'],
            ]);
            if ($validator->fails()) {
                // Validation failed
                $errors = $validator->errors();
                return back()->withErrors($errors);
                // Handle the errors
            } else {
                $data = [
                    'product_name' => $newName,
                    'brand' => $newBrand,
                    'price' => $newPrice,
                    'thumbnail_url' => $newImg,
                ];
            }
            Product::create($data);
            return redirect()->route('admin.productManage')->with('Success', 'Chỉnh sửa sản phẩm thành công');
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function deleteProduct(Request $request)
    {
        $productId = $request->input('id');
        Product::where(['id' => $productId])->delete();
        return redirect()->back()->with('ok', 'Xóa sản phẩm thành công!!');
    }
}
