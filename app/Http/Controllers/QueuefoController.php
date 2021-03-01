<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CounterQueue;
use App\Models\CounterRegistration;
use App\Models\Counter;

class QueuefoController extends Controller
{
    public function __construct()
    {
       date_default_timezone_set('Asia/Jakarta');
    }

    public function index(Request $request)
    {
        $filter = '';
        $counter_id = '%';
        $counter_type = '';
        $limit = 3;
        $filter_fix = '';
        //untuk dinamis
        $counterexp = '';
        if(isset($request->filter)){
            $counterexp = explode('-',$request->filter);
        }
        //---
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
        
        $counter = Counter::where('status',1)->get();
        // $counter_reg = CounterRegistration::leftJoin('mst_counter as c',function($join){
        //                 $join->on('c.id','=','counter_id');
        //             })
        //             ->select(
        //                 'c.name as counter_name',
        //                 'counter_registration.id as id',
        //                 'counter_registration.counter_id as counter_id',
        //                 'counter_registration.date_visit as date_visit',
        //                 'counter_registration.queue_number as queue_number',
        //                 'counter_registration.counter_type as counter_type',
        //             )
        //             ->where('counter_registration.date_visit',date('Y-m-d'))
        //             ->where('counter_registration.counter_id','like',$counter_id)
        //             ->where('counter_registration.is_next',0)
        //             ->where('counter_registration.is_skip',0)
        //             ->where('counter_registration.ou_fk',$orgId)
        //             ->groupBy('counter_registration.counter_id')
        //             ->orderBy('counter_registration.queue_number','ASC')->limit($limit)->get();

        $counter_reg = CounterQueue::leftJoin('mst_counter as c',function($join){
                        $join->on('c.id','=','counter_registration_queue.counter_id');
                    })
                    ->select(
                        'c.name as counter_name',
                        'counter_registration_queue.current_queue as current_queue',
                        'counter_registration_queue.counter_type as counter_type',
                        'counter_registration_queue.counter_id as counter_id',
                        'counter_registration_queue.date_visit as date_visit'
                        
                    )
                    // ->where('counter_registration_queue.counter_id','like',$counterexp[0])
                    ->where('counter_registration_queue.date_visit',date('Y-m-d'))
                    ->get();
        
        // $counter_reg_que = CounterQueue::where('counter_id','like',$counterexp[0])
        //                 ->where('date_visit',date('Y-m-d'))
        //                 ->first();
        
        // $total_data = CounterRegistration::where('is_next',1)
        //                 ->orWhere('is_skip',0)
        //                 ->where('date_visit',date('Y-m-d'))
        //                 ->where('counter_registration.counter_id','like',$counter_id)
        //                 ->count();
        if($filter === 'all'){
            
        }else{
            if($counter_type != 1){
                $filter = $filter_fix;
            }
        }

        // dd($counter_reg);

        // return view('admin.antrian.management',compact('counter','counter_reg','filter','total_data','counter_reg_que'));
        return view('admin.antrian.management',compact('counter','counter_reg','filter'));
    }

    function checkData(Request $request){
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

        $result = CounterRegistration::where('counter_id','like',$counter_id)
                    ->where('counter_type','like',$counter_type)
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
                        ->count();
        $counter_reg_que = CounterQueue::where('counter_id','like',$counter_id)
            ->first();
        if(count($counter_reg_que) == 0){
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
        
        $queue = $request->queue;
        $result = CounterRegistration::where('counter_type',$counter_type)
                ->where('queue_number',$queue)
                ->where('date_visit',date('Y-m-d'))
                ->update([
                    'is_next' => 1
                ]);
        $current_que = '';
        if($counter_type == 2){
            $current_que = CounterQueue::where('counter_type',$counter_type)
                ->where('date_visit',date('Y-m-d'))
                ->get();
        }else{
            $current_que = CounterQueue::where('counter_type',$counter_type)
                ->where('counter_id',$counter_id)
                ->where('counter_type',$counter_type)
                ->where('date_visit',date('Y-m-d'))
                ->get();
        }
        
        $current_queue = array();
        foreach ($current_que as $key => $value) {
            $current_queue[$key] = $value->current_queue;
        }
        $result_reg = CounterRegistration::where('counter_type',$counter_type)
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
                        ->where('date_visit',date('Y-m-d'))
                        ->update([
                            'current_queue' => $result_reg->queue_number
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
        $result = CounterRegistration::where('counter_type',$counter_type)
                ->where('counter_type',$counter_type)
                ->where('queue_number',$qeueu)
                ->where('date_visit',date('Y-m-d'))
                ->update([
                    'is_skip' => 1
                ]);
        $current_que = '';
        if($counter_type == 2){
            $current_que = CounterQueue::where('counter_type',$counter_type)
                ->where('counter_type',$counter_type)
                ->where('date_visit',date('Y-m-d'))
                ->get();
        }else{
            $current_que = CounterQueue::where('counter_type',$counter_type)
                ->where('counter_id',$counter_id)
                ->where('counter_type',$counter_type)
                ->where('date_visit',date('Y-m-d'))
                ->get();
        }

        $current_queue = array();
        foreach ($current_que as $key => $value) {
            $current_queue[$key] = $value->current_queue;
        }
        
        $result_reg = CounterRegistration::where('counter_type',$counter_type)
                    ->where('date_visit',date('Y-m-d'))
                    ->where('is_next',0)
                    ->where('is_skip',0)
                    ->whereNotIn('queue_number',$current_queue)
                    ->orderBy('queue_number','ASC')
                    ->first();
        
        $result_next = CounterQueue::where('counter_type',$counter_type)
                    ->where('counter_id',$counter_id)
                    ->where('counter_type',$counter_type)
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
        $counter_reg = '';
        if($counter_type == 2){
            $result_que = CounterQueue::where('counter_type',$counter_type)
                        ->where('date_visit',date('Y-m-d'))
                        ->whereNotIn('counter_id',[$counter_id])
                        ->get();
            $current_que = array();
            foreach ($result_que as $key => $value) {
                $current_que[$key] = $value->current_queue;
            }
            $counter_reg = CounterRegistration::where('counter_type',$counter_type)
                        ->where('date_visit',date('Y-m-d'))
                        ->where('is_next',0)
                        ->where('is_skip',0)
                        ->whereNotIn('queue_number',$current_que)
                        ->orderBy('queue_number','ASC')
                        ->first();
            $result = CounterQueue::where('counter_id',$counter_id)
                        ->where('counter_type',$counter_type)
                        ->where('date_visit',date('Y-m-d'))
                        ->update([
                            'current_queue' => $counter_reg->queue_number
                        ]);
        }else{
            $result = CounterQueue::where('counter_id',$counter_id)
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
        $counter_reg = CounterRegistration::where('counter_id',$counter_id)
                    ->where('counter_type',$counter_type)
                    ->where('date_visit',date('Y-m-d'))
                    ->where('is_next',0)
                    ->where('is_skip',0)
                    ->orderBy('queue_number','ASC')
                    ->first();
        $result = CounterQueue::where('counter_id',$counter_id)
                ->where('counter_type',$counter_type)
                ->where('date_visit',date('Y-m-d'))
                ->update([
                    'current_queue' => $counter_reg->queue_number
                ]);
        $data = [
            'result' => $result
        ];
        return response()->json($data);
    }
}
