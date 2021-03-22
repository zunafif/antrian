<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\AuthenticateModulTrait;
use App\Models\CounterQueue;
use App\Models\CounterRegistration;
use App\Models\Counter;
use App\Models\Role;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use Config;

class UserController extends Controller
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
        $user = User::where('ou_fk',$orgId)
                ->get();
        
        $data = [
            'user' => $user
        ];
        return view('admin.user.index',$data);
    }

    public function create(Request $request){
        $role = Role::orderBy('id','DESC')->get();
        $data = [
            'role' => $role,
            'role_user' => ''
        ];
        return view('admin.user.create', $data);
    }

    public function store(Request $request)
    {
        $orgId = Auth::user()->getOrganizationUnitId();
        $data = User::firstOrNew(['id'=>$request->id]);
        $data->name = $request->name;
        $data->email = $request->email;
        if($request->password == '' || $request->password == null){
            $data->password = Hash::make(Config::get('antrian.default_password'));
        }else{
            $data->password = Hash::make($request->password);
        }
        $data->ou_fk = $orgId;
        $data->save();
        $data->assignRole($request->role);
        if($data){
            flash('Data Berhasil Disimpan', 'alert-success');
        }else{
            flash('Data Gagal Disimpan', 'alert-danger');
        }
        return redirect()->route('user_master.index');
    }

    public function edit($id)
    {
        $orgId = Auth::user()->getOrganizationUnitId();
        $user = User::where('ou_fk',$orgId)
                    ->where('id',$id)
                    ->first();
        $role_user = $user->getRoleNames();
        $role = Role::orderBy('id','ASC')->get();
         
        $data = [
            'user' => $user,
            'role' => $role,
            'role_user' => $role_user[0]
        ];
        
        return view('admin.user.edit',$data);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $counter = Counter::destroy($id);
        // $counter->delete();

        return response()->json($counter);
    }

}
