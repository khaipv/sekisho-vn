<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Division;
use App\Orders;
use App\Master;
use App\User;
use App\Units;
use App\Candidates;
use App\Ca_OrderCandidates;
use App\Library\CommonFunction;
use DB;
use Carbon\Carbon; 
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class OrderCandiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        $order =  Orders::find($id);
        $master =  Master::all();
        $statuslist = Master::where('type','canoType')->orderBy('sort', 'asc')->get();
        $candilist =DB::table('ca_OrderCandidates as oc')
        ->join('candidates as ca','ca.id','=','oc.idCandidates')  
        ->select('ca.*','oc.id as ocID','oc.*','oc.idOrder as ocOrderID','ca.id as caid' )
        ->where('idOrder','=',$id)
        ->orderBy('oc.id', 'DESC')->get();  
         return view('order.udpOrderCandi', compact('order','candilist','master','statuslist'));


      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       dd("destroy");
  
    }


    public function ordercandiSearch(Request $request)
    {
         $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
         $cansFist=$request->input('canFirstsrc');
         $cansMidle=$request->input('canMidlesrc');
         $cansLast=$request->input('canLastsrc');
         $canNoFrom=$request->input('canNoFrom');
         $canNoTo=$request->input('canNoTo');
         $canEmail=$request->input('canEmail');
         $canPhone=$request->input('canPhone'); 
         $canorderID=$request->input('orderID');
         $cansStatus=$request->input('canStatus');

         $ocCanArr=ca_OrderCandidates::   
          where('idOrder', '=', $canorderID)->pluck('idCandidates')->toArray();
           $master =  Master::all();
           $statuslist = Master::where('type','canoType')->orderBy('sort', 'asc')->get();

         $queryI = Candidates::query()->sortable();  //Initializes the query
                 $queryI 
                 ->leftJoin('ca_OrderCandidates as oc', function($join) use ($canorderID)
                    {
                        $join->on('candidates.id', '=', 'oc.idCandidates');
                        $join->on('oc.idOrder','=',DB::raw("'".$canorderID."'"));
                    })
                 ->select('candidates.*','oc.id as ocID','oc.idOrder as ocOrderID','oc.*'
                  ,'candidates.id as caid' )

                  ->where(function ($query)use ($cansFist) {
           if (!is_null($cansFist)) {
                 $query->Where ('firstName','like','%' . $cansFist . '%' );
                   } })
    ->where(function ($query)use ($cansMidle) {
                 $query->Where ('candidates.midleName','like','%' . $cansMidle . '%' );
                    })
     ->where(function ($query)use ($cansLast) {
                 $query->Where ('candidates.lastName','like','%' . $cansLast . '%' );
            
                    })
           ->where(function ($query)use ($canPhone) {
                if (!is_null($canPhone)) {
               $query->where ('candidates.mobile','like','%' . $canPhone . '%' );
                    }})
  
           ;
             if (!is_null($canNoFrom)) {
                $queryI->whereRaw('CAST(candidates.code AS UNSIGNED) >='.$canNoFrom);
             }
          if (!is_null($canNoTo)) {
                $queryI->whereRaw('CAST(candidates.code AS UNSIGNED) <='.$canNoTo);
             }
          if (!is_null($canEmail)) {
             $queryI->whereRaw("candidates.email like '%".$canEmail."%'");
          }
              $queryI->orWhere(function($query) use ($ocCanArr, $canorderID)
    {
        $query->where ('idCandidates','=' . $canorderID . '%' );
        $query->orwherein('candidates.id', $ocCanArr);
            
    });

            $candilist=$queryI->orderBy('ocID', 'DESC')
                   ->sortable() 
              ->paginate(50);
               $order =  Orders::find($canorderID);
return view('order.udpOrderCandi', compact('order','candilist','master','statuslist'));
            

    }

 public function updateOrderCandi(Request $request)
    {
        $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
          $canIDArr=$canIntroduce=$canEnter=$canNote=$canAccept=array();
          $orderID=$request->orderID2;
          $canIDArr=$request->canid;
          $canIntroduce=$request->introduceDate;
          $canEnter=$request->enterDate;
          $canNote=$request->note;
          $canAccept=$request->accept;
          $canStatus=$request->canStatus;
           $ocCanlst=ca_OrderCandidates::   
          where('idOrder', '=', $orderID)->get();
      
         
            for ($i=0; $i <sizeof($canIDArr) ; $i++)  {
                //start for
                 if (!is_null($canAccept)&& in_array($canIDArr[$i],$canAccept)) {
                 
                 $item=Ca_OrderCandidates::updateOrCreate(
                
              [
              'idOrder'=>$orderID,
              'idCandidates'=>$canIDArr[$i],
              ],
                           [
                            'introduceDate'=>  $canIntroduce[$i],
                            'enterDate'=>  $canEnter[$i],
                            'note'=>$canNote[$i] ,   
                            'status'=>$canStatus[$i],                      
                           ]);
                 
                          $item->save();
        }
       }  //end if data changed
       // end for

       return $this->edit($orderID);
            
          
    }
      public function destroyCandi($id)
    {
               $user = Auth::user();
       if (is_null($user) ) {
           return  redirect('/');
          }
        $ordCa = Ca_OrderCandidates::find($id);
        $idOrder=$ordCa->idOrder;
        $ordCa->delete();
        return $this->edit($idOrder);
  
    }

     
}