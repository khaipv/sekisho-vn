@extends('layouts.elements.master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Report's Detail
  </b>
                   <!-- report.detailReport  -->
                </div>
<div class="container">

  @if($upd>0)
            <div style="text-align: center;" class="alert alert-success">
               Succes! Report was deleted
            </div>
              @elseif($upd < 0)
               <div style="text-align: center;" class="alert alert-success">
               Succes! Report was updated
            </div>
      @else   
       <div style="text-align: center;"  class="alert">
              
            </div>   
        @endif
 <div class="col-sm-12"  > 
 <div class="col-sm-1"  > </div> 
 <div class = "col-sm-10"  >
          @if($reports->userID == Auth::user()->id)
          <p style="margin-top: -2.4%;margin-bottom: 0%" align="left">
       <a  href="{{action('DailyReportController@edit', $reports->id)}}">Edit</a> 
          </p>
         @endif
          <p style="margin-top: -2.4%" align="right">
 <?php 
    if (Session::has('ssReport')) {
                   $url = Session::get('ssReport');
              echo "<a href=".$url[0]."> Back</a> ";
                    } else {
                      echo   "<a href='javascript:history.back()'>Back &nbsp;&nbsp;&nbsp;  </a> ";

                    }
        
     ?>
  
  
 </p>
 <table class="table table-hover table-bordered" >
   
	
     <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Creator</font></strong></td> 
            <td class="col-md-7" colspan="3"   >
     {{ $reports->userName}}
      </td> 
     </tr>
       <tr>
       <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">Date, Time</font></strong></td> 
            <td colspan="3"   >

        {{$reports->date}}
         {{substr($reports->from, 0, 5)}} {{substr($reports->to, 0, 5)}}
      </td> 
     </tr>
    <tr>
          <td  style="background: #7EC0EE;width: 20%" ><strong><font color="556B2F">
          Company </font></strong></td> 
          {{csrf_field()}}
          <td colspan="3"  >
           <div style="overflow-y: auto;white-space: pre-wrap;">@if($reports->companyID<>10000){{$reports->companyname }}      @else{{$reports->other }}
              @endif
           </div>
      </td> 
               
         
             
          </td> 
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Division </font></strong></td> 
            <td colspan="3"  >{{$reports->divisionname}} 
      </td> 
    </tr>
     
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Customer</font></strong></td> 
            <td  >
              <div style="overflow-y: auto;white-space: pre-wrap;">{{$reports->customer}} 
            </div>
      
      </td> 
               
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Visitor</font></strong></td> 
            <td  >
                <div style="overflow-y: auto;white-space: pre-wrap;">{{$reports->visitor}} 
               </div>
        
      </td> 
               
    </tr>
    <tr>
      <td  style="background: #7EC0EE" ><strong><font color="556B2F">Kind/ Way</font></strong></td> 
      <td  >
      {{$reports->kindname}} / {{$reports->wayname}} 
      </td> 
      
    </tr>
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">
           Title</font></strong></td> 
            <td  >
      {{$reports->title}}
    
      </td> 
    </tr>
   
    <tr>
            <td  style="background: #7EC0EE" ><strong><font color="556B2F">Detail</font></strong></td> 
            <td  >
       
        <div style="overflow-y: auto;white-space: pre-wrap;">{{$reports->detail}}</div>
      </td> 
               
  
       
    </tr>
     
      </table>

   <form class="delete" action="{{action('DailyReportController@destroy', $reports->id)}}" method="post">
            {{csrf_field()}}
             @if($reports->userID == Auth::user()->id)
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-default" style=" background-color: #DCDCDC;" type="submit" id="deleteBtn">Delete</button>
             @endif
          </form> 

   </div>
      </div>
 
	<br>
	<br>
	<br>
 

</div>
<script type="text/javascript">
      function validateHhMm(inputField) {
        var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(inputField.value);

        if (isValid) {
            inputField.style.backgroundColor = '#bfa';
        } else {
            inputField.style.backgroundColor = '#fba';
        }

        return isValid;
    }

       var url = "{{ url('/showDivision') }}";
    
    $("select[name='client']").change(function(){
        var province_id = $(this).val();

        var token = $("input[name='_token']").val();

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                id: province_id,
                _token: token
            },
            success: function(data) {
            
                $("select[name='division'").html('');
                $.each(data, function(key, value){
                    $("select[name='division']").append(
                        "<option value=" + value.id + ">" + value.divisionname + "</option>"
                    );
                      $("#workingPlaceID").val(data[0].address);

                });
            }
        });
    });

      var url2 = "{{ url('/showAddressByDiv') }}";
    
    $("select[name='division']").change(function(){

        var division_id = $(this).val();

        var token = $("input[name='_token']").val();

        $.ajax({
            url: url2,
            method: 'POST',
            data: {
                id: division_id,
                _token: token
            },
            success: function(data) {
            
               

                $.each(data, function(key, value){
                 $("#workingPlaceID").val(value.address);
                

                });
            }
        });
    });
        
   $( function() {
    $( "#datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '2017:2050',
        dateFormat : 'yy-mm-dd',
       
    });
  } );
      $( function() {
    $( "#workingDate" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '2017:2050',
        dateFormat : 'yy-mm-dd',
        
    });
  } );
       $( function() {
    $( "#introducePriority" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '2017:2050',
        dateFormat : 'yy-mm-dd',
        
    });
  } );
       $( function() {
    $( "#invoiceDateID" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '2017:2050',
        dateFormat : 'yy-mm-dd',

        
    });
  } );

function validateNum(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
var input = document.getElementById('introduceFeeID');
  input.addEventListener('keyup', function(e)
  {
    input.value = format_number(this.value);
  });
  

  
var inputPartnerFee = document.getElementById('partnerFeeID');
  inputPartnerFee.addEventListener('keyup', function(e)
  {
    inputPartnerFee.value = format_number(this.value);
  });
  var salaryFromval = document.getElementById('salaryFromID');
  salaryFromval.addEventListener('keyup', function(e)
  {
    salaryFromval.value = format_number(this.value);
  });

       var unitSaToval = document.getElementById('salaryToID');
  unitSaToval.addEventListener('keyup', function(e)
  {
    unitSaToval.value = format_number(this.value);
  });



  
  /* Function */
  function format_number(number, prefix, thousand_separator, decimal_separator)
  {
    var thousand_separator = thousand_separator || ',',
      decimal_separator = decimal_separator || '.',
      regex   = new RegExp('[^' + decimal_separator + '\\d]', 'g'),
      number_string = number.replace(regex, '').toString(),
      split   = number_string.split(decimal_separator),
      rest    = split[0].length % 3,
      result    = split[0].substr(0, rest),
      thousands = split[0].substr(rest).match(/\d{3}/g);
    
    if (thousands) {
      separator = rest ? thousand_separator : '';
      result += separator + thousands.join(thousand_separator);
    }
    result = split[1] != undefined ? result + decimal_separator + split[1] : result;
    return prefix == undefined ? result : (result ? prefix + result : '');
  };
       function OnChangeCheckbox (invoiceCK) {
            if (invoiceCK.checked) {
     var currentDt = new Date();
    var mm = currentDt.getMonth() + 1;
    var dd = currentDt.getDate();
    var yyyy = currentDt.getFullYear();
    var date = yyyy+"-"+mm+"-"+dd;
          document.getElementById('invoiceDateID').style.visibility = 'visible';
          document.getElementById('invoiceDateID').value= date;

            
            }
            else {
              document.getElementById('invoiceDateID').style.visibility = 'hidden';
            }
        }
</script>
<style>
div.lal { 
  background: #E6E6FA;
  height: 40px;
  width: 110%;
}
#lal label {display:block; width:100%; height:30px; text-align:right;}

div.b {
    text-align: left;
}

div.c {
    text-align: right;
} 

div.d {
    text-align: justify;
} 
</style>
@endsection