
@extends('master')
@section('content')
      
           
              <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">   Company List  </b>
                   <!-- approve.approveDetail  -->
                </div>
       

  
        <div class="col-sm-1"  > </div> 
        <div class="col-sm-8" >
       <div class="col-sm-11" style="margin-left: -6%" > 
       
        <form method="GET" action="{{ url('my-search') }}">
         <div align="center">
            
          
  <table    id="selecter">
     <tr>
          <td  style="background: #7EC0EE  "   ><strong><font color="556B2F">&nbsp; Company  &nbsp;</font></strong></td> 
            <td    >
       <input type="text"  style="width: 190%" id="companyFistsrc" name="companynamesrc" placeholder="Companies's name" 
              value="{{ old('companynamesrc') }}">
      </td>     
         </tr>
            <tr>
          <td  style="background: #7EC0EE  "   ><strong><font color="556B2F">&nbsp; Status  &nbsp;</font></strong></td> 
            <td    >
       {{csrf_field()}}
            <input type="checkbox" name='status' value="1" 
               <?php  if (old('status')=="1")  echo "checked";?> >  Include Not Alive
                
              
      </td>     
         </tr>
    </table>   
    </div>
    <br>
  <div align="center" style="margin-left:  15%">
        <button type="submit">Search</button>    
           
   
</div> 
       
        </form> 
        </div>     
      <br>
       
       
       <div align="center" >
  <div class="form-class row" style="margin-top:-1%">
        <label class="control-label col-sm-7" style="font-size: 18px; margin-left: -19% ">{{$client->total()}} Companies</label>
  </div>
  <table id="myTable" class="table table-bordered " content ="charset=UTF-8" >
    <thead >
        <tr >
                <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('code', 'Code')</strong></td> 
       <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('companyname', 'Company')</strong></td> 
         <td style=" background-color: #CACFD2;"  ><strong>@sortablelink('url', 'URL')</strong></td> 
         <td style=" background-color: #CACFD2;"  ><strong>@sortablelink('status', 'Status')</strong></td>
        </tr>  
    </thead>
    <tbody>
    @foreach($client as $value)
        <tr <?php if( !is_null( $value->status )): ?> style="background-color:#DCDCDC;text-align: right;" 
              <?php else : ?> style="text-align: right;" <?php endif; ?> >
            <td><a href="{{action('ClientControler@show', $value->id)}}">{{ $value->code }}</a></td>
            <td align="left" nowrap="true" >{{ $value->companyname }}</td>
            <td align="left" ><a href="http://{{ $value->url }}" target="_blank">{{ $value->url }}</a></td>
             <td align="left" styl  >{{ $value->status }}</td>
        </tr>
    @endforeach
     <tr>
      <td colspan="3" align="center">
        <div class="pagination" >
         {{ $client->appends(Request::except('page'))->links() }}   
        </div>      
      </td>
    </tr>
    </tbody>
</table>
 </div>  

 <script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 2); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      try {
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
catch(err) {
  
}

    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>        
    </body>
@endsection