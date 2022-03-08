@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
  <br></br>
                   <b style="font-size: 18px; margin-left: 220px  ">   Order List   </b>
                   {{-- <label class="control-label col-sm-7" style="font-size: 18px; margin-left: -19% ">{{$order->total()}} Ordersdsaaaaaaaaaaaaaaaaaaa</label> --}}
                   <!-- approve.approveDetail  -->
                </div>
                      @if(session()->has('message'))
            <div style="text-align: center;" class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
      <div>
      <div class="col-sm-1" style="width: 11%" > </div>
             <div style="width: 70%" class="col-sm-8"  > 

        <form method="GET" action="{{ url('orderSearch') }}" autocomplete="off">
             <input name="_method" type="hidden" value="PATCH">
            <table class="table table-hover table-condensed table-striped table-bordered" id="selecter">
         <tr>     
  
        <td  style="background: #7EC0EE; " ><strong><font color="556B2F">
     Company </font></strong></td> 
      <td colspan="1"  >
        <input id="order_companysrc" type="text" name="order_companysrc" style="width: 90%" 
         placeholder="Please enter Company" value="{{ old('order_companysrc')}}"   >
      </td>
       <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
     PIC </font></strong></td> 
      <td colspan="1"   >
         <select    name="pics"  style="width: 170px" >
                  <option></option>
                @foreach($users as $users)
                    <option value="{{$users->id}}"    @if( $users->id == old('pics') ) selected="selected" @endif   >{{ $users->name }}</option>
                @endforeach
                </select>
      </td>
      </tr>
      <tr>
        <td  style="background: #7EC0EE;width: 15%"   ><strong><font color="556B2F">
     Status </font></strong></td> 
            
     <td colspan="1"   >
    <select   id="statussrc" style="width: 170px"  name="status">
                 <option></option>
                @foreach($status as $status)
                 @if($status->type =='status')
                    <option value="{{$status->code}}"  @if( $status->code == old('status') ) selected="selected" @endif  >{{ $status->name }}</option>
                 @endif
                @endforeach
                </select>
        
      </td>
           <td  style="background: #7EC0EE"  class="col-md-1"><strong><font color="556B2F">
     Invoice </font></strong></td> 
      <td colspan="1"   >
         <select  style="width: 170px"    name="invoiceCK" style="width: 80%">
                         <option></option>
                          <option value="1"  @if( old('invoiceCK') =='1') selected="selected" @endif  >Invoice</option>
                           <option value="0"  @if( old('invoiceCK') =='0' ) selected="selected" @endif>Not Invoiced</option>
                       </select>
      </td>
      </tr>
      <tr>
               <td  style="background: #7EC0EE"  ><strong><font color="556B2F">
     Progress </font></strong></td> 
      <td colspan="1"  >
          <select  style="width: 170px"   name="progress">
                         <option></option>
                      @foreach($progress as $progress)
                       @if($progress->type =='progress')
                                       <option value="{{$progress->name}}"    @if( $progress->name == old('progress') ) selected="selected" @endif   >{{ $progress->name }}</option>
                       @endif
                      @endforeach
                       </select>
      </td>
      <td nowrap="true"    style="background: #7EC0EE;width: 15%" ><strong><font color="556B2F">
     Introduce  Date </font></strong></td> 
      <td colspan="1" nowrap="true"  >
         <input type="date" name="introduceFrom" value="{{ old('introduceFrom')}}"   min="1970-01-01"    /> ~
          <input type="date" name="introduceTo"  value="{{ old('introduceTo')}}"  min="1970-01-01"    /> 
      </td>
      </tr>
       <tr>
         <td nowrap="true"  style="background: #7EC0EE;width: 15%" ><strong><font color="556B2F">
     Order Date </font></strong></td> 
      <td colspan="1" nowrap="true"  >
         <input type="date" name="orderFrom" value="{{ old('orderFrom')}}"  
           min="1970-01-01"     /> ~
          <input type="date" name="orderTo"  value="{{ old('orderTo')}}"   min="1970-01-01"    /> 
      </td>
       <td nowrap="true"  style="background: #7EC0EE;width: 15%" ><strong><font color="556B2F">
     Salary </font></strong></td> 
      <td colspan="1" nowrap="true"  >
        <input type="text"  id="salaryFromID"  style="width: 35%"
        value="{{ old('salaryFrom')}}" name="salaryFrom"  onkeypress='validateNum(event)' 
         onkeypress='validateNum(event)' >  ~
           <input type="text"  id="salaryToID"  style="width: 35%"
        value="{{ old('salaryTo')}}" name="salaryTo"  onkeypress='validateNum(event)' 
         >
          <select    name="unitSaFrom"  >
                @foreach($unitSaFrom as $unitSaFrom)
                    <option value="{{$unitSaFrom->id}}"   @if( session('forms.ssunitSaFromID')  ==$unitSaFrom->id ) selected="selected" @endif   >{{ $unitSaFrom->code }}</option>
                @endforeach
      </select>
      </td>
      
      </tr>
      
         <tr>
         <td nowrap="true"   style="background: #7EC0EE;width: 15%" ><strong><font color="556B2F">
     Working Date </font></strong></td> 
      <td colspan="1"  >
         <input type="date" name="workingFrom" value="{{ old('workingFrom')}}"   min="1970-01-01"    /> ~
          <input type="date" name="workingTo" value="{{ old('workingTo')}}"    min="1970-01-01"    /> 
      </td>
 
      </tr>

       </table>
   
                 <div class="col-sm-12"  >
                  <div class="content">
                  <button type="submit">Search</button>
                 </div> 
                  </div> 
        </div>
    </div>
        </form>   
       
        </div> 
          
      <br>
</div>
       <div class="content " style="margin-left: 27px ">
             <div class="form-class row" style="margin-top:-1%">
        <label class="control-label col-sm-7" style="font-size: 18px; margin-left: -19% ">{{$order->total()}} Orders</label>
  </div>
<table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
        
    <thead >
        <tr>
         <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('code', 'Code')</strong></td> 
         <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('companyname', 'Company')</strong></td> 
          <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('divisionName', 'Division')</strong></td> 
         <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('title', 'Title')</strong></td> 
      
       <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('pic_s', 'Pic Sekisho')</strong></td>
           <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('orderDate', 'Order Date(Plan)')</strong></td> 
           <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('introduceDate', 'Introduce Date')</strong></td> 
           <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('workingDate', 'Working Date')</strong></td> 
           <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('progress', 'Progress')</strong></td> 
              {{-- <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('type', 'Type')</strong></td>  --}}
              {{-- <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('pic_s', 'Pic Sekisho')</strong></td>  --}}
               <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('created_at', 'Created Date')</strong></td> 
          <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('JLevel', 'Japanese Level')</strong></td> 
         <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('ELevel', 'English Level')</strong></td> 
                <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('skill', 'Other Skill')</strong></td> 
               <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('indispensable', 'Indispensable Skill')</strong></td> 
      <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('warranty', 'Warranty')</strong></td> 

          <td  style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('warrantyPeriod', 'Warranty Period')</strong></td> 
           <td style=" background-color: #CACFD2;"  nowrap><strong>@sortablelink('recruit_num', 'Recruitment Number')</strong></td> 
           <td style=" background-color: #CACFD2;"  nowrap><strong>@sortablelink('age', 'Age')</strong></td> 
               <td  style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('salaryFrom', 'Salary')</strong></td>
          <td  style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('incom', 'Incom')</strong></td>
           <td  style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('invoiceCK', 'Invoice')</strong></td>
           <td  style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('invoiceDate', 'invoiceDate')</strong></td>
        </tr>
    </thead>
    <tbody>
    @foreach($order as $value)
    @if ($value->priority===1)
    <tr style="background: rgb(230, 245, 165);">
      <td><a href="{{action('OrderController@show', $value->id)}}">{{ $value->code }}</a></td>
      <td align="left" nowrap>{{ $value->companyname }}</td>
      <td align="left" nowrap>{{ $value->divisionName }}</td>
      <td align="left" nowrap>{{ $value->title }}</td>
      <td align="left" nowrap> {{ $value->pics }}</td> 
      <td align="left" nowrap>{{ $value->orderDate }}</td>
      <td align="left" nowrap>{{ $value->introduceDate }}</td>
      <td align="left">{{ $value->workingDate }}</td>
      <td align="left" nowrap>{{ $value->progressName }}</td>
      {{-- <td align="left" nowrap>{{ $value->typeName }}</td> --}}
      {{-- <td align="left" nowrap> {{ $value->pics }}</td> --}}
         <td>{{ Carbon\Carbon::parse($value->created_at)->format('Y-m-d') }}</td>
         <td align="left"> {{ $value->jpName }}</td>
         <td align="left">{{ $value->engName }}</td>
         <td align="left">{{ $value->skill }}</td>
         <td align="left">{{ $value->indispensable }}</td> 
          <td align="left">{{ $value->warantyName }}</td>  
         <td>{{ $value->warrantyPeriod }}</td>  
         <td>{{ $value->recruit_num }}</td>
         <td>{{ $value->age }}</td>
          <td>{{ number_format($value->salaryFrom).'~'.number_format($value->salaryTo).($value->unitSaName) }}</td>
         <td nowrap>{{ number_format(($value->introduceFee)-($value->partnerfee)) }} {{$value->unitName}}</td>
         <td>
            <input type="checkbox" name="invoiceCK"
            <?php if( $value->invoiceCK == '1' ) {  echo "checked"; } ?> value="1" > 
         </td>
         <td>{{ $value->invoiceDate }}</td>
  </tr>
  @else
  <tr>
    <td><a href="{{action('OrderController@show', $value->id)}}">{{ $value->code }}</a></td>
    <td align="left" nowrap>{{ $value->companyname }}</td>
    <td align="left" nowrap>{{ $value->divisionName }}</td>
    <td align="left" nowrap>{{ $value->title }}</td>
    <td align="left" nowrap> {{ $value->pics }}</td> 
    <td align="left" nowrap>{{ $value->orderDate }}</td>
    <td align="left" nowrap>{{ $value->introduceDate }}</td>
    <td align="left">{{ $value->workingDate }}</td>
    <td align="left" nowrap>{{ $value->progressName }}</td>
    {{-- <td align="left" nowrap>{{ $value->typeName }}</td> --}}
    {{-- <td align="left" nowrap> {{ $value->pics }}</td> --}}
       <td>{{ Carbon\Carbon::parse($value->created_at)->format('Y-m-d') }}</td>
       <td align="left"> {{ $value->jpName }}</td>
       <td align="left">{{ $value->engName }}</td>
       <td align="left">{{ $value->skill }}</td>
       <td align="left">{{ $value->indispensable }}</td> 
        <td align="left">{{ $value->warantyName }}</td>  
       <td>{{ $value->warrantyPeriod }}</td>  
       <td>{{ $value->recruit_num }}</td>
       <td>{{ $value->age }}</td>
        <td>{{ number_format($value->salaryFrom).'~'.number_format($value->salaryTo).($value->unitSaName) }}</td>
       <td nowrap>{{ number_format(($value->introduceFee)-($value->partnerfee)) }} {{$value->unitName}}</td>
       <td>
          <input type="checkbox" name="invoiceCK"
          <?php if( $value->invoiceCK == '1' ) {  echo "checked"; } ?> value="1" > 
       </td>
       <td>{{ $value->invoiceDate }}</td>
</tr>
    @endif     
    @endforeach
     <tr>
      <td colspan="13">
        <div class="pagination">
            {{ $order->appends(Request::except('page'))->links() }}   
      </td>
    </tr>
    </tbody>
</table>
 </div>           
    </body>
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
</script>
@endsection