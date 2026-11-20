<?php
namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\PortfolioItem;
use App\Models\ContactMessage;
class PublicController extends Controller {
 public function index(){
  $items=PortfolioItem::where('is_active',true)->orderBy('sort_order')->orderByDesc('id')->get()->groupBy('type');
  $content=Content::all()->groupBy('section')->map(fn($rows)=>$rows->pluck('value','key'));
  return view('public.home',compact('items','content'));
 }
 public function project(PortfolioItem $item){abort_unless($item->type==='project' && $item->is_active,404);return view('public.project',compact('item'));}
 public function contact(Request $request){
  $data=$request->validate(['name'=>'required|string|max:120','email'=>'required|email|max:190','subject'=>'required|string|max:190','message'=>'required|string|max:5000','website'=>'nullable|size:0']);
  unset($data['website']);ContactMessage::create($data);
  return back()->with('success','Your message has been received.');
 }
}
