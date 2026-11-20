<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProjectImage extends Model {
 protected $fillable=['portfolio_item_id','path'];
 public function project(){return $this->belongsTo(PortfolioItem::class,'portfolio_item_id');}
}
