<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Helper;

class CartSiteController extends Controller
{
    public function index()
    {
        return "Cart";
    }

    
    public function getCartModal()
    {
        $cart_content = Cart::content();
        return view('site.ajax.ajax_cart_modal',compact('cart_content'));
    }

    public function deleteCart($rowId)
    {
        Cart::remove($rowId);
        
        return response()->json([
            "msg" => "Đã xóa sản phảm ra khỏi giỏ hàng",
            "status" => 1,
        ]);
    }

    public function updateCart(Request $request)
    {
        $qty = $request->qty;
        $rowId = $request->rowId;
        $msg = "";
        $status = 0;
        $data = "";
        $price = "";
        if(is_numeric($qty) && $qty > 0){
            Cart::update($rowId, $qty);
            $msg = "Cập nhập giỏ hàng thành công";
            $status = 1;
            $data = Cart::get($rowId);
            $price = Helper::formatCurrency($data->qty * $data->price);
        }elseif(is_numeric($qty) && $qty == 0){
            Cart::update($rowId, $qty);
            $status = 2;
        }
        else{
            $msg = "Số lượng phải là số & >= 0";
        }

        return response()->json([
            "price" => $price,
            "status" => $status,
            "msg" => $msg
        ]);
    }

    public function add(Request $request)
    {
        $id = $request->id;
        $status = 0;
        $msg = '';
        if (auth()->guard('web')->check() && auth()->guard('web')->user()->type == 2) {
            if(is_numeric($id) && $id > 0){
                $package = Package::findOrFail($id);
                $price = $package->new_price != '' ? $package->new_price : $package->price;
                Cart::add($package->id, $package->title, 1, $price, 550);
            }
            $cart_content = Cart::content();
            $status = 1;
            $msg = "Thêm vào giỏ hàng thành công!";
        }else{
            $msg = "Vui lòng đăng nhập NTD để tiếp tục";
        }

        return response()->json([
            "status" => $status,
            "content" => $cart_content,
            "msg" => $msg
        ],200);
    }

    public function getCartData(){
        $price = str_replace('.','',Cart::subTotal());
        $total = str_replace('.','',Cart::total());
        return response()->json([
            "count" => Cart::count(),
            "total" => Helper::formatCurrency($total),
            "price" => Helper::formatCurrency($price),
            "status" => 1,
        ],200);
    }

}
