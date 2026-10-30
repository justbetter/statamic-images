<picture>
    @if( $image->extension() == 'svg' || $image->extension() == 'gif')
        <img {{ $attributes->merge([
            'src' => $image->url(),
            'alt' => $image->get('alt', ''),
            'width' => $image->width(),
            'height' => $image->height(),
        ]) }} />
    @else
        @foreach($picture->sources as $format => $srcset)
            <source
                type="{{ $format }}"
                srcset="{{ $srcset }}"
                sizes="{{ $sizes }}"
            >
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
