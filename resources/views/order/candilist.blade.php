@extends('master')
@section('content')
  <div style="margin-top: -4%; float: left;margin-left: 1%;" >
                   <b style="font-size: 18px">    Detail order </b>
                   <!-- approve.approveDetail  -->
                </div>

<div class="container">
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-2" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-sm-9 col-form-label col-form-label-sm">コード</label>
    </div>
    <div class="col-md-5" style="background-color:lavender;"> {{$order->code}}</div> 
</div>



<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-2" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-sm-9 col-form-label col-form-label-sm">取引先名</label>
    </div>
    <div class="col-sm-5" style="background-color:lavender;">{{$order->divisionname}}</div> 
</div>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-2" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-sm-9 col-form-label col-form-label-sm">案件名</label>
    </div>
    <div class="col-sm-5" style="background-color:lavender;">{{$order->title}}</div> 
</div>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-2" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-sm-9 col-form-label col-form-label-sm">案件種別</label>
    </div>
    <div class="col-sm-5" style="background-color:lavender;">{{$order->type}}</div> 
</div>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-2" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-sm-9 col-form-label col-form-label-sm">受注予定日</label>
    </div>
    <div class="col-sm-5" style="background-color:lavender;">{{$order->orderDate}}</div> 
</div>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-2" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-sm-9 col-form-label col-form-label-sm">受注確度・進捗状況</label>
    </div>
    <div class="col-sm-5" style="background-color:lavender;">{{$order->progress}}</div> 
</div>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-2" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-sm-9 col-form-label col-form-label-sm">就労日</label>
    </div>
    <div class="col-sm-5" style="background-color:lavender;">{{$order->workingDate}}</div> 
</div>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-2" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-sm-9 col-form-label col-form-label-sm">就業先住所</label>
    </div>
    <div class="col-sm-5" style="background-color:lavender;">{{$order->workingPlace}}</div> 
</div>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-2" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-sm-9 col-form-label col-form-label-sm">VJCCホームページ</label>
    </div>
    <div class="col-sm-5" style="background-color:lavender;">{{$order->vjcc}}</div> 
</div>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-2" style="background-color:lavenderblush;">
       <label for="lgFormGroupInput" class="col-sm-9 col-form-label col-form-label-sm">SEKISHO ウェブサイト</label>
    </div>
    <div class="col-sm-5" style="background-color:lavender;">{{$order->web}}</div> 
</div>
</div>
<div class="content">
         <td><a class="btn btn-small btn-success" href="{{action('OrderController@edit',$order->ID)}}">Edit order</a> </td>         
</div> 

<div class="content">
 <div class="title m-b-md">
                   Condition List
  </div>
  <div class="content">
         <td><a class="btn btn-small btn-success" href="{{action('OrderController@edit',$order->ID)}}">Candidates's List</a> </td>         
</div> 
     <form method="GET" action="{{ url('cansearch') }}">
   <td> <input  id="companyinputid" name="division2" type="hidden"  value="{{order->ID}}"></td>
             <div>
                    <button class="btn btn-success">Search</button>
                </div>
        </form>
<div class="row">
    <div class="col-sm-2"  > </div>
    <div class="col-sm-8" style="background-color:lavenderblush;">
  <table class="table table-hover table-bordered" >

    <thead >
            <td nowrap><strong>No</strong></td> 
            <td nowrap><strong>条件付き名前</strong></td>
            <td nowrap><strong>から</strong></td>
            <td nowrap><strong>まで</strong></td>
            <td nowrap><strong>必須</strong></td>  
            <td nowrap><strong>候補額</strong></td>
    </thead>
    <tbody>
    @foreach($condition as $value)
        <tr>
          <td><a href="{{action('OrderController@show',$value->ID)}}">{{$value->ID}}</a></td>
          <td>{{ $value->title }}</td>
          <td>{{ $value->from }}</td>
          <td>{{ $value->to }}</td>
          <td>{{ $value->status }}</td>
          <td>{{ $value->candidates }}</td>
   
        </tr>
    @endforeach
    <tr>
      <td colspan="13">
        <div class="pagination">{!! str_replace('/?', '?', $condition->render()) !!}</div>      
      </td>
    </tr>
    </tbody>
    
</table>

</div>
<div class="col-sm-2"  > </div>

</div>
         
</div>

@endsection