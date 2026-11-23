<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Content;
use App\Models\PortfolioItem;
class DatabaseSeeder extends Seeder {
 public function run():void {
 $values=['profile'=>['name'=>'Your Name','title'=>'Laravel & Web Developer','bio'=>'I build responsive websites, Laravel applications, and practical business software.','email'=>'hello@example.com','phone'=>'','location'=>'Pakistan','photo'=>'','cv'=>'','github'=>'','linkedin'=>'','whatsapp'=>''],
 'hero'=>['eyebrow'=>'AVAILABLE FOR PROJECTS','heading'=>'Building digital experiences that work.','intro'=>'Modern web applications, thoughtful design, and reliable solutions.','button_text'=>'See My Work','typing_titles'=>'Full-Stack Web Developer|Software Engineer|MERN Stack Developer|Laravel Developer|Backend Developer'],
 'settings'=>['site_title'=>'Personal Portfolio','accent'=>'#C6A77B','secondary_accent'=>'#222222','footer'=>'Built with Laravel.','logo'=>'','favicon'=>'','theme'=>'dark','hero_background_type'=>'particles','particles'=>'1','animations'=>'1','dark_mode'=>'1','preloader'=>'1','scroll_animations'=>'1','project_effects'=>'1','whatsapp_enabled'=>'1','available_for_work'=>'1','meta_description'=>'Muhammad Dawood — Full-Stack Web Developer and Software Engineer specializing in Laravel and MERN Stack development.','social_facebook'=>'','social_instagram'=>'','social_x'=>'','social_youtube'=>'']];
 foreach($values as $section=>$items) foreach($items as $key=>$value) Content::firstOrCreate(['section'=>$section,'key'=>$key],['value'=>$value]);
 $samples=[['type'=>'skill','title'=>'Laravel','subtitle'=>'90','description'=>'Backend development'],['type'=>'skill','title'=>'MySQL','subtitle'=>'85','description'=>'Databases'],['type'=>'skill','title'=>'Bootstrap','subtitle'=>'90','description'=>'Responsive design'],['type'=>'service','title'=>'Website Development','description'=>'Fast, responsive websites for businesses.'],['type'=>'service','title'=>'Laravel Applications','description'=>'Custom dashboards and business systems.'],['type'=>'project','title'=>'Business Management System','subtitle'=>'Laravel · MySQL','description'=>'A sample project entry. Replace with your own project in the admin dashboard.'],['type'=>'education','title'=>'Your University','subtitle'=>'Degree / program','description'=>'Update this record with your education.']];
 foreach($samples as $sample) PortfolioItem::firstOrCreate(['type'=>$sample['type'],'title'=>$sample['title']],$sample);
 }
}
