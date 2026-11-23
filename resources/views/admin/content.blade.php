@extends('layouts.admin')
@section('body')
<h1 class="h2 mb-4">Edit {{ ucfirst($section) }}</h1>
<div class="card p-4">
    <form method="post" enctype="multipart/form-data" action="{{ route('admin.content.save',$section)">
        @csrf
        @php
        $fields=[
          'profile'=>[
            'name'=>'text','title'=>'text','bio'=>'textarea','email'=>'email','phone'=>'text','location'=>'text',
            'github'=>'url','linkedin'=>'url','whatsapp'=>'text','photo'=>'image','cv'=>'file'
          ],
          'hero'=>[
            'eyebrow'=>'text','heading'=>'text','intro'=>'textarea','button_text'=>'text','typing_titles'=>'textarea',
            'background'=>'image','background_video'=>'video','personal_image'=>'image'
          ],
          'settings'=>[
            'site_title'=>'text','accent'=>'color','secondary_accent'=>'color','footer'=>'textarea',
            'theme'=>'select','hero_background_type'=>'select','particles'=>'checkbox','animations'=>'checkbox',
            'dark_mode'=>'checkbox','preloader'=>'checkbox','scroll_animations'=>'checkbox','project_effects'=>'checkbox',
            'whatsapp_enabled'=>'checkbox','available_for_work'=>'checkbox','social_facebook'=>'url',
            'social_instagram'=>'url','social_x'=>'url','social_youtube'=>'url','meta_description'=>'textarea',
            'logo'=>'image','favicon'=>'image','og_image'=>'image'
          ]
        ][$section];
        @endphp
        @foreach($fields as $key=>$kind)
            <div class="mb-3">
                <label class="form-label fw-semibold">{{ ucwords(str_replace('_',' ',$key)) }}</label>
                @if($kind==='textarea')
                    <textarea class="form-control" rows="4" name="{{ $key }}">{{ old($key,$values[$key]??'') }}</textarea>
                    @if($key==='typing_titles')<small class="text-muted">Separate titles with a vertical bar (|).</small>@endif
                @elseif($kind==='select')
                    <select class="form-control" name="{{ $key }}">
                        @if($key==='theme')
                            @foreach(['dark'=>'Dark','light'=>'Light','auto'=>'Auto'] as $value=>$label)
                                <option value="{{ $value }}" @selected(old($key,$values[$key]??'dark')==$value)>{{ $label }}</option>
                            @endforeach
                        @else
                            @foreach(['particles'=>'Animated particles','waves'=>'Animated waves','grid'=>'Geometric grid','gradient'=>'Animated gradient','image'=>'Static image','video'=>'Background video','static'=>'Static background'] as $value=>$label)
                                <option value="{{ $value }}" @selected(old($key,$values[$key]??'particles')==$value)>{{ $label }}</option>
                            @endforeach
                        @endif
                    </select>
                @elseif($kind==='checkbox')
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="{{ $key }}" value="1" id="{{ $key }}" @checked(old($key,$values[$key]??'1')==='1')>
                        <label class="form-check-label" for="{{ $key }}">Enable {{ str_replace('_',' ', $key) }}</label>
                    </div>
                @elseif($kind==='image'||$kind==='file'||$kind==='video')
                    @if(!empty($values[$key]))
                        <div class="mb-2"><a target="_blank" rel="noopener" href="{{ asset('storage/'.$values[$key]) }}">Current file</a></div>
                    @endif
                    @if($kind==='image')
                        <img class="upload-preview img-thumbnail mb-2" style="max-height:140px" src="{{ !empty($values[$key]) ? asset('storage/'.$values[$key]) : '' }}" alt="Image preview">
                    @endif
                    <input type="file" class="form-control" onchange="if(this.files[0] && this.files[0].type.startsWith('image/')){let preview=this.parentElement.querySelector('img.upload-preview');if(preview)preview.src=URL.createObjectURL(this.files[0]);}" name="{{ $key }}" accept="{{ $kind==='image'?'image/png,image/jpeg,image/webp':($kind==='video'?'video/mp4,video/webm':'.pdf') }}">
                    @if(!empty($values[$key]))
                        <label class="d-block mt-2"><input type="checkbox" name="remove_{{ $key }}" value="1"> Remove current file</label>
                    @endif
                @else
                    <input type="{{ $kind }}" class="form-control" name="{{ $key }}" value="{{ old($key,$values[$key]??'') }}">
                @endif
            </div>
        @endforeach
        <button class="btn btn-primary">Save changes</button>
    </form>
</div>
@endsection
