
@extends('timemaster')
@section('content')
     
                <div style="margin-top: -25px; float: left;" >
                   <b style="font-size: 18px"> Fingerprint </b>
                </div>
                <br>
       
       <div >
       

       <div class="col-sm-5"  > 

       
        <form method="GET" action="{{ url('timeSearch') }}">   
          <table class="table table-borderless" >
               
     @if($userL->role>=1 )
      <tr >
      <td  style="background: #7EC0EE ;width: 10%"   class="col-md-1"><strong><font color="556B2F">Staff</font></strong>
      </td> 
      
      <td class="col-md-3" colspan="3" >
      <select class="form-control"   name="tck_user" required="true" style="width: 90%" >
               <option value="">--------</option>
                @foreach($tck_user as $tck_user)
                    <option value="{{$tck_user->id}}"  
                     @if( $tck_user->id == old('tck_user') ) 
          selected="selected" @endif     >{{ $tck_user->name }}</option>
                @endforeach
      </select> 
      
      </td>
      </tr>
      @endif
    

  
  <tr >
            <td  style="background: #7EC0EE"  width="14.5%"><strong><font color="556B2F">
       Month/Year</font></strong></td> 
            <td class="col-md-5" colspan="2" >
   <select    name="month" style="width: 40%;height: 30px " >
               <option value="1" @if( $month == 1 ) selected="selected" @endif>1</option>
               <option value="2" @if( $month == 2 ) selected="selected" @endif>2</option>
               <option value="3" @if( $month == 3 ) selected="selected" @endif>3</option>
               <option value="4" @if( $month == 4 ) selected="selected" @endif>4</option>
               <option value="5" @if( $month == 5 ) selected="selected" @endif>5</option>
               <option value="6" @if( $month == 6 ) selected="selected" @endif>6</option>
               <option value="7"  @if( $month == 7 ) selected="selected" @endif>7</option>
               <option value="8" @if( $month == 8 ) selected="selected" @endif>8</option>
               <option value="9" @if( $month == 9 ) selected="selected" @endif>9</option>
               <option value="10" @if( $month == 10) selected="selected" @endif>10</option>
               <option value="11" @if( $month == 11) selected="selected" @endif>11</option>
               <option value="12" @if( $month == 12) selected="selected" @endif>12</option>
       </select>/ 

             <input type="number" style="width: 40%;height: 30px " placeholder="Year" id="year" name="year" min="2019" 
              @if( isset($year) )
       value="{{$year }}"
       @else 
       value="{{ old('year')}}"
       @endif
            required="true"/>

             
      </td> 
      <td class="col-md-3" style="float: right;" >
       <button type="submit" >Search</button>
          <button type="submit" class="btn btn-link" name="deny" value ="deny">Download PDF</button>
        </td>
        </tr>
              
        </table>
        </form> 
         
        </div> 
       <div class="col-sm-5"  > 
          <tr>

                  
                  <label style="text-align: right;width: 100%">
                    @if(!is_null($userH))
                    CP Leave, Special Leave :  {{ $arrayLeave['daySCPLeave']}} days <br>
                     Unpaid Leave :  {{ $arrayLeave['dayUnpaidLeave']}} days <br>
                     Annual Leave Remaining (now) : 
                     <?php 
               if ($annualLeaveYear >0) {
                echo $annualLeaveYear;
               } else  echo 0;
               ?> days 
                    <br>Annual Leave(start month) :   
                     <?php 
               if ($arrayLeave['dayAnnualStart'] >0) {
                echo $arrayLeave['dayAnnualStart'];
               } else  echo 0;
               ?>days
                       <br>Annual Leave(end month) :  
                        <?php 
               if ($arrayLeave['dayAnnualEnd'] >0) {
                echo $arrayLeave['dayAnnualEnd'];
               } else  echo 0;
               ?>  days
                  @endif
    </label>
            </tr>
        </div>
      </div>
      <br>
      <div class="col-sm-1"  > </div> 

       <div class="col-sm-10"  > 

  <table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
    <thead >
        <tr>
       <td colspan="2" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('date', 'Date')</strong></td> 
 
      <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('namex', 'Type')</strong></td>
       <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('dayoff', 'Dayoff')</strong></td>
          <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('attendance', 'attendance')</strong></td> 
            <td style=" background-color: #CACFD2;" ><strong>@sortablelink('Original Attendance', 'Original Attendance')</strong></td> 
           
            <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('leaving', 'leaving')</strong></td> 
                <td style=" background-color: #CACFD2;" ><strong>@sortablelink('Original Leaving', 'Original Leaving')</strong></td> 
                 <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('break time', 'Rest')</strong></td>
                     <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('workingTime time', 'Working Time')</strong></td>
              <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Overtime', 'Overtime')</strong></td> 
            <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Overtime', 
            'Midnight')</strong></td>
             <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Overtime', 
            'Late & Early')</strong></td>
              <td style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Overtime', 
            'Total')</strong></td>
        </tr>  
    </thead>
    <tbody>
        @php
    
     $i=0;
     $lateM=0;
     @endphp
    @foreach($timesheet as $value)
        
   
        <tr>
          <td nowrap="true"<?php if($value->status=='107'  ): ?> style="background-color:#F6CFCA;" <?php endif; ?>>
            {{ $value->date }}</td>

          <td>{{ $value->typedate }}</td>

            <td nowrap="true">{{ $value->nameX }}</td>
             <!-- Dayoff type -->
            <td nowrap="true">
              <?php 
               $breakString=$tck_workinghours->breaks ;
               $breakVal=0;
               $txtdayoff="";
               
               foreach ($dayoffList as $key => $dayoff) {
               if ( $value->date==$dayoff->date) {
                if ($dayoff->amID >0) {
                    $txtdayoff .="振休(AM)";
                } elseif ($dayoff->amID == -1) {
                    $txtdayoff .="有給(AM)";
                } elseif ($dayoff->amID == -5) {
                    $txtdayoff .="無給(AM)";
                }elseif ($dayoff->amID == -10) {
                    $txtdayoff .="特休(AM)";
                }
               
                if ($dayoff->pmID>0) {
                    $txtdayoff .="振休(PM)";
                } elseif ($dayoff->pmID == -1) {
                    $txtdayoff .="有給(PM)";
                } elseif ($dayoff->pmID == -5) {
                    $txtdayoff .="無給(PM)";
                }elseif ($dayoff->pmID == -10) {
                    $txtdayoff .="特休(PM)";
                }

                if ($dayoff->amID >0 && $dayoff->pmID >0) {
                    $txtdayoff ="振休(ALL)";
                } elseif ($dayoff->amID == -1 && $dayoff->pmID == -1) {
                    $txtdayoff ="有給(ALL)";
                } elseif ($dayoff->amID == -5  &&  $dayoff->pmID == -5) {
                    $txtdayoff  ="無給(ALL)";
                }elseif ($dayoff->amID == -10  && $dayoff->pmID == -10) {
                    $txtdayoff ="特休(ALL)";
                }
                    break;
                }


                 }
                 echo $txtdayoff;
                
              ?>
            </td> 
            <td <?php if($value->late < 0): ?> style="background-color:#00ff00;text-align: right;" 
              <?php else : ?> style="text-align: right;" <?php endif; ?>>
              {{  substr($value->attendance ,0, 5) }}
            </td>    

                  <td <?php if($value->late < 0): ?> style="background-color:#00ff00;text-align: right;" 
              <?php else : ?> style="text-align: right;" <?php endif; ?>>
            
               {{  substr($value->attendance_ori ,0, 5) }}
            </td> 
             <td <?php if($value->early < 0): ?> style="background-color:#00ff00;text-align: right;" 
              <?php else : ?> style="text-align: right;" <?php endif; ?>>
           
               {{  substr($value->leaving,0, 5) }}
               <td <?php if($value->early < 0): ?> style="background-color:#00ff00;text-align: right;" 
              <?php else : ?> style="text-align: right;" <?php endif; ?>>
              
              {{  substr($value->leaving_ori,0, 5) }}
              <!-- rest start -->
               <td style="text-align: right;">
             <?php
              $hour3= \Carbon\Carbon::parse($tck_workinghours->hour3);
                 $hour4= \Carbon\Carbon::parse($tck_workinghours->hour4);
                 $restStart=\Carbon\Carbon::parse($value->attendance);
                  $restEnd=\Carbon\Carbon::parse($value->leaving);
             if ($restStart<$hour3 &&$restEnd >$hour4) {
               echo "01:00";
             }
             ?>
            </td> 
                        <!-- rest start -->
            <!-- WorkingTime start -->
             <td style="text-align: right;">
              <?php
                $k=$i; 
                
                if ($arrayWorkHour8h[$i] >0) {
                  # code...
               
                  if ($arrayWorkHour8h[$i] - floor($arrayWorkHour8h[$i] / 60) * 60 <10) {
            $arrayWorkHour_txt = floor($arrayWorkHour8h[$i]/60).':0'.($arrayWorkHour8h[$i] - floor($arrayWorkHour8h[$i] / 60) * 60);
                 } else 
              $arrayWorkHour_txt = floor($arrayWorkHour8h[$i]/60).':'.($arrayWorkHour8h[$i] - floor($arrayWorkHour8h[$i] / 60) * 60);
                 echo $arrayWorkHour_txt;
                  }
              ?>
            </td>
             <!-- WorkingTime End  -->
                          <td style="text-align: right;">
                   <!-- Overtime Start  -->
              <?php 
         $ohour1= \Carbon\Carbon::parse($tck_workinghours->ohour1);
                 $ohour2= \Carbon\Carbon::parse($tck_workinghours->ohour2);
                 $ohour3= \Carbon\Carbon::parse($tck_workinghours->ohour3);
                 $ohour4= \Carbon\Carbon::parse($tck_workinghours->ohour4);
                 $ohour5= \Carbon\Carbon::parse($tck_workinghours->ohour5);
              $timeOTSum=0;
              foreach ($OTlist2 as $key => $OTvalue) {
                if ($OTvalue->usrID==$value->code && $OTvalue->date==$value->date 
                  && $OTvalue->companyCode==$value->companyCode 
                  && $OTvalue->statusCP=='401'                ) {
                   $startOT= \Carbon\Carbon::parse($OTvalue->start);
                   $endOT= \Carbon\Carbon::parse($OTvalue->end);
           // Nếu thời gian làm thêm buổi tối lớn hơn mốc giờ làm đêm thì lấy end là mốc làm đêm 22h còn nếu mốc end nhỏ hơn mốc làm đêm buổi sáng thì lấy mốc sáng là 6h  
           if($endOT > $ohour2) { $endOT = $ohour2; }
           if($endOT < $ohour5) { $endOT = $ohour5;}
                   $timeOTSum+=$endOT->diffInMinutes($startOT);
                }
              }
                 if ($timeOTSum - floor($timeOTSum / 60) * 60 <10) {
            $timeOTSum_txt = floor($timeOTSum/60).':0'.($timeOTSum - floor($timeOTSum / 60) * 60);
                 } else 
              $timeOTSum_txt = floor($timeOTSum/60).':'.($timeOTSum - floor($timeOTSum / 60) * 60);  if ($timeOTSum>0) {
                 echo $timeOTSum_txt;
              }
              ?>
              <!-- Overtime End  -->
            </td>   


             <td style="text-align: right;">
              <!-- Midnight start  


              -->
              <?php
               $nightOTs=0;
              
                 $ohour1= \Carbon\Carbon::parse($tck_workinghours->ohour1);
                 $ohour2= \Carbon\Carbon::parse($tck_workinghours->ohour2);
                 $ohour3= \Carbon\Carbon::parse($tck_workinghours->ohour3);
                 $ohour4= \Carbon\Carbon::parse($tck_workinghours->ohour4);
                 $ohour5= \Carbon\Carbon::parse($tck_workinghours->ohour5);
               foreach ($OTlist2 as $key => $nightOT) {
                if ($nightOT->usrID==$value->code && $nightOT->date==$value->date 
                  && $nightOT->companyCode==$value->companyCode) {
                    $startOT= \Carbon\Carbon::parse($nightOT->start);
                   $endOT= \Carbon\Carbon::parse($nightOT->end);
                  if ($endOT >$ohour2) {
                   if ($endOT<$ohour3) {
                     $nightOTs+=$endOT->diffInMinutes($ohour2);
                   } else 
                    $nightOTs+=$ohour3->diffInMinutes($ohour2);
                  }
                    if ($startOT < $ohour5) {
                   if ($endOT<$ohour5) {
                     $nightOTs+=$endOT->diffInMinutes($startOT);
                   } else 
                    $nightOTs+=$ohour5->diffInMinutes($startOT);
                  }
                }
               }

               // print 
                 if ($nightOTs - floor($nightOTs / 60) * 60 <10) {
            $nightOTs_txt = floor($nightOTs/60).':0'.($nightOTs - floor($nightOTs / 60) * 60);
                 } else 
              $nightOTs_txt = floor($nightOTs/60).':'.($nightOTs - floor($nightOTs / 60) * 60); 
               if ($nightOTs>0) {
                 echo $nightOTs_txt;
              }
              ?>

              <!-- Midnight end  -->
            </td>  
            <td style="text-align: right;">
              <?php
              // late and early count 
               if ($arrayTimelate[$i] >0) {
                       if ($arrayTimelate[$i] - floor($arrayTimelate[$i] / 60) * 60 <10) {
            $show_txt = floor($arrayTimelate[$i]/60).':0'.($arrayTimelate[$i] - floor($arrayTimelate[$i] / 60) * 60);
                 } else 
              $show_txt = floor($arrayTimelate[$i]/60).':'.($arrayTimelate[$i] - floor($arrayTimelate[$i] / 60) * 60);
                echo $show_txt;
               }
               
              
               
               ?>
             
            </td>
                 <td style="text-align: right;">
              <?php
                $k=$i; 
                
                if ($arrayWorkHour[$i] >0) {
                  # code...
               
                  if ($arrayWorkHour[$i] - floor($arrayWorkHour[$i] / 60) * 60 <10) {
            $arrayWorkHour_txt = floor($arrayWorkHour[$i]/60).':0'.($arrayWorkHour[$i] - floor($arrayWorkHour[$i] / 60) * 60);
                 } else 
              $arrayWorkHour_txt = floor($arrayWorkHour[$i]/60).':'.($arrayWorkHour[$i] - floor($arrayWorkHour[$i] / 60) * 60);
                 echo $arrayWorkHour_txt;
                  }
              ?>
                <?php  $i++; ?>
            </td>
        </tr>
       
    @endforeach
      <tr>
          <td><a href=""></a></td>
           <td><a href=""></a></td>
            <td><a href=""></a></td>
           <td><a href=""></a></td>
            <td><a href=""></a></td>
          <td><a href=""></a></td>
           
           <td><a href=""></a></td>
         
            <td colspan="2"> <span class="jplang"> 合 計  </span><span class="vilang">Tổng số </span></td>
             
            <td style="text-align: right;">
               <?php 
   
                     if (end($arrayWorkHour8h) - floor(end($arrayWorkHour8h) / 60) * 60 <10) {
            $arrayWorkHour8h_txt = floor(end($arrayWorkHour8h)/60).':0'.(end($arrayWorkHour8h) - floor(end($arrayWorkHour8h) / 60) * 60);
                 } else 
              $arrayWorkHour8h_txt = floor(end($arrayWorkHour8h)/60).':'.(end($arrayWorkHour8h) - floor(end($arrayWorkHour8h) / 60) * 60);
                 echo $arrayWorkHour8h_txt;
          ?>
        
              
         </td> 

            <td style="text-align: right;"> <?php 
   
              echo $arrayTime['d2'];
          ?></td>
          

            <td style="text-align: right;"> {{ $arrayTime['d3']}}</td>
          
            <td style="text-align: right;"> <?php 
   
              echo $arrayTotal['sumTimelate'];
          ?></td>
            <td style="text-align: right;">
           <?php 
   
              echo $arrayTotal['sumWorkHour'];
          ?>
              
         </td> 
         
        </tr>
    
    </tbody>
</table>
  
   
  @if(!is_null($userH))
  <table id="myTable" class="table table-hover table-bordered " content ="charset=UTF-8" >
    <thead >
        <tr>
       <td colspan="3" style=" background-color: #e6f2aa;" nowrap><strong>@sortablelink('date', 'Day Week')</strong></td> 
       <td colspan="3"  style=" background-color: #e6f2aa;" nowrap><strong>@sortablelink('Code', 'Weekend')</strong></td> 
         <td  colspan="3"  style=" background-color: #e6f2aa;" nowrap><strong>@sortablelink('name', 'Holiday')</strong></td>
          <td  colspan="3"  style=" background-color: #e6f2aa;" nowrap><strong>@sortablelink('name', '')</strong></td>
              
       </tr>
     </thead>
      <thead >
        <tr>
       <td colspan="1" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('date', 'Working Time')</strong></td> 
       <td colspan="1" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Code', 'Over Time')</strong></td> 
         <td colspan="1" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('name', 'OT-Night')</strong></td>
          <td colspan="1" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('date', 'Working Time')</strong></td> 
       <td colspan="1" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Code', 'Over Time')</strong></td> 
         <td colspan="1" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('name', 'OT-Night')</strong></td>
          <td colspan="1" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('date', 'Working Time')</strong></td> 
       <td colspan="1" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('Code', 'Over Time')</strong></td> 
         <td colspan="1" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('name', 'OT-Night')</strong></td>
           <td colspan="1" style=" background-color: #CACFD2;" nowrap><strong>@sortablelink('name', 'Late $ Early')</strong></td>
           
       </tr>
       <tr>
         <td style="text-align: right;">
          {{ $arrayTime['d1']}}
              
         </td> 
          <td style="text-align: right;">
                  {{ $arrayTime['d2']}}
            </td> 
                <td style="text-align: right;">
               {{ $arrayTime['d3']}}
            </td> 
                  <td style="text-align: right;">
             {{ $arrayTime['w1']}}
            </td>
                   <td style="text-align: right;">
              {{ $arrayTime['w2']}}
            </td>
                  <td style="text-align: right;">
              {{ $arrayTime['w3']}}
            </td>
                  <td style="text-align: right;">
              {{ $arrayTime['h1']}}
            </td>
                   <td style="text-align: right;">
             {{ $arrayTime['h2']}}
            </td>
                  <td style="text-align: right;">
              {{ $arrayTime['h3']}}
            </td>
              <td style="text-align: right;">
            <?php 
   
              echo $arrayTotal['sumTimelate'];
          ?>
            </td>
            
             
       </tr>
     </thead>
   </table> 
   @endif
       
       </div>
     </div>
   </td>
 </tr>




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