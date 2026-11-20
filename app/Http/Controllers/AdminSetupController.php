<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
class AdminSetupController extends Controller {
 private function available():bool {
  abort_unless(Schema::hasTable('admin_setup'),503,'Run php artisan migrate before registering.');
  return !DB::table('admin_setup')->where('id',1)->exists() && !User::where('is_admin',true)->exists();
 }
 public function show(){abort_unless($this->available(),404);return view('admin.register');}
 public function store(Request $request){abort_unless($this->available(),404);
  $data=$request->validate(['name'=>'required|string|max:190','email'=>'required|email|max:190|unique:users,email','password'=>['required','confirmed',PasswordRule::min(12)->mixedCase()->numbers()]]);
  try {DB::transaction(function()use($data){
   // MySQL serializes competing setup attempts via a singleton row, unique primary key.
   if(DB::table('admin_setup')->where('id',1)->exists() || User::where('is_admin',true)->exists())throw new \RuntimeException('Administrator already exists.');
   $user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password']),'is_admin'=>true]);
   DB::table('admin_setup')->insert(['id'=>1,'user_id'=>$user->id,'created_at'=>now(),'updated_at'=>now()]);
  });}catch(\Illuminate\Database\QueryException $e){return redirect()->route('login')->with('status','Administrator registration is already complete.');}
  return redirect()->route('login')->with('status','Administrator created. Please sign in.');
 }
 public function forgot(){return view('admin.forgot');}
 public function sendReset(Request $request){$request->validate(['email'=>'required|email']);Password::sendResetLink($request->only('email'));return back()->with('status','If that email exists, a reset link has been sent.');}
 public function resetForm(string $token,Request $request){return view('admin.reset',['token'=>$token,'email'=>$request->query('email','')]);}
 public function reset(Request $request){$request->validate(['token'=>'required','email'=>'required|email','password'=>['required','confirmed',PasswordRule::min(12)->mixedCase()->numbers()]]);
  $status=Password::reset($request->only('email','password','password_confirmation','token'),function(User $user,string $password){$user->forceFill(['password'=>Hash::make($password),'remember_token'=>Str::random(60)])->save();});
  return $status===Password::PASSWORD_RESET?redirect()->route('login')->with('status',__($status)):back()->withErrors(['email'=>__($status)]);
 }
}
