<!DOCTYPE html>
<html>
<head>
    <title>Laravel 5.3 - Form Validation</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>

<div class="container">
    <h2>Form Validation</h2>
    <form method="POST" action="/form-validation" autocomplete="off">

        @if(count($errors))
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <br/>
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter First Name" value="{{ old('firstname') }}">
                    <span class="text-danger">{{ $errors->first('firstname') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter Last Name" value="{{ old('lastname') }}">
                    <span class="text-danger">{{ $errors->first('lastname') }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Email" value="{{ old('email') }}">
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('mobileno') ? 'has-error' : '' }}">
                    <label for="mobileno">Mobile No:</label>
                    <input type="text" id="mobileno" name="mobileno" class="form-control" placeholder="Enter Mobile No" value="{{ old('mobileno') }}">
                    <span class="text-danger">{{ $errors->first('mobileno') }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" >
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Enter Confirm Passowrd">
                    <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
                    <label for="details">Details:</label>
                    <textarea name="details" id="details" class="form-control" placeholder="Enter Details">{{ old('details') }}</textarea>
                    <span class="text-danger">{{ $errors->first('details') }}</span>
                </div>
            </div>
        </div>
            <p>Date: <input type="text" id="datepicker" name="datep"></p>
        <div class="form-group">
            <button class="btn btn-success">Submit</button>
        </div>

    </form>
</div>
<script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  </script>
</body>
</html>