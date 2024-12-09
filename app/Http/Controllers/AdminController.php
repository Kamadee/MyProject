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
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Quantity;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rules\Can;
use PhpParser\Node\Expr\FuncCall;

class AdminController extends Controller
{
    public function getLogin()
    {
        return view('admin.login');
    }

    public function logOut()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function getOverview()
    {
        $totalRevenue  = Order::where('status_id', 4)->sum('total_amount');
        $totalCustomer = User::where('is_admin', 0)->count();
        $totalProduct = Product::all()->count();
        $orderCompleted = Order::where('status_id', 4)->count();
        $monthlyRevenue = DB::table('orders')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_amount) as total'))
            ->where('status_id', 4)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
        $formData = [];
        for ($i = 1; $i <= 12; $i++) {
            $formData[] = $monthlyRevenue[$i] ?? 0;
        }
        return view('admin.overview', [
            'totalRevenue' => $totalRevenue,
            'monthlyRevenue' => json_encode($formData),
            'totalCustomer' => $totalCustomer,
            'totalProduct' => $totalProduct,
            'orderCompleted' => $orderCompleted
        ]);
    }

    public function getOrderCustomer()
    {
        $orderCustomer = Order::all();
        $orderStatusList = OrderStatus::all();
        return view('admin.orderCustomer', [
            'orderCustomer' => $orderCustomer,
            'orderStatusList' => $orderStatusList
        ]);
    }

    public function getProductManage()
    {
        $products = Product::with('categories')->get();;
        return view('admin.productManage', ['products' => $products]);
    }

    public function addProduct()
    {
        $categoriesList = Category::all();
        return view('admin.addProduct', ['categoriesList' => $categoriesList]);
    }
    public function saveAdd(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_name' => ['required'],
                'brand' => ['required'],
                'price' => 'required|numeric',
                'thumbnail_url' => ['required'],
                'category_id' => ['required'],
            ]);

            if ($validator->fails()) {
                // Validation failed
                return back()->withErrors($validator)->withInput();
            }
            $product = Product::create([
                'product_name' => $request->product_name,
                'brand' => $request->brand,
                'price' => $request->price,
                'thumbnail_url' => $request->thumbnail_url,
                'category_id' => $request->category_id,
                'description' => $request->description
            ]);
            $sizes = ['S', 'M', 'L', 'XL'];
            $colors = ['Brown', 'Grey', 'Plum Red'];
            // Thêm size và color vào bảng quantities
            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    Quantity::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $color,
                        'quantity' => 10,
                    ]);
                }
            }

            return redirect()->route('admin.productManage')->with('success', 'Thêm sản phẩm thành công!');
        } catch (Exception $e) {
            dd($e);
        }
    }
    public function editProduct(Request $request)
    {
        $productId = $request->productId;
        $product = Product::with('quantities')->findOrFail($productId);
        $categoriesList = Category::all();
        $sizes = Quantity::distinct()->pluck('size');

        return view('admin.addProduct', [
            'product' => $product,
            'categoriesList' => $categoriesList,
            'sizes' => $sizes,
        ]);
    }

    public function saveEdit(Request $request)
    {
        $productId = $request->productId;
        $product = Product::findOrFail($productId);
        $validator = Validator::make($request->all(), [
            'product_name' => ['required'],
            'brand' => ['required'],
            'price' => 'required|numeric',
            'thumbnail_url' => ['required'],
            'category_id' => ['required']
        ]);
        if ($validator->fails()) {
            // Validation failed
            $errors = $validator->errors();
            return back()->withErrors($errors);
            // Handle the errors
        } else {
            $data = [
                'product_name' => $request->product_name,
                'brand' => $request->brand,
                'price' => $request->price,
                'thumbnail_url' => $request->thumbnail_url,
                'category_id' => $request->category_id,
                'description' => $request->description
            ];
        }
        Product::where(['id' => $productId])->update($data);
        return redirect()->route('admin.productManage')->with('Success', 'Chỉnh sửa sản phẩm thành công');
    }

    public function deleteProduct(Request $request)
    {
        $productId = $request->input('id');
        Product::where(['id' => $productId])->delete();
        return redirect()->back()->with('ok', 'Xóa sản phẩm thành công!!');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orderId' => 'required|exists:orders,id',
            'statusId' => 'required|exists:order_status,id',
        ]);
        $order = Order::find($request->orderId);
        $order->status_id = $request->statusId;
        $order->save();
        return redirect()->back()->with('ok', 'Cập nhật trạng thái đơn hàng thành công!!');
    }

    public function getDetailOrder(Request $request)
    {
        $orderId = $request->orderId;
        $order = Order::where(['id' => $orderId])->with('orderItems.product')->first();
        return view('admin.detailOrder', ['order' => $order]);
    }
    public function getCustomerManage()
    {
        $customers = User::where('is_admin', 0)->get();
        return view('admin.customerManage', ['customers' => $customers]);
    }
    public function deleteCustomer(Request $request)
    {
        $customerId = $request->id;
        User::where(['id' => $customerId])->delete();
        return redirect()->back()->with('ok', 'Xóa khách hàng thành công!!');
    }
}
