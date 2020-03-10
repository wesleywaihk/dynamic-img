{{-- server side --}}
@include('elements.dynamic_img',[
  'img'=> $feed->hero_image,
  'placeholder_class' => 'dimension-1_1'
])


{{-- vue --}}
@include('bumblebee.elements.vue_dynamic_img',[
  'fKey'=>'episode',
  'img_dimension'=>'1_1'
])
