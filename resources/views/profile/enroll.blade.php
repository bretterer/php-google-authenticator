@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Google Authenticator</div>

                    <div class="panel-body">
                        Please scan this code in your google authenticator application.
                        <br>
                        <img src="{{$qrUrl}}" alt="" title="" />

                        <div>
                            <h3>Enter the code and click next</h3>
                            <form method="POST">
                                {{csrf_field()}}
                                <input type="text" name="otpVerify" placeholder="verification code"/>
                                <input type="submit" value="Next">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
