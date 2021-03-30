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
use DB;

class Queuefov2Controller extends Controller
{
    use AuthenticateModulTrait;
    private $modul = 'queuefo';
 
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
        $r_filter = $filter;
        $counter_id = '%';
        $counter_type = '';
        $limit = 3;
        $filter_fix = '';
        //untuk dinamis
        $counterexp = '';
        $emergency = 0;
        $c_id = 0;
        $c_type = 0;
        if(isset($request->filter)){
            $counterexp = explode('-',$request->filter);
        }
        //---
        if(isset($request->filter)){
            $data = explode('-',$request->filter);
            $c_type = $data[1];
            $c_id = $data[0];
            if($data[1] == 2){
                $filter = $data[0];
                $filter_fix = $data[0];
            }else{
                $filter = $data[0];
            }
            $counter_type = $data[1];
            $emergency = $data[2];
        }else{
            $filter = 'all';
        }

        if($filter !== 'all'){
            $counter_id = $filter;
            $limit = 1;
        }
        
        $orgId = Auth::user()->getOrganizationUnitId();
        $counter = Counter::where('status',1)->where('ou_fk',$orgId)->get();
        $counter_reg = CounterQueue::leftJoin('mst_counter as c',function($join){
                        $join->on('c.id','=','counter_registration_queue.counter_id');
                    })
                    ->select(
                        'c.name as counter_name',
                        'counter_registration_queue.current_queue as current_queue',
                        'counter_registration_queue.counter_type as counter_type',
                        'counter_registration_queue.counter_id as counter_id',
                        'counter_registration_queue.date_visit as date_visit'
                        
                    );
                    if($filter !== 'all'){
                        $counter_reg = $counter_reg->where('counter_registration_queue.counter_id','like',$counterexp[0]);
                    };
                    $counter_reg = $counter_reg->where('counter_registration_queue.ou_fk',$orgId)
                    ->where('counter_registration_queue.date_visit',date('Y-m-d'))
                    ->get();
        
        $counter_reg_que = CounterQueue::where('ou_fk',$orgId);
                        if($filter !== 'all'){
                            $counter_reg_que = $counter_reg_que->where('counter_id','like',$counterexp[0]);
                        }
                        $counter_reg_que = $counter_reg_que->where('date_visit',date('Y-m-d'))
                        ->first();
        
        $total_data = CounterRegistration::where('is_next',1)
                        ->orWhere('is_skip',0)
                        ->where('date_visit',date('Y-m-d'))
                        ->where('counter_registration.counter_id','like',$counter_id)
                        ->where('ou_fk',$orgId)
                        ->count();

        
        
        $data = [
            'counter' => $counter,
            'counter_reg' => $counter_reg,
            'filter' => $filter,
            'total_data' => $total_data,
            'counter_reg_que' => $counter_reg_que,
            'emergency' => $emergency,
            'counter_type' => $c_type,
            'counter_id' => $c_id
        ];
        
        return view('admin.antrianv2.management',$data);
    }

    function checkData(Request $request){
        $orgId = Auth::user()->getOrganizationUnitId();
        $counter_type = $request->counter_type;
        $counter_id = $request->counter_id;
        $limit = 1;
        if($counter_type == 'all'){
            $counter_type = '%';
            $counter_id = '%';
            $limit = 3;
        }
        if($counter_type == 2){
            $counter_id = 0;
        }

        $result = CounterRegistration::where('ou_fk',$orgId)
                    ->where('counter_id',$counter_id)
                    ->where('counter_type',$counter_type)
                    ->where('is_next',0)
                    ->where('is_skip',0)
                    ->where('date_visit',date('Y-m-d'))
                    ->orderBy('queue_number','ASC')
                    ->groupBy('counter_id')
                    ->limit($limit)
                    ->get();
        $total_data = CounterRegistration::where('is_next',0)
                        ->where('is_skip',0)
                        ->where('date_visit',date('Y-m-d'))
                        ->where('ou_fk',$orgId)
                        ->count();
        $counter_reg_que = CounterQueue::where('counter_id',$counter_id)
            ->where('ou_fk',$orgId)
            ->first();
        if($counter_reg_que == null){
            $counter_reg_que = 'false';
        }
        $data = [
            'result' => $result,
            'count' => $total_data,
            'counter_reg_queue' => $counter_reg_que
        ];
        
        return response()->json($data);
    }

    function next(Request $request){
        $counter_type = $request->counter_type;
        $counter_id = $request->counter_id;
        // $request->common_counter;
        date_default_timezone_set("Asia/Jakarta");
        $queue = $request->queue;
        $orgId = Auth::user()->getOrganizationUnitId();
        $result = CounterRegistration::where('counter_type',$counter_type)
                ->where('queue_number',$queue)
                ->where('date_visit',date('Y-m-d'))
                ->where('ou_fk',$orgId);
                if($counter_type == 1){
                    $result = $result->where('counter_id',$counter_id);
                }
                $result = $result->update([
                    'is_next' => 1,
                    'date_next' => date("Y-m-d H:i:s"),
                    'user_id' => Auth::user()->id,
                    'counter_next' => $counter_id
                ]);
        $current_que = '';
        if($counter_type == 2){
            $current_que = CounterQueue::where('counter_type',$counter_type)
                ->where('ou_fk',$orgId)
                ->where('date_visit',date('Y-m-d'))
                ->get();
        }else{
            $current_que = CounterQueue::where('counter_type',$counter_type)
                ->where('counter_id',$counter_id)
                ->where('counter_type',$counter_type)
                ->where('ou_fk',$orgId)
                ->where('date_visit',date('Y-m-d'))
                ->get();
        }
        
        $current_queue = array();
        foreach ($current_que as $key => $value) {
            $current_queue[$key] = $value->current_queue;
        }
        $result_reg = CounterRegistration::where('counter_type',$counter_type);
        if($counter_type == 1){
            $result_reg = $result_reg->where('counter_id',$counter_id);
        }
        $result_reg = $result_reg->where('ou_fk',$orgId)
                    ->where('date_visit',date('Y-m-d'))
                    ->where('is_next',0)
                    ->where('is_skip',0)
                    ->whereNotIn('queue_number',$current_queue)
                    ->orderBy('queue_number','ASC')
                    ->first();
        $result_next = '';
        if($result_reg != null){
            $result_next = CounterQueue::where('counter_type',$counter_type)
                        ->where('counter_id',$counter_id)
                        ->where('counter_type',$counter_type)
                        ->where('ou_fk',$orgId)
                        ->where('date_visit',date('Y-m-d'))
                        ->update([
                            'current_queue' => $result_reg->queue_number,
                            'current_code_alpha' => $result_reg->code_alpha
                        ]);
            $result_next = CounterRegistration::find($result_reg->id);
        }
        $data = [
            'result' => $result_next,
            'count' => $result_reg
        ];

        return response()->json($data);
    }
    function skip(Request $request){
        $counter_type = $request->counter_type;
        $counter_id = $request->counter_id;
        // $request->common_counter;
        
        $queue = $request->queue;
        $orgId = Auth::user()->getOrganizationUnitId();
        $result = CounterRegistration::where('counter_type',$counter_type)
                ->where('counter_type',$counter_type)
                ->where('queue_number',$qeueu)
                ->where('date_visit',date('Y-m-d'))
                ->where('ou_fk',$ou_fk)
                ->update([
                    'is_skip' => 1,
                    'user_id' => Auth::user()->id,
                    'current_code_alpha' => $result_reg->code_alpha,
                    'counter_next' => $counter_id
                ]);
        $current_que = '';
        if($counter_type == 2){
            $current_que = CounterQueue::where('counter_type',$counter_type)
                ->where('counter_type',$counter_type)
                ->where('ou_fk',$ou_fk)
                ->where('date_visit',date('Y-m-d'))
                ->get();
        }else{
            $current_que = CounterQueue::where('counter_type',$counter_type)
                ->where('counter_id',$counter_id)
                ->where('counter_type',$counter_type)
                ->where('ou_fk',$ou_fk)
                ->where('date_visit',date('Y-m-d'))
                ->get();
        }

        $current_queue = array();
        foreach ($current_que as $key => $value) {
            $current_queue[$key] = $value->current_queue;
        }
        
        $result_reg = CounterRegistration::where('counter_type',$counter_type)
                    ->where('ou_fk',$ou_fk)
                    ->where('date_visit',date('Y-m-d'))
                    ->where('is_next',0)
                    ->where('is_skip',0)
                    ->whereNotIn('queue_number',$current_queue)
                    ->orderBy('queue_number','ASC')
                    ->first();
        
        $result_next = CounterQueue::where('counter_type',$counter_type)
                    ->where('counter_id',$counter_id)
                    ->where('counter_type',$counter_type)
                    ->where('ou_fk',$ou_fk)
                    ->where('date_visit',date('Y-m-d'))
                    ->update([
                        'current_queue' => $result_reg->queue_number
                    ]);
        $result_next = CounterRegistration::find($result_reg->id);
        $data = [
            'result' => $result_next,
            'count' => $result_reg
        ];

        return response()->json($data);
    }

    function ready(Request $request){
        $counter_id = $request->counter_id;
        $counter_type = $request->counter_type;
        $orgId = Auth::user()->getOrganizationUnitId();
        $counter_reg = '';
        if($counter_type == 2){
            $result_que = CounterQueue::where('ou_fk',$orgId)
                        ->where('counter_type',$counter_type)
                        ->where('date_visit',date('Y-m-d'))
                        ->whereNotIn('counter_id',[$counter_id])
                        ->get();
            $current_que = array();
            foreach ($result_que as $key => $value) {
                $current_que[$key] = $value->current_queue;
            }
            $counter_reg = CounterRegistration::where('ou_fk',$orgId)
                        ->where('counter_type',$counter_type)
                        ->where('date_visit',date('Y-m-d'))
                        ->where('is_next',0)
                        ->where('is_skip',0)
                        ->whereNotIn('queue_number',$current_que)
                        ->orderBy('queue_number','ASC')
                        ->first();
            $result = CounterQueue::where('ou_fk',$orgId)
                        ->where('counter_id',$counter_id)
                        ->where('counter_type',$counter_type)
                        ->where('date_visit',date('Y-m-d'))
                        ->update([
                            'current_queue' => $counter_reg->queue_number
                        ]);
        }else{
            $result = CounterQueue::where('ou_fk',$orgId)
                    ->where('counter_id',$counter_id)
                    ->where('counter_type',$counter_type)
                    ->where('date_visit',date('Y-m-d'))
                    ->update([
                        'current_queue' => 1
                    ]);
        }
        $data = [
            'result' => $result,
            'count' => $counter_reg
        ];
        return response()->json($data);
    }

    function setFoQueue(Request $request){
        $counter_id = $request->counter_id;
        $counter_type = $request->counter_type;
        $orgId = Auth::user()->getOrganizationUnitId();
        $counter_reg = CounterRegistration::where('ou_fk',$orgId);
                    if($counter_type == 0){
                        $counter_reg = $counter_reg->where('counter_id',$counter_id);
                    }else{
                        $counter_reg = $counter_reg->where('counter_id','0');
                    }
                    $counter_reg = $counter_reg->where('counter_type',$counter_type)
                    ->where('date_visit',date('Y-m-d'))
                    ->where('is_next',0)
                    ->where('is_skip',0)
                    ->orderBy('queue_number','ASC')
                    ->first();
        $data = [
            'result' => $counter_reg
        ];
        return response()->json($data);
    }

    function extNext(Request $request){
        $orgId = Auth::user()->getOrganizationUnitId();
        $counter_type = $request->counter_type;
        $counter_id = $request->counter_id;
        $emergency = $request->emergency;

        $current_queue = array();
        $current_alpha = array();
        $current = '';
        $list = '';
        if($counter_type == 2){
            $list = CounterQueue::leftJoin('mst_counter as c',function($join){
                        $join->on('c.id','counter_registration_queue.counter_id');
                    })
                    ->where('counter_registration_queue.ou_fk',$orgId)
                    ->where('counter_registration_queue.counter_type',1)
                    ->where('c.emergency',1)
                    ->where('counter_registration_queue.date_visit',date('Y-m-d'))
                    ->get();
        }else if($counter_type == 1 && $emergency == 0){
            $list = CounterQueue::leftJoin('mst_counter as c',function($join){
                        $join->on('c.id','counter_registration_queue.counter_id');
                    })
                    ->where('counter_registration_queue.ou_fk',$orgId)
                    ->where('counter_registration_queue.counter_type',2)
                    ->where('c.emergency',0)
                    ->where('counter_registration_queue.date_visit',date('Y-m-d'))
                    ->get();
        }else{
            $list = CounterQueue::leftJoin('mst_counter as c',function($join){
                $join->on('c.id','counter_registration_queue.counter_id');
            })
            ->where('counter_registration_queue.ou_fk',$orgId)
            ->where('counter_registration_queue.counter_type',2)
            ->where('c.emergency',0)
            ->where('counter_registration_queue.date_visit',date('Y-m-d'))
            ->get();
        }

        foreach ($list as $key => $value) {
            $current = $current."concat('".$value->current_code_alpha."',".$value->current_queue.")";
            $current = $current.',';
        }
        $current = rtrim($current, ", ");

        if($counter_type == 1 && $emergency == 0){
            $current_que = CounterRegistration::where('ou_fk',$orgId)
                            ->where('is_next',0)
                            ->where('is_skip',0)
                            ->where('counter_id',0)
                            ->where('date_visit',date('Y-m-d'))
                            ->whereRaw('(concat(code_alpha,queue_number)) not in ('.$current.')')
                            ->first();
        }else if($counter_type == 1 && $emergency == 1){
            $current_que = CounterRegistration::where('ou_fk',$orgId)
                            ->where('is_next',0)
                            ->where('is_skip',0)
                            ->where('counter_id',0)
                            ->where('date_visit',date('Y-m-d'))
                            ->whereRaw('(concat(code_alpha,queue_number)) not in ('.$current.')')
                            ->first();
        }else{
            $current_que = CounterRegistration::leftJoin('mst_counter as c',function($join){
                                $join->on('c.id','counter_registration.counter_id');
                            })
                            ->where('c.emergency',1)
                            ->where('counter_registration.ou_fk',$orgId)
                            ->where('counter_registration.is_next',0)
                            ->where('counter_registration.is_skip',0)
                            ->where('counter_registration.date_visit',date('Y-m-d'))
                            ->whereRaw('(concat(counter_registration.code_alpha,counter_registration.queue_number)) not in ('.$current.')')
                            ->first();
        }

        $queue = CounterQueue::where('counter_id',$counter_id)
                ->where('date_visit',date('Y-m-d'))
                ->where('ou_fk',$orgId)
                ->update([
                    'current_queue' => $current_que->queue_number,
                    'current_code_alpha' => $current_que->code_alpha
                ]);

        $set_counter_next = CounterRegistration::where('id',$current_que->id)
                    ->update([
                        'is_next' => 1,
                        'date_next' => date("Y-m-d H:i:s"),
                        'user_id' => Auth::user()->id,
                        'counter_next' => $counter_id
                    ]);
        $data = [
            'current_queue' => $current_que->queue_number
        ];
        return response()->json($data);
    }

    function extSkip(){

    }

    function checkExtData(Request $request){
        $orgId = Auth::user()->getOrganizationUnitId();
        $counter_type = $request->counter_type;
        $counter_id = $request->counter_id;
        $emergency = $request->counter_emergency;
        $limit = 1;
        
        $current_que = '';
        $que_left = '';
        // if($emergency == 1){
        //     //get not emergency & general counter list
        //     $current_que = CounterQueue::where('ou_fk',$orgId)
        //                     ->where('counter_type',2)
        //                     ->get();
        //     $que_left = CounterRegistration::where('ou_fk',$orgId)
        //                     ->where('counter_type',2)
        //                     ->where('is_next',0)
        //                     ->where('is_skip',0)
        //                     ->where('date_visit',date('Y-m-d'))
        //                     ->count();
        // }else{
        //     //get emergency & general counter list
        //     $current_que = CounterQueue::leftJoin('mst_counter as c', function($join){
        //                         $join->on('c.id','counter_registration_queue.counter_id');
        //                     })
        //                     ->where('c.ou_fk',$orgId)
        //                     ->where('c.counter_type',1)
        //                     ->where('c.emergency',1)
        //                     ->get();
        //     $que_left = CounterRegistration::leftJoin('mst_counter as c',function($join){
        //                         $join->on('c.id','counter_registration.counter_id');
        //                     })
        //                     ->where('counter_registration.ou_fk',$orgId)
        //                     ->where('c.emergency',1)
        //                     ->where('counter_registration.is_next',0)
        //                     ->where('counter_registration.is_skip',0)
        //                     ->where('counter_registration.date_visit',date('Y-m-d'))
        //                     ->count();
        // }

        // $current_queue = array();
        // foreach ($current_que as $key => $value) {
        //     $current_queue[$key] = $value->current_queue;
        // }
        // $result_reg = CounterRegistration::where('counter_type',$counter_type);
        // if($counter_type == 1){
        //     $result_reg = $result_reg->where('counter_id',$counter_id);
        // }
        // $result_reg = $result_reg->where('ou_fk',$orgId)
        //             ->where('date_visit',date('Y-m-d'))
        //             ->where('is_next',0)
        //             ->where('is_skip',0)
        //             ->whereNotIn('queue_number',$current_queue)
        //             ->orderBy('queue_number','ASC')
        //             ->first();

        // queue left remain
        $queue_left = '';
        if($emergency == 1 && $counter_type == 1){
            $queue_left = CounterRegistration::where('ou_fk',$orgId)
                ->where('counter_type',2)
                ->where('is_next',0)
                ->where('is_skip',0)
                ->where('date_visit',date('Y-m-d'))
                ->whereNull('counter_next')
                ->whereNull('date_next')
                ->whereNull('user_id')
                ->count();
        }else if($emergency == 0 && $counter_type == 1){
            $queue_left = CounterRegistration::where('ou_fk',$orgId)
                ->where('counter_type',2)
                ->where('is_next',0)
                ->where('is_skip',0)
                ->where('date_visit',date('Y-m-d'))
                ->whereNull('counter_next')
                ->whereNull('date_next')
                ->whereNull('user_id')
                ->count();
        }else{
            $queue_left = CounterRegistration::leftJoin('mst_counter as c',function($join){
                    $join->on('c.id','counter_registration.counter_id');
                })
                ->where('c.emergency',1)
                ->where('counter_registration.ou_fk',$orgId)
                ->where('counter_registration.counter_type',1)
                ->where('counter_registration.is_next',0)
                ->where('counter_registration.is_skip',0)
                ->where('date_visit',date('Y-m-d'))
                ->whereNull('counter_registration.counter_next')
                ->whereNull('counter_registration.date_next')
                ->whereNull('counter_registration.user_id')
                ->count();
        }
        $data = [
            "queue_left" => $queue_left
        ];
        return response()->json($data);
    }
}
