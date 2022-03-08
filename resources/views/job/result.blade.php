@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Close  </b>
                   <!-- approve.approveDetail  -->
                </div>
<div class="container">
  <form method="post" action="{{url('education')}}" name="formid"  onsubmit="return do_something()">
  
  </form>
</div>
<script type="text/javascript">
//   $('form').on('submit', function(event) {
//     event.preventDefault();
   
    
//     this.submit(); //now submit the form
//     window.opener.location.reload();
//       document.getElementById("refeshbtn").click();
// });

   
     localStorage.setItem('result', "4");
window.opener.location.reload();
 window.close();

function do_something(){
  // window.opener.location.reload();
  alert (window.opener.location);
 
  //window.close();
   // Do your stuff here
    
   window.close();

  
}
  </script>
@endsection