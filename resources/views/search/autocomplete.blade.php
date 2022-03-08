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
<table class="table table-hover table-bordered " content ="charset=UTF-8" >
    
    <thead >
        <tr>
             <td nowrap><strong>案件コード</strong></td> 
            <td nowrap><strong>取引先名</strong></td> 
            <td nowrap><strong>案件名</strong></td> 
            <td nowrap><strong>案件種別</strong></td> 
            <td nowrap><strong>就労日</strong></td> 
            <td nowrap><strong>紹介期日</strong></td> 
            <td nowrap><strong>担当者</strong></td> 


        </tr>
    </thead>
    <tbody>
@foreach($results as $results)
   <tr>
    <td>{{$loop->iteration}}</td>
      <td>{{ $results ['value'] }}</td>
      <td><a href="{{action('DivisionController@show', $results ['id'])}}">{{ $results ['value'] }}</a></td>
      
                               
   </tr>
 @endforeach


     <tr>
      <td colspan="13">
     
      </td>
    </tr>
    </tbody>
</table>
    
</div>




</body>
</html>