@php
    $fKey = !empty($fKey) ? $fKey : 'feed';

    //bootstrap grid system based multi dimension support --
    //e.g.  $img_dimension = '1_1 md-3_2';
    $img_dimension = explode(' ', @$img_dimension);
    if (!in_array(@$img_dimension[0], ['1_1','3_2','2_3','16_9'])) {
        $img_dimension[0] = '3_2';
    }
    $placeholder_class = 'dimension-'.$img_dimension[0];

    switch ($img_dimension[0]) {
        case '1_1':
            $img_scales = 'square';
            break;
        case '2_3':
            $img_scales = 'portrait';
            break;
        case '16_9':
            $img_scales = 'landscape';
            break;
        case '3_2':
        default:
            $img_scales = 'feed';
            break;
    }
    $ref_ratio = (int)explode('_',$img_dimension[0])[1] / (int)explode('_',$img_dimension[0])[0];

    if (count($img_dimension) > 1) {
        foreach ($img_dimension as $d_key => $d_value) {
            if ($d_key != 0) {
                $placeholder_class .= ' dimension-'.$d_value;
            }
        }
    }
@endphp
<figure>
    <i class="story-icon" v-bind:class="{{$fKey}}.story_icon" v-if="{{$fKey}}.story_icon" aria-hidden="true"></i>
    <div class="img_placeholder defer-load {{$placeholder_class}} focuspoint"
        :data-focus-x="{{$fKey}}.cover_image.focus_x" :data-focus-y="{{$fKey}}.cover_image.focus_y"
        :data-image-w="{{$fKey}}.cover_image.width" :data-image-h="{{$fKey}}.cover_image.height"
        :style="[typeof {{$fKey}}.cover_image.bg_color_hsl !== 'undefined' ? {'background-color': 'hsl('+     {{$fKey}}.cover_image.bg_color_hsl[0]+','+   {{$fKey}}.cover_image.bg_color_hsl[1]+'%,'+{{$fKey}}.cover_image.bg_color_hsl[2]+'%)'} : {}]"
    >
        <img src="{{config('tatler.img_preload')}}"
            {{-- :data-defer-src="feed.cover_image.article" --}}
            :data-defer-src="{{$fKey}}.cover_image.cover"
            :class="({{$fKey}}.cover_image.height / {{$fKey}}.cover_image.width) > {{$ref_ratio}} ? 'fit_width' : 'fit_height'"
            :style="'transform:scale('+{{$fKey}}.cover_image.scales.{{$img_scales}}+') translateZ(0)'"
            :data-scale-feed="{{$fKey}}.cover_image.scales.feed"
            :data-scale-square="{{$fKey}}.cover_image.scales.square"
            :data-scale-landscape="{{$fKey}}.cover_image.scales.landscape"
            :data-scale-portrait="{{$fKey}}.cover_image.scales.portrait"
            :alt="({{$fKey}}.cover_image.alt ? {{$fKey}}.cover_image.alt : ({{$fKey}}.name ? {{$fKey}}.name : {{$fKey}}.title)).replace(/(<([^>]+)>)/ig,'')"
        >
    </div>
</figure>
