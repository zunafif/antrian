<?php
namespace App\Traits;

use App\Models\PatientRegistration;
use App\Models\PolyclinicBaseSchedule;
use App\Models\Polyclinic;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RoleModule;
use App\User;
use Auth;

use Carbon\Carbon;
trait AuthenticateModulTrait
{
    public function isAuthenticate($moduleUser){
        $thismodule='';
        $authenticate = false;
        $modules = Auth::user()->getModules(Auth::user()->getRoleId());

        foreach ($modules as $res){
            $thismodule= $res->name;
            if($thismodule==$moduleUser){
                $authenticate= true;
                break;
            }    
        }

        return $authenticate;
    }
}
