<!DOCTYPE html>
<html>
<head>
    <title>Laravel Ajax Validation Example</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/province.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/provi.js') }}"></script>
    <link href={{URL::asset('css/color.css')}}  rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Laravel Ajax Validation</h2>
<h1> My colorfull H1 tags </h1>
    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul></ul>
    </div>

    <form>
        {{ csrf_field() }}
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="first_name" class="form-control" placeholder="First Name">
        </div>

        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="last_name" class="form-control" placeholder="Last Name">
        </div>

        <div class="form-group">
            <strong>Email:</strong>
            <input type="text" name="email" class="form-control" placeholder="Email">
        </div>

        <div class="form-group">
            <strong>Address:</strong>
            <textarea class="form-control" name="address" placeholder="Address"></textarea>
        </div>

        <div class="form-group">
            <button class="btn btn-success btn-submit">Submit</button>
        </div>
    </form>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        $(".btn-submit").click(function(e){
            e.preventDefault();

            var _token = $("input[name='_token']").val();
            var first_name = $("input[name='first_name']").val();
            var last_name = $("input[name='last_name']").val();
            var email = $("input[name='email']").val();
            var address = $("textarea[name='address']").val();

            $.ajax({
                url: "/my-form",
                type:'POST',
                data: {_token:_token, first_name:first_name, last_name:last_name, email:email, address:address},
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        alert(data.success);
                    }else{
                        printErrorMsg(data.error);
                    }
                }
            });

        }); 

        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }
    });

</script>
<div class="container">
    <h1>Dynamic Dependant Select Box using JQuery Ajax Example</h1>

    <form method="" action="">
        {{ csrf_field() }}
        <div class="form-group">
            <label>Select Country:</label>
            <select class="form-control" name="province">
                <option value="">---</option>
                @foreach($countries as $country)
                    <option value="{{$country->Id}}">{{ $country->Name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Select District:</label>
            <select class="form-control" name="district">
            </select>
        </div>
        <div class="form-group">
            <label>Select Ward:</label>
            <select class="form-control" name="slbWard">
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-success" type="submit">Submit</button>
        </div>
    </form>

</div>



</body>
</html>