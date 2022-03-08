<!-- master.blade.php -->

<!doctype html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title> @yield('title')</title>
    <link href="{{ asset('layouts/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/css/price-range.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/css/animate.css') }}" rel="stylesheet">
	<link href="{{ asset('layouts/css/main.css') }}" rel="stylesheet">
	<link href="{{ asset('layouts/css/responsive.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('layouts/images') }}/ico/favicon.ico">
</head><!--/head-->

<body>
	<!--/header-->
	@include("layouts.elements.top")
	<!--/slider-->
	@include("layouts.elements.slide")
	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					@include("layouts.elements.sidebar")
				</div>
				<div class="col-sm-9 padding-right">
					@yield('content')
				</div>
			</div>
		</div>
	</section>
	@include("layouts.elements.footer")
	<!--/Footer-->
    <script src="{{ asset('layouts/js/jquery.js') }}"></script>
	<script src="{{ asset('layouts/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('layouts/js/jquery.scrollUp.min.js') }}"></script>
	<script src="{{ asset('layouts/js/price-range.js') }}"></script>
    <script src="{{ asset('layouts/js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('layouts/js/main.js') }}"></script>
</body>
</html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Candidate Management </title>
        <!-- Fonts -->
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('css/master.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
         <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    </head>
    <body>
    
    <ul id="nav">
            <li><a href="#">Company</a>
                <ul>
                    <li><a href="{{ URL::to('client') }}">Company</a></li>
                    <li><a href="{{ URL::to('client/create') }}">Create New</a></li>

                    
                    
                </ul>
            </li>
              <li><a href="#">Division</a>
                <ul>
                           <li><a href="{{ URL::to('division') }}">Division</a></li>
                            <li><a href="{{ URL::to('division/create') }}">Create New</a></li>
                </ul>
            </li>
            <li><a href="#">Order</a>
                <ul>
                    <li><a href="{{ URL::to('order') }}">Order</a></li>
                    <li><a href="{{ URL::to('order/create') }}">Create New </a></li>
                </ul>
            </li>
            <li><a href="#">PIC</a>
                <ul>
                   <li><a href="{{ URL::to('pic') }}">PIC</a></li>
                     <li><a href="{{ URL::to('pic/create') }}">New PIC</a></li>
                    
                </ul>
            </li>
                 <li><a href="#">Candidate</a>
                <ul>
                   <li><a href="{{ URL::to('canSearch1') }}">Candidate List</a></li>
                    <li><a href="{{ URL::to('candidate/create') }}">Create New </a></li>
                   
                    
                </ul>
            </li>
            <li id="navus"><a  href="#">{{ Auth::user()->name }}</a>
             <ul>
                <li><a href="{{ URL::to('time') }}">Time Keeper</a></li>
               <li><a href="{{ URL::to('reportSearch') }}">Report</a></li>

                 <li><a href="{{ URL::to('masterSearch') }}">Master</a></li>
                      <li ><a  href="{{ url('/editProfile') }}"> Edit Profile </a></li>
                <li ><a  href="{{ url('/logout') }}"> Logout </a></li>
             </ul>
            </li>

            
            
        </ul>

        <br><br>
        @yield('content')
    </body>
</html>
