@extends('master')
@section('content')

<div class="container">
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Successful Updated </div>

                <div class="panel-body">
                   
                      <form method="POST" action="{{action('ProfileController@update',1)}}" >
                          <input name="_method" type="hidden" value="PATCH">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name"
                                 value="{{ Auth::user()->name }}" disabled  autofocus>

                              
                            </div>
                        </div>
                         <div class="form-group{{ $errors->has('userName') ? ' has-error' : '' }}">
                            <label for="userName" class="col-md-4 control-label">Username</label>
                            <div class="col-md-6">
                                <input id="userName" type="text" class="form-control" name="userName" value="{{ Auth::user()->userName }}" disabled >
                              
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" disabled >

                              
                            </div>
                        </div>

                       

                     
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<br>


@endsection