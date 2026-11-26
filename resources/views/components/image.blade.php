<picture>
    @if( $image->extension() == 'svg' || $image->extension() == 'gif')
        <img {{ $attributes->merge([
            'src' => $image->url(),
            'alt' => $image->get('alt', ''),
            'width' => $image->width(),
            'height' => $image->height(),
        ]) }} />
    @else
        @foreach($pictures as $item)
            @foreach($item['picture']->sources as $format => $srcset)
                <source
                    type="{{ $format }}"
                    @if($item['media']) media="{{ $item['media'] }}" @endif
                    srcset="{{ $srcset }}"
                    sizes="{{ $sizes }}"
                    width="{{ $item['picture']->width }}"
                    height="{{ $item['picture']->height }}"
                >
            @endforeach
        @endforeach

        <img {{ $attributes->merge([
            'src' => $picture->src,
            'alt' => $image->get('alt', ''),
            'width' => $picture->width,
            'height' => $picture->height,
            'loading' => 'lazy',
        ]) }} />
    @endif
</picture>
