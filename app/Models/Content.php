<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Content extends Model {
 protected $table='contents';
 protected $fillable=['section','key','value'];
 public static function getValue(string $section,string $key,string $default=''): string {return (string)(static::where('section',$section)->where('key',$key)->value('value') ?? $default);}
}
