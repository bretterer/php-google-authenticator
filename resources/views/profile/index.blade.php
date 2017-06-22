@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Google Authenticator</div>

                    <div class="panel-body">
                        @if(auth()->user()->enrolled)
                            Enrolled into Google Authenticator
                        @else
                            <a href="/profile/enroll" class="btn btn-primary">Enroll into Google Authenticator</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
