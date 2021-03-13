<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\AuthenticateModulTrait;
use App\Models\CounterQueue;
use App\Models\CounterRegistration;
use App\Models\Counter;
use Auth;
use Config;

class QueueinfoController extends Controller
{
    use AuthenticateModulTrait;
    private $modul = 'manajemen_antrian';
 
    protected $paging;
    public function __construct()
    {
       $this->middleware('auth');
       $this->paging = Config::get('site.paging');
       date_default_timezone_set('Asia/Jakarta');
    }

    public function index(Request $request)
    {
        // $authenticate = $this->isAuthenticate($this->modul);

        // if(!$authenticate) {
        //     abort(403,"Forbidden");
        // }
        
        $filter = '';
        $counter_id = '%';
        $counter_type = '';
        $limit = 3;
        $filter_fix = '';
        if(isset($request->filter)){
            $data = explode('-',$request->filter);
            if($data[1] == 2){
                $filter = 0;
                $filter_fix = $data[0];
            }else{
                $filter = $data[0];
            }
            $counter_type = $data[1];
        }else{
            $filter = 'all';
        }
        
        if($filter !== 'all'){
            $counter_id = $filter;
            $limit = 1;
        }
        
        $orgId = Auth::user()->getOrganizationUnitId();
        // dd($orgId);
        $counter = Counter::where('status',1)->where('ou_fk',$orgId)->get();
        $counter_que = CounterQueue::leftJoin('mst_counter as c',function($join){
                        $join->on('c.id','=','counter_registration_queue.counter_id');
                    })
                    ->select(
                        'c.name as name',
                        'counter_registration_queue.counter_id as counter_id',
                        'counter_registration_queue.counter_type as counter_type',
                        'counter_registration_queue.current_queue as current_queue'
                    )
                    ->where('c.ou_fk',$orgId)
                    ->where('counter_registration_queue.date_visit',date('Y-m-d'))
                    ->get();
        $count_que = CounterRegistration::where('counter_type',2)
                        ->where('ou_fk',$orgId)
                        ->where('date_visit',date('Y-m-d'))
                        ->count();
        if($filter === 'all'){
            
        }else{
            if($counter_type != 1){
                $filter = $filter_fix;
            }
        }
        $general_counter = Counter::where('ou_fk',$orgId)->where('status',1)->where('counter_type',2)->count();
        return view('admin.antrian.index',compact('counter','counter_que','filter','count_que','general_counter'));
    }

    function checkData(Request $request){
        $orgId = Auth::user()->getOrganizationUnitId();
        $counter_que = CounterQueue::leftJoin('mst_counter as c',function($join){
            $join->on('c.id','=','counter_registration_queue.counter_id');
        })
        ->select(
            'c.name as name',
            'counter_registration_queue.counter_id as counter_id',
            'counter_registration_queue.counter_type as counter_type',
            'counter_registration_queue.current_queue as current_queue'
        )
        ->where('c.ou_fk',$orgId)
        ->where('counter_registration_queue.date_visit',date('Y-m-d'))
        ->get();
      
        $general_counter = Counter::where('ou_fk',$orgId)->where('status',1)->where('counter_type',2)->count();
        $data = [
            'result' => $counter_que,
            
            'general_counter' => $general_counter
        ];
        return response()->json($data);
    }

}
