@extends('master')
@section('content')

    <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">    PIC's Detail   </b>
                   <!-- approve.approveDetail  -->

                </div>
                 <div style="margin-top: -4%; float: center ;margin-right:  4%;" >
                   
 </div>
<div class="container">
      
<div class="row">

 <div class="col-sm-11"  > 
   @if(session()->has('message'))
            <div style="text-align: center;" class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
 <div class="col-sm-2"  > </div> 
 <div class = "col-sm-9"  >
  <table class="table table-hover table-bordered" >
     <p align="left"><a  href="{{action('PicController@edit', $pic->id)}}">Edit </a> </p>
<p style="margin-top: -2.4%" align="right">
   <?php 
    if (Session::has('ssPic')) {
                   $url = Session::get('ssPic');
              echo "<a href=".$url[0]."> Back</a> ";
                    } else
          {
                  
              echo "<a href='javascript:history.back()'>Back &nbsp;&nbsp;&nbsp;  </a>  ";
                    }
     ?>

 </p>
          
  <label for="lgFormGroupInput" class="col-sm-12 col-form-label col-form-label-sm">
    <a href="{{action('DivisionController@show', $pic->div_ID)}}">{{$pic->clientName}}  &#95; {{$pic->divisionName}}</a>
    
  </label>
   <input name="_method" type="hidden" value="PATCH">
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			PIC's Name (First Middle Last Name )</font></strong></td> 
            <td class="col-md-1" >
			{{$pic->firstName}}&nbsp;{{$pic->midleName}}&nbsp;{{$pic-> lastName}}
			</td> 
			 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">PIC's Name （JP/VN）</font></strong></td> 
            <td class="col-md-1" >
			{{$pic->firstNameJ}}&nbsp;{{$pic-> midleNameJ}}&nbsp;{{$pic-> lastNameJ}}
			</td> 
		</tr>

				<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Department</font></strong></td> 
            <td class="col-md-1" >
			{{$pic->department}}
			</td> 
			 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Position</font></strong></td> 
            <td class="col-md-1" >
			{{$pic->position}}
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Email</font></strong></td> 
            <td class="col-md-1" >
			{{$pic->email}}
			</td> 
			 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">TEL</font></strong></td> 
            <td class="col-md-1" >
			{{$pic->tell}}
			</td> 
		</tr>
		<tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			MOBILE</font></strong></td> 
            <td class="col-md-1" >
			{{$pic->mobile}}
			</td> 
			 <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Status Flag</font></strong></td> 
            <td class="col-md-1" >
			{{$pic->status}}
			</td> 
		</tr>
    <tr >
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Mail Magazine</font></strong></td> 
            <td class="col-md-1" >
      {{$pic->magazine}}
      </td> 
      </tr>

				
        <tr >
            <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
			Note</font></strong></td> 
            <td class="col-md-1" colspan="3" >
			{{$pic->add}}
			</td> 
			
		</tr>

	    </table>
      <div  style="align-items: left">


 @if(Auth::user()->role > 0)
          <form class="delete" action="{{action('PicController@destroy', $pic->id)}}" method="post">
            {{csrf_field()}}
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-default" style=" background-color: #DCDCDC;" type="submit">Delete PIC</button>
          </form>
          @endif
</div> 
 </div>
</div>
 
</div> 

 
       
</div>


<iframe id="myFrame" style="width:0;height:0;border:0; border:none;" src="/default.asp"></iframe>
 <input type="text" style="width: 20%" class="hidden" id="nationalID" placeholder="tell" name="national" 
        value="{{$pic->nationID}}">
 <script type="text/javascript">
  document.getElementById("myFrame").onload = function() {myFunction()};
  function myFunction() {
    

  var nationalflag=  document.getElementById("nationalID").value;
 //alert(nationalflag);
  // alert(nationalflag);
  if (nationalflag>1){
          
            document.getElementById("provinceID").style.display="none";
            document.getElementById("districtID").style.display="none";
           
          // 
          } else
          { document.getElementById("provinceID").style.display="block";
            document.getElementById("districtID").style.display="block";
            
        }
}
 $(".delete").on("submit", function(){
        return confirm("Do you want to delete this PIC ?");
    });
</script>
@endsection