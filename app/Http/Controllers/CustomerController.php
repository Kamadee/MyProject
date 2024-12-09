<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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

class CustomerController extends Controller
{
    public function getProfile(Request $request)
    {
        $customerId = Auth::user()->id;
        $customer = User::find($customerId);
        // dd($customer);
        // Nếu không tìm thấy người dùng
        if (!$customer) {
            abort(404, 'Customer not found.');
        }

        // Xử lý cho AJAX request
        if ($request->ajax()) {
            return response()->view('customer.profile', ['customer' => $customer], 200);
        }
        return view('customer.profile', ['customer' => $customer]);
    }

    public function changeProfile(Request $request)
    {
        $newName = $request->input('name');
        $newEmail = $request->input('email');
        $newPhone = $request->input('phone');
        $newAddress = $request->input('address');
        $userId = Auth::id();
        $customer = User::findOrFail($userId);
        if ($customer) {
            $customer->name = $newName;
            $customer->email = $newEmail;
            $customer->phone = $newPhone;
            $customer->address = $newAddress;
            $customer->save();
        }
        return redirect()->route('customer.profile')->with('Success', 'Chỉnh sửa profile thành công');
    }

    public function getBill(Request $request)
    {
        $customerId = Auth::id();
        $customerOrder = Order::where('user_id', $customerId)->get();
        return view('customer.bill', ['customerOrder' => $customerOrder]);
    }

    public function toggleFavourite(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('productId');
        $productExist = Favourite::where(['product_id' => $productId, 'user_id' => $userId])->first();
        if ($productExist) {
            $productExist->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Favourite::create(['product_id' => $productId, 'user_id' => $userId]);
            return response()->json(['status' => 'added']);
        }
    }

    public function getFavourite(Request $request)
    {
        $userId = Auth::id();
        $favouriteProductIds = Favourite::where('user_id', $userId)->pluck('product_id');
        // dd($favouriteProductIds)->get();
        $favouriteProducts = Product::whereIn('id', $favouriteProductIds)->get();
        // dd($favouriteProducts);
        return view('customer.favourite', ['favouriteProducts' => $favouriteProducts]);
    }

    // public function getFavourite(Request $request)
    // {
    //     $userId = Auth::id();
    //     $favourites = Favourite::select('carts.*', 'products.product_name', 'products.price', 'products.thumbnail_url')
    //         ->join('products', 'products.id', '=', '.product_id')
    //         ->where('user_id', $userId)
    //         ->with('products')
    //         ->get();
    //     dd($favourites);
    //     return view('customer.favourite', ['favourites' => $favourites]);
    // }

    public function addCart(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->productId;
        $quantity = $request->quantity;
        $size = $request->size;
        $color = $request->color;
        // dd($request->input());
        $cartExist = Cart::where(['product_id' => $productId, 'user_id' => $userId, 'size' => $size, 'color' => $color])->first();

        if ($cartExist) {
            $cartExist->quantity += 1;
            $cartExist->update();
            return redirect()->route('customer.cart')->with('Success', 'Cập nhật số lượng sp thành công');
        } else {
            $data = [
                'product_id' => $productId,
                'user_id' => $userId,
                'quantity' => 1,
                'size' => $size,
                'color' => $color
            ];
            // dd($data);
            Cart::create($data);
            return redirect()->route('customer.cart')->with('Success', 'Thêm thành công sp vào giỏ');
        }

        return redirect()->back()->with('Fail', 'Lỗi, vui lòng thử lại');
    }

    public function updateCart(Request $request)
    {
        $cartId = $request->id;
        $quantity = max(1, (int)$request->quantity);
        $cart = Cart::findOrFail($cartId);
        // dd($cart);
        if ($cart) {
            $cart->quantity = $quantity;
            $cart->save();
        }
        return redirect()->back()->with('ok', 'Cập nhật số lượng sp thành công!!');
    }

    public function deleteCart(Request $request)
    {
        $cartId = $request->input('id');
        $userId = $request->input('userId');
        Cart::where(['id' => $cartId, 'user_id' => $userId])->delete();
        return redirect()->back()->with('ok', 'Xóa sp thành công!!');
    }
    public function getCart(Request $request)
    {
        $userId = Auth::id();
        $carts = Cart::select('carts.*', 'products.product_name', 'products.price', 'products.thumbnail_url', DB::raw('(carts.quantity * products.price) AS total_price'))
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->where('user_id', $userId)
            ->with('products')
            ->get();
        // dd($carts);
        return view('customer.cart', ['carts' => $carts]);
    }

    public function addOrder(Request $request)
    {
        $productId = $request->productId;
        $size = $request->input('size');
        $color = $request->input('color');
        $selectedProducts = $request->input('selectedProducts');
        $userId = Auth::id();
        // dd($request->input('orderItem'), $request->input('orderItems'));   
        if ($productId) {
            $orderItem = Product::with('quantities')->findOrFail($productId);
            // dd($orderItem);
            $orderItem->size = $size;
            $orderItem->color = $color;
            $orderItem->quantity = 1;
            $totalAmount = $orderItem->price;
            return view('customer.order', ['orderItem' => $orderItem, 'totalAmount' => $totalAmount, 'orderItems' => null]);
        } elseif ($selectedProducts) {
            $selectedProducts = explode(',', $selectedProducts);
            $orderItems = Cart::with('products')->whereIn('carts.id', $selectedProducts)->where('user_id', $userId)->get();
            $totalAmount = 0;
            foreach ($orderItems as $item) {
                $totalAmount += $item->products->price * $item->quantity;
            }
            return view('customer.order', ['orderItems' => $orderItems, 'totalAmount' => $totalAmount, 'orderItem' => null]);
        }
    }

    public function placeOrder(Request $request)
    {
        // $data = $request->json()->all();
        // dd($data);
        // $formData = $request->all(); //thay thế toàn bộ biến truyền từ view sang controler: $payment_method, $productId, $selectedSize, ....
        $userId = Auth::id();
        $productId = $request->input('productId');
        $size = $request->input('size');
        $color = $request->input('color');
        $selectedProducts = $request->input('selectedProducts');
        $payment_method = $request->input('payment_method');
        $totalAmount = $request->input('totalAmount');
        $orderItem = $request->input('orderItem');

        if (is_string($orderItem)) {
            $orderItem = json_decode($orderItem, true);
        }
        $orderItems = $request->input('orderItems');
        if (is_string($orderItems)) {
            $orderItems = json_decode($orderItems, true);
        }

        if (is_null($totalAmount)) {
            return response()->json(['message' => 'Total amount is required'], 400);
        }
        // dd($request->all());

        DB::beginTransaction();

        $orderDate = now();
        $receiveDate = $orderDate->copy()->addDays(5)->format('Y-m-d');
        // Tạo đơn hàng mới
        $order = Order::create([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'payment_method' => $payment_method,
            'date_order' => $orderDate,
            'received_date' => $receiveDate,
        ]);
        // dd($order);

        // Lưu các mục đơn hàng
        if ($orderItems) {
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'size' => $item['size'],
                    'color' => $item['color'],
                    'quantity' => $item['quantity'],
                ]);
            }
            // dd($orderItems);
        } elseif ($orderItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'size' => $size,
                'color' => $color,
                'quantity' => 1,
            ]);
            // dd($orderItem);
        }

        DB::commit();
        return redirect()->route('customer.finishOrder', ['orderId' => $order->id]);
    }

    public function finishOrder(Request $request)
    {
        $orderId = $request->orderId;
        $order = Order::findOrFail($orderId);
        $orderItems = OrderItem::with('order')->where('order_id', $orderId)->get();
        $orderItem = OrderItem::with('order')->where('order_id', $orderId)->get();
        // dd($order);
        return view('customer.finishOrder', [
            'order' => $order,
            'orderItems' => $orderItems,
            'orderItem' => $orderItem
        ]);
    }

    public function cartCount(Request $request)
    {
        $userId = Auth::id();
        $cartCount = Cart::where(['user_id' => $userId])->count();
        return response()->json(['cartCount' => $cartCount]);
    }
}
