<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('order.index', [
            'message' => "",
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([

            'amount' => 'required'
        ]);

        $order = Order::create($request->all());
        $params = array(
            'username' => (string)env('TARAPAY_USERNAME'),
            'password' => (string)env('TARAPAY_PASSWORD'),
            'taracode' => (string)env('TARAPAY_GATEWAY'),
            'amount' => (string)$order->amount,
            'cash_ref_no' => (string)$order->id,
            'landing_url' => (string)url("/order/callback/{$order->id}"),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('TARAPAY_ENDPOINT'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        $result = curl_exec($ch);
        $result = json_decode($result);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (isset($result->TOKEN)) {
            $token = $result->TOKEN;
            $token_endpoint = env('TOKEN_ENDPOINT');
            $link = str_replace('@TOKEN', $token, $token_endpoint);
            return Redirect::to($link);


        } else {
            return view('order.index', [
                'message' => "هنگام اتصال به درگاه تارا مشکلی پیش آمده است",

            ]);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    public function callback($id)
    {
        $tokenid = "";
        $order_id = $id;
        if (isset($_GET['tokenid'])) {
            $tokenid = $_GET['tokenid'];
        }
        $findOrder = Order::find($id);
        if ($findOrder && !empty($tokenid)) {
            $addPayment = new Payment();
            $addPayment->order_id = $id;
            $addPayment->token = $tokenid;
            $addPayment->amount = $findOrder->amount;
            $addPayment->save();


            $params = array(
                'tokenid' => (string)$tokenid,
                'cash_desk_id' => (string)env('TARAPAY_GATEWAY'),
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, env('VERIFY_ENDPOINT'));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));
            $result = curl_exec($ch);
            $result = json_decode($result);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $status = "danger";
            $MSG = empty($result[0]->MSG) ? NULL : $result[0]->MSG;
            $RNO = empty($result[0]->RNO) ? NULL : $result[0]->RNO;
            if ($result[0]->MSG != 100) {
                $comment ="مشکلی در تایید پرداخت شما صورت گرفته است.";
                $findOrder->status = -1;
                $findOrder->description = $comment;
                $findOrder->save();
                $addPayment->description = $RNO;
                $addPayment->status = -1;
                $addPayment->save();

            } else {
                $status = "success";
                $comment = "تراکنش با موفقیت پرداخت شد.";
                $findOrder->status = 1;
                $findOrder->description = $comment . "<br/>" . $RNO;
                $findOrder->save();
                $addPayment->description = $RNO;
                $addPayment->status = 1;
                $addPayment->save();

            }
        } else {
            $comment = 'شماره سفارش اشتباه است.';
        }


        return view('order.callback', [
            'message' => $comment,
            'status' => $status,
            'order' => $findOrder,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
