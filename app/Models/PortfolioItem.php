<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PortfolioItem extends Model {
 public function images(){return $this->hasMany(ProjectImage::class);}
 protected $fillable=['type','title','subtitle','description','image','url','secondary_url','meta','sort_order','is_active','is_featured'];
 protected function casts(): array {return ['is_active'=>'boolean','is_featured'=>'boolean','sort_order'=>'integer'];}
}
