@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-6 offset-md-3 col-xs-12">

            @if ($message)
                <div class="alert alert-danger">
                    <strong>Error!</strong>
                    <p>{{$message}}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="col-md-6 offset-md-3 col-xs-12">
            <div class="alert alert-info">
                <p>برای ایجاد تراکنش باید مقادیر زیر را کامل
                    کنید.</p>
            </div>
            <form action="{{url('order/store')}}" method="POST" id="snedPaymentTara">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>مبلغ (ریال):</strong>
                            <input type="text" name="amount" class="form-control" placeholder="12000">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>توضیحات:</strong>
                            <textarea class="form-control" style="height:50px" name="description"
                                      placeholder="توضیحات خرید"></textarea>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" id="submitbtn" class="btn btn-primary">ارسال</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <script>
        $(document).on('submit', '#snedPaymentTara', function (e) {
            $("#submitbtn"  ).attr("disabled", true);
        });
            </script>
@endsection
