<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Package;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('site.checkout.index');
    }

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function createOrder($oderId, $paymentId, $totalPrice, $content, $linkPayment, $status = 0)
    {
        $order = new Order;
        $order->order_code = $oderId;
        $order->user_id = Auth::user()->id;
        $order->payment_id = $paymentId;
        $order->total_price = $totalPrice;
        $order->content = $content;
        $order->link_payment = $linkPayment;
        $order->status = $status;

        $order->save();

        $carts = Cart::content();
        foreach ($carts as $key => $item) {
            $orderDetail = new OrderDetail;
            $orderDetail->order_id = $order->id;
            $orderDetail->package_id = $item->id;
            $orderDetail->qty = $item->qty;
            $orderDetail->price = $item->total; //gia cua san pham x vơi qty
            $orderDetail->save();
        }
    }

    public function checkoutMoMo($paymentId)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $amount = str_replace('.', '', Cart::total());
        $orderId = time() . "";
        $redirectUrl = route('checkout.webhook', $paymentId);
        $ipnUrl = route('checkout.webhook', $paymentId);
        $extraData = "";
        $requestId = time() . "";
        $requestType = "payWithATM";
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json
        $createOrder = $this->createOrder($orderId, $paymentId, $amount, $orderInfo, $jsonResult['payUrl']);
        // if($createOrder){
        //     return redirect()->to($jsonResult['payUrl']);
        // }else{
        //     return redirect()->back()->with(["type" => "danger", "flash_message" => "CÓ lỗi xãy ra!"]);
        // }
        //Just a example, please check more in there
        Cart::destroy();
        return $jsonResult['payUrl'];
    }

    public function checkout(Request $request)
    {
        $paymentId = $request->payment_method;
        $payment = PaymentMethod::find($paymentId);

        if ($payment) {

            switch ($paymentId) {
                case 1:
                    return redirect()->to($this->checkoutMoMo($paymentId));
                    break;
                case 2:
                    # code...
                    break;
                default:
                    return redirect()->back()->with(["type" => "danger", "flash_message" => "Chúng tôi chưa hỗ trợ PTTT này"]);
                    break;
            }
        } else {
            return redirect()->back()->with(["type" => "danger", "flash_message" => "Có lỗi xãy ra!"]);
        }
    }

    public function updateOrder($order_code)
    {
        $order = Order::where('order_code', $order_code)->where('status', 0)->first();
        if ($order) {
            $order->status = 1;
            if ($order->save()) {
                $orderUpdate = Order::where('order_code', $order_code)->where('status', 1)->first();
                $orderDetail = OrderDetail::where('order_id', $orderUpdate->id)->get();
                
                foreach ($orderDetail as $key => $value) {
                    
                    $package = Package::find($value->package_id);
                    $start_time = Carbon::now();
                    $curr_time = Carbon::now();
                    switch ($package->expiry) {
                        case 0:
                            $end_time = $curr_time->addDay($value->qty);
                            break;

                        case 1:
                            $end_time = $curr_time->addMonth($value->qty);
                            break;

                        case 2:
                            $end_time = $curr_time->addYear($value->qty);
                            break;

                        default:
                            $end_time = '';
                            break;
                    }
                    
                    $service_user = DB::table('user_package')->insert([
                        'user_id' => $orderUpdate->user_id,
                        'package_id' => $value->package_id,
                        'status' => 1,
                        'start_time' => $start_time,
                        'end_time' => $end_time
                    ]);
                }
            }
            return redirect()->route('home');
        } else {
            return "Luu that bai";
        }
    }

    public function webhook(Request $request, $paymentId)
    {
        // ?partnerCode=MOMOBKUN20180529&orderId=1661866315&requestId=1661866315&amount=1579000&orderInfo=Thanh+toán+qua+MoMo
        //&orderType=momo_wallet&transId=2722713142&resultCode=0&message=Successful.&payType=napas
        //&responseTime=1661866457628&extraData=&signature=3c93f8aa13351cea9e5074749fe9364d0d6d516b81813351758a96edd4ca9d9f
        switch ($paymentId) {
            case 1:
                return $this->updateOrder($request->orderId);
                break;

            default:

                break;
        }
    }
}
