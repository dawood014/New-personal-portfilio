<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Content;
use App\Models\PortfolioItem;
class DatabaseSeeder extends Seeder {
 public function run():void {
 $values=['profile'=>['name'=>'Your Name','title'=>'Laravel & Web Developer','bio'=>'I build responsive websites, Laravel applications, and practical business software.','email'=>'hello@example.com','phone'=>'','location'=>'Pakistan','photo'=>'','cv'=>'','github'=>'','linkedin'=>'','whatsapp'=>''],
 'hero'=>['eyebrow'=>'AVAILABLE FOR PROJECTS','heading'=>'Building digital experiences that work.','intro'=>'Modern web applications, thoughtful design, and reliable solutions.','button_text'=>'See My Work'],
 'settings'=>['site_title'=>'Personal Portfolio','accent'=>'#7c5cff','footer'=>'Built with Laravel.','logo'=>'','favicon'=>'']];
 foreach($values as $section=>$items) foreach($items as $key=>$value) Content::firstOrCreate(['section'=>$section,'key'=>$key],['value'=>$value]);
 $samples=[['type'=>'skill','title'=>'Laravel','subtitle'=>'90','description'=>'Backend development'],['type'=>'skill','title'=>'MySQL','subtitle'=>'85','description'=>'Databases'],['type'=>'skill','title'=>'Bootstrap','subtitle'=>'90','description'=>'Responsive design'],['type'=>'service','title'=>'Website Development','description'=>'Fast, responsive websites for businesses.'],['type'=>'service','title'=>'Laravel Applications','description'=>'Custom dashboards and business systems.'],['type'=>'project','title'=>'Business Management System','subtitle'=>'Laravel · MySQL','description'=>'A sample project entry. Replace with your own project in the admin dashboard.'],['type'=>'education','title'=>'Your University','subtitle'=>'Degree / program','description'=>'Update this record with your education.']];
 foreach($samples as $sample) PortfolioItem::firstOrCreate(['type'=>$sample['type'],'title'=>$sample['title']],$sample);
 }
}
