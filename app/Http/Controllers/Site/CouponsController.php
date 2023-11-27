<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Helper;

class CouponsController extends Controller
{
    public function addCoupon(Request $request)
    {
        $code = "";
        $msg = "";
        $discount = 0;
        $subTotal = str_replace('.','',Cart::subTotal());
        $coupon = Coupon::where('code',$request->code)->first();
        if($coupon){
            $discount = $coupon->discount($subTotal);
            // Cart::discount($discount);
            $code = $coupon->code;
            // Cart::setGlobalDiscount($discount);
        }
        else{
            $msg = "Mã giãm giá này không tồn tại hoặc đã hết hạn";
        }

        return response()->json([
            'code' => $code,
            'discount' => Helper::formatCurrency($discount),
            'msg' => $msg
        ]);

    }
}
