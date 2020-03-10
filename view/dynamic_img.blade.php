@php
    $placeholder_class = !empty($placeholder_class) ? $placeholder_class : 'dimension-1_1';
    $img =  !empty($img) ? $img : empty_image();
    $src = config('tatler.img_preload');
    $defer_img_class = 'defer-load';
    $defer_attr = 'data-defer-src="'.$img->article.'"';
    $focus_point = focus_point($img, $placeholder_class, 'article');
    $imgPlaceholder_attr = $focus_point->attr;
    if (!empty($img->bg_color_hsl)) {
        $imgPlaceholder_attr .= ' style="background-color:hsl('.
            $img->bg_color_hsl[0].','.
            $img->bg_color_hsl[1].'%,'.
            $img->bg_color_hsl[2].
        '%)"';
    }

    $img_class='fit_height';
    if (!empty($img->height) && !empty($img->width)) {
        $img_ratio = $img->height/$img->width;
        if ($img_ratio>1) {
            $img_class='fit_width';
        }
    }
@endphp
<figure>
    <div class="img_placeholder {{$defer_img_class}} {{$placeholder_class}} {{$focus_point->class}}" {!!$imgPlaceholder_attr!!}>
        <img src="{{$src}}" class="{{$img_class}}" alt="{{$feed->title}}" {!!@$focus_point->img_attr!!}
            style="{{@$focus_point->img_style}}" {!!$defer_attr!!}
        >
    </div>
</figure>
