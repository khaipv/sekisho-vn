<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Create PDF</title>
<style>
body {
font-family: 'ipag','DejaVu Sans';
}
.vilang {
   font-family: 'DejaVu Sans'; 
   font-size: 10px;
}
.vilang11 {
   font-family: 'DejaVu Sans'; 
   font-size: 11px;
}
.vilang7 {
   font-family: 'DejaVu Sans'; 
   font-size: 7px;
}
.jplang {
   font-family: 'ipag'; 
     font-size: 10px;
}
.jplang11 {
   font-family: 'ipag'; 
     font-size: 11px;
}
td {
  border: 1px solid #dddddd;
  text-align: left;
  padding-top: 4px;
  padding-bottom: 2px;

}
 th {
     font-family: ipag; font-size: 9px;
  border: 1px  solid #dddddd;
  text-align: center;
  padding-left: 1px;
   padding-top: 2px;
 padding-right: 1px;
  vertical-align: top;
}
</style>
</head>


 <body>
     
              
       
        <div class="col-sm-10" style="margin-bottom: 10%" > 
        <div  >
          <div style="width: 40%;margin-right: 20%;margin-top: -1%;">
              勤   務   月   報    {{$year}}  年 {{$month }} 月分<br><br><br>
         <span class="jplang11"> № </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $userH->code }}<br>
       <span class="jplang11"> 氏名 </span> <span class="vilang11"> Họ tên  </span> &nbsp; {{ $userH->name }} <br>
        <span class="vilang11">Bộ Phận  </span>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $userH->depart }} 
          </div>
      <div style="width: 50%;margin-left: 50%;margin-top: -12%" >
        <table  content ="charset=UTF-8" style="float: right; font-size: 12px; 

 
        "  >
          <tr >
            <th> 
            今月の代休と特休 <br>
           <span class="vilang7"> SNN<br> Bù, Đặc Biệt </span></th>
                <th> 
            今月の無給 <br>
           <span class="vilang7"> SNN<br> Không Lương </span></th>
            <th> 
            有給休暇残 <br>
           <span class="vilang7"> SNNP<br> Đầu Tháng </span></th>
               <th> 
            今月の<br> 有給休暇 <br>
           <span class="vilang7"> SNNP <br> Trong Tháng</span></th>
                  <th> 
            月末までの<br> 有給休暇残 <br>
           <span class="vilang7">SNNP <br> Còn Đến <br> Cuối Tháng</span></th>
                       <th> 
            承認  <br>
           <span class="vilang7">Phê duyệt</span></th>
          </tr>
          <tr >
            <td style="text-align: center;">
                {{ $arrayLeave['daySCPLeave']}} 
              
            </td>
            <td style="text-align: center;">
            {{ $arrayLeave['dayUnpaidLeave']}}
            </td>
             <td style="text-align: center;">
          <?php 
               if ($arrayLeave['dayAnnualStart'] >0) {
                echo $arrayLeave['dayAnnualStart'];
               } else  echo 0;
               ?>
            </td>
              <td style="text-align: center;">
                <?php 
                  if ($arrayLeave['dayAnnualEnd'] >=0 && $arrayLeave['dayAnnualStart'] -$arrayLeave['dayAnnualEnd'] >=0) {
                echo $arrayLeave['dayAnnualStart'] -$arrayLeave['dayAnnualEnd'];
               } elseif ($arrayLeave['dayAnnualStart'] >0) {
                  echo $arrayLeave['dayAnnualStart'] ;
               } else echo 0;
                  ?>
              </td>
              <td style="text-align: center;">
              <?php 
               if ($arrayLeave['dayAnnualEnd'] >0) {
                echo $arrayLeave['dayAnnualEnd'];
               } else  echo 0;
               ?>
              </td>
             <td height="35px">
              
            </td>
          </tr>
        </table>
        </div>
        </div>
      </div>
      <br>
      <div class="col-sm-1"  > </div> 

       <div class="col-sm-10"  > 

  <table id="myTable" class="table table-hover table-bordered "
style=" font-family: Arial, Helvetica;
  border-collapse: collapse;
  font-size: 10px;" 
   content ="charset=UTF-8" >
    <thead >
        <tr>
       <th colspan="2"  > 日 <br> <span class="vilang"> Ngày <span class="vilang"> </th> 
 <th  > 区分 <br> <span class="vilang"> Phân <br> loại </span></th> 
     
       <th><strong>休みの日 <br><span class="vilang">Nghỉ </span> </strong></th>
            <th  nowrap>
                始業時間<br> <span class="vilang"> Thời giờ <br>bắt đầu <br>làm việc </span>
        </th>            
            <th  nowrap>
                終業時間<br> <span class="vilang"> Thời giờ<br> kết thúc<br> làm việc </span>
            </th> 
                   <th nowrap>
                休憩時間<br> <span class="vilang"> Nghỉ giải<br> lao </span>
            </th> 
               <th  nowrap>
                実動時間<br> <span class="vilang"> Thời gian<br> làm việc<br> thực tế </span>
            </th> 
                  <th  nowrap>
                残業時間<br> <span class="vilang"> Giờ làm<br> thêm </span>
            </th> 
             <th  nowrap>
                深夜時間<br> <span class="vilang">Thời gian <br> làm đêm </span>
            </th> 
             <th>
              遅刻早退<br> <span class="vilang">Late <br> & Early </span>
             </th>
              <th  nowrap>
                総時間<br> <span class="vilang"> Tổng <br> Thời gian <br>làm việc </span>
            </th> 
             <th> 責任者印<br> <span class="vilang">Dấu người <br> quản lý</th>
        </tr>  
    </thead>
    <tbody>
        @php
    
     $i=0;
     $lateM=0;
     @endphp
    @foreach($timesheet as $value)
        
   
        <tr>
          <td nowrap="true"<?php if($value->status=='107'  ): ?>  <?php endif; ?>>
            {{ $value->date }}</td>

          <td> {{  substr( $value->typedate  ,0, 3) }}</td>

            <td nowrap="true"> <span class="jplang">{{ $value->jName }}</span></td>
             <!-- Dayoff type -->
  <td nowrap="true">
    <span class="jplang">
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
              </span>
            </td> 
            <td style="text-align: right;">
              {{  substr($value->attendance ,0, 5) }}
            </td>    

                 
             <td style="text-align: right;">
           
               {{  substr($value->leaving,0, 5) }}
               
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
             <td>
                 
             </td>
        </tr>
       
    @endforeach
     <tr>
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
            <td style="text-align: right;"><a href=""></a></td>
        </tr>
    
    </tbody>
</table>

  <br><br>
   
  @if(!is_null($userH))
  <table id="myTable" class="table table-hover table-bordered "
style=" font-family: Arial, Helvetica;
  border-collapse: collapse;
  font-size: 10px;" 
   content ="charset=UTF-8" >
    </thead>
        <tr>
           <th colspan="3"  >平日<span class="vilang">Ngày trong tuần </span></th>
 
       <th colspan="3"   nowrap>週休日<span class="vilang">Ngày nghỉ hàng tuần </span></th>
          <th colspan="3"   nowrap>祝日<span class="vilang">Ngày nghỉ lễ</span></th>
           
              
       </tr>
     </thead>
      <thead >
        <tr>
      <th colspan="1"  >実動時間<br><span class="vilang">Thời gian<br>  làm việc <br> thực tế</span></th>
          <th  >残業割増分<br><span class="vilang">Số thời<br> gian làm<br> thêm giờ</span></th>
             <th colspan="1"   nowrap>深夜割増分<br><span class="vilang">Số thời<br> gian làm <br>thêm ban đêm</span></th>
              <th colspan="1"   nowrap>実動時間<br><span class="vilang">
Thời gian <br>làm việc <br>thực tế</span></th>
       
        <th colspan="1"   nowrap>残業割増分<br><span class="vilang">Số thời <br> gian làm <br>thêm giờ</span></th>
           <th colspan="1"   nowrap>深夜割増分<br><span class="vilang">Số thời <br>  gian làm thêm <br>  ban đêm</span></th>
             <th colspan="1"   nowrap>実動時<br><span class="vilang">Thời gian <br> làm việc <br> thực tế</span></th>
           <th colspan="1"   nowrap>残業割増分<br> <span class="vilang">Số thời<br> gian làm <br>thêm giờ</span></th>
            <th colspan="1"   nowrap>深夜割増分<br><span class="vilang">
Số thời gian <br>làm thêm ban đêm</span></th>
     
           
       </tr>
       </thead>
       <tr>
           <td style="text-align: right;">
          <?php   
       

  
                echo $arrayTotal['sumWorkHour']; ;
          ?>
              
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
          
            
             
       </tr>
     
   </table> 
   @endif
       
       </div>
     </div>
   </td>
 </tr>



    
    </body>
</html>