<?php
namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Content;
use App\Models\PortfolioItem;
use App\Models\ContactMessage;
class AdminController extends Controller {
 private const TYPES=['skill','project','service','experience','education','testimonial'];
 private const SECTIONS=['profile','hero','settings'];
 private function admin():void {abort_unless(Auth::user()?->is_admin,403);}
 private function type(string $type):void {abort_unless(in_array($type,self::TYPES,true),404);}
 private function section(string $section):void {abort_unless(in_array($section,self::SECTIONS,true),404);}
 public function loginForm(){return Auth::check() && Auth::user()->is_admin ? redirect()->route('admin.dashboard') : view('admin.login');}
 public function login(Request $request){$data=$request->validate(['email'=>'required|email','password'=>'required|string']);
 if(Auth::attempt($data,$request->boolean('remember')) && Auth::user()->is_admin){$request->session()->regenerate();return redirect()->route('admin.dashboard');}
 Auth::logout();return back()->withErrors(['email'=>'Invalid administrator credentials.'])->onlyInput('email');}
 public function logout(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('login');}
 public function dashboard(){ $this->admin();$counts=PortfolioItem::selectRaw('type,COUNT(*) as total')->groupBy('type')->pluck('total','type');$unread=ContactMessage::where('is_read',false)->count();$recent=ContactMessage::latest()->limit(5)->get();return view('admin.dashboard',compact('counts','unread','recent'));}
 public function editContent(string $section){$this->admin();$this->section($section);$values=Content::where('section',$section)->pluck('value','key');return view('admin.content',compact('section','values'));}
 public function saveContent(Request $request,string $section){$this->admin();$this->section($section);
 $fields=['profile'=>['name','title','bio','email','phone','location','github','linkedin','whatsapp'], 'hero'=>['eyebrow','heading','intro','button_text'],'settings'=>['site_title','accent','footer']][$section];
 $rules=[];foreach($fields as $field){$rules[$field]=$field==='email'?'nullable|email|max:190':($field==='bio'||$field==='intro'?'nullable|string|max:5000':'nullable|string|max:500');}
 foreach(['github','linkedin'] as $f)if(isset($rules[$f]))$rules[$f]='nullable|url|max:500';
 if($section==='settings')$rules['accent']='nullable|regex:/^#[0-9a-fA-F]{6}$/';
 $data=$request->validate($rules);
 foreach($fields as $field)Content::updateOrCreate(['section'=>$section,'key'=>$field],['value'=>$data[$field]??'']);
 $images=['profile'=>['photo'=>'image','cv'=>'file'],'hero'=>['background'=>'image','personal_image'=>'image'],'settings'=>['logo'=>'image','favicon'=>'image']][$section];
 foreach($images as $key=>$kind){if($request->boolean('remove_'.$key)){ $old=Content::getValue($section,$key);Content::updateOrCreate(['section'=>$section,'key'=>$key],['value'=>'']);if($old)\Illuminate\Support\Facades\Storage::disk('public')->delete($old);continue;}if($request->hasFile($key)){$request->validate([$key=>$kind==='image'?'image|mimes:jpg,jpeg,png,webp|max:4096':'file|mimes:pdf|max:5120']);$path=$request->file($key)->store('portfolio','public');$old=Content::getValue($section,$key);Content::updateOrCreate(['section'=>$section,'key'=>$key],['value'=>$path]);if($old)\Illuminate\Support\Facades\Storage::disk('public')->delete($old);}}
 return back()->with('success','Changes saved. Refresh the public website to see them.');}
 public function items(string $type){$this->admin();$this->type($type);$items=PortfolioItem::where('type',$type)->orderBy('sort_order')->latest()->paginate(20);return view('admin.items',compact('type','items'));}
 public function itemForm(string $type,?PortfolioItem $item=null){$this->admin();$this->type($type);if($item)abort_unless($item->type===$type,404);return view('admin.item-form',compact('type','item'));}
 public function saveItem(Request $request,string $type,?PortfolioItem $item=null){$this->admin();$this->type($type);if($item)abort_unless($item->type===$type,404);
 $data=$request->validate(['title'=>'required|string|max:190','subtitle'=>'nullable|string|max:190','description'=>'nullable|string|max:10000','url'=>'nullable|url|max:2048','secondary_url'=>'nullable|url|max:2048','meta'=>'nullable|string|max:5000','sort_order'=>'nullable|integer|min:0|max:99999','image'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:4096','gallery'=>'nullable|array|max:12','gallery.*'=>'image|mimes:jpg,jpeg,png,webp|max:4096']);
 unset($data['image'],$data['gallery']);$data['type']=$type;$data['is_active']=$request->boolean('is_active');$data['is_featured']=$request->boolean('is_featured');$item=$item??new PortfolioItem();$old=$item->image;
 if($request->hasFile('image'))$data['image']=$request->file('image')->store('portfolio','public');$item->fill($data)->save();if(isset($data['image'])&&$old)\Illuminate\Support\Facades\Storage::disk('public')->delete($old);
 if($type==='project' && $request->hasFile('gallery'))foreach($request->file('gallery') as $upload)$item->images()->create(['path'=>$upload->store('portfolio/projects','public')]);
 return redirect()->route('admin.items',$type)->with('success','Item saved.');}
 public function deleteItem(string $type,PortfolioItem $item){$this->admin();$this->type($type);abort_unless($item->type===$type,404);if($item->image)\Illuminate\Support\Facades\Storage::disk('public')->delete($item->image);foreach($item->images as $image)\Illuminate\Support\Facades\Storage::disk('public')->delete($image->path);$item->delete();return back()->with('success','Item deleted.');}
 public function deleteProjectImage(string $type,PortfolioItem $item,\App\Models\ProjectImage $image){
 $this->admin();abort_unless($type==='project' && $item->type==='project' && $image->portfolio_item_id===$item->id,404);
 \Illuminate\Support\Facades\Storage::disk('public')->delete($image->path);$image->delete();return back()->with('success','Screenshot deleted.');}
 public function messages(){ $this->admin();$messages=ContactMessage::latest()->paginate(20);return view('admin.messages',compact('messages'));}
 public function readMessage(ContactMessage $message){$this->admin();$message->update(['is_read'=>true]);return back();}
 public function deleteMessage(ContactMessage $message){$this->admin();$message->delete();return back()->with('success','Message deleted.');}
 public function account(){ $this->admin();return view('admin.account');}
 public function saveAccount(Request $request){$this->admin();$user=Auth::user();$data=$request->validate(['name'=>'required|string|max:190','email'=>['required','email','max:190',Rule::unique('users')->ignore($user->id)],'current_password'=>'nullable|required_with:password','password'=>'nullable|string|min:12|confirmed']);
 if(!empty($data['password'])){if(!Hash::check($data['current_password']??'',$user->password))return back()->withErrors(['current_password'=>'Current password is incorrect.']);$user->password=Hash::make($data['password']);}
 $user->name=$data['name'];$user->email=$data['email'];$user->save();return back()->with('success','Account updated.');}
}
