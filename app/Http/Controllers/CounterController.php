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

class CounterController extends Controller
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
        $orgId = Auth::user()->getOrganizationUnitId();
        $counter = Counter::where('ou_fk',$orgId)
                ->get();
        $data = [
            'counter' => $counter
        ];
        return view('admin.loket.index',$data);
    }

    public function create(Request $request){
        return view('admin.loket.create');
    }

    public function store(Request $request)
    {
        $orgId = Auth::user()->getOrganizationUnitId();
        $data = Counter::firstOrNew(['id'=>$request->id]);
        $data->code_alpha = $request->code_alpha;
        $data->name = $request->name;
        $data->status = $request->status;
        $data->counter_type = $request->counter_type;
        $data->ou_fk = $orgId;
        $data->save();
        if($data){
            flash('Data Berhasil Disimpan', 'alert-success');
        }else{
            flash('Data Gagal Disimpan', 'alert-danger');
        }
        return redirect()->route('counter_master.index');
    }

    public function edit($id)
    {
        $orgId = Auth::user()->getOrganizationUnitId();
        $counter = Counter::where('ou_fk',$orgId)
                    ->where('id',$id)
                    ->first();
        $data = [
            'counter' => $counter
        ];
        return view('admin.loket.edit',$data);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $counter = Counter::destroy($id);
        // $counter->delete();

        return response()->json($counter);
    }

}
