@extends('layouts.master')
@section('content')
    <div class="row">

        <div class="col-md-6 offset-md-3 col-xs-12">

                <div class="alert alert-{{ $status }}">
                    <strong>توجه!</strong>
                     <p>{{ $message }}</p>
                </div>
            <br/>
            @if (isset($order))
                <div class="row">
                 <table class="table    table-bordered">
                     <tr>
                         <th class="text-center " colspan="2"> گزارش سفارش</th>
                     </tr>
                     <tr>
                         <td style="width: 150px;"><b>کد سفارش</b></td>
                         <td>{{$order->id}}</td>
                     </tr>
                     <tr>
                         <td><b>مبلغ سفارش</b></td>
                         <td>{{$order->amount}}</td>
                     </tr>
                     <tr>
                         <td><b>وضعیت سفارش</b></td>
                         <td>{{$order->status}}</td>
                     </tr>
                     <tr>
                         <td><b>توضیحات سفارش</b></td>
                         <td>{{$order->description}}</td>
                     </tr>
                 </table>
                </div>
            @endif
            <a class="btn btn-primary" href="{{url('/')}}">بازگشت به فرم پرداخت </a>
        </div>
    </div>

@endsection
