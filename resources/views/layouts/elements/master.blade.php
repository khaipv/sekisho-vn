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
     <style>
body {
  font-family: "Lato", sans-serif;
}

.sidenav {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #FFE8E5;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidenav a:hover {
  color: #f1f1f1;
}

.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
</style>
    </head>

<body>
    <!--/header-->
    @include("layouts.elements.top")
    <!--/slider-->
   
    <section>
        <div class="container">
            <div class="row">
              
              <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-1 padding-left" style="margin-left: -86px;">
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
    <script>
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
</script>
   
</body>
</html>