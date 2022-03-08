<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
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
    <!--/header-->
    @include("layouts.elements.topTime")
    <!--/slider-->
   
    <section>
        <div class="container">
            <div class="row">
              
              <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-1 padding-left" style="margin-left:  -86px;">
                    @include("layouts.elements.sidebar")
                </div>
                <div class="col-sm-10 padding-right" style="margin-left: -2%;">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>
            </div>
        </div>
    </section>
    
   
</body>
</html>