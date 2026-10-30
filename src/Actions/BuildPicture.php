<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages\Actions;

use JustBetter\StatamicImages\Contracts\BuildsPicture;
use JustBetter\StatamicImages\Data\Config;
use JustBetter\StatamicImages\Data\Picture;
use Statamic\Assets\Asset;
use Statamic\Statamic;
use Statamic\Tags\FluentTag;

class BuildPicture implements BuildsPicture
{
    public function build(Asset $image, Config $config): Picture
    {
        $paths = [];
        $fallback = [];
        $maxSize = config()->integer('statamic-glide-images.max_size');

        /** @var array<string, string> $formats */
        $formats = config()->array('statamic-glide-images.formats');

        /** @var float $ratio */
        $ratio = $image->ratio();

        foreach ($formats as $extension => $format) {
            $paths[$format] = [];

            foreach ($config->dimensions as $dimension) {
                $width = $dimension['w'] ?? (int) round($dimension['h'] * $ratio);
                $height = $dimension['h'] ?? (int) round($dimension['w'] / $ratio);

                foreach ([1, 2] as $dpr) {
                    if ($width * $dpr > $maxSize || $height * $dpr > $maxSize) {
                        continue;
                    }

                    /** @var FluentTag<string, mixed> $tag */
                    $tag = Statamic::tag('glide');

                    /** @var string $url */
                    $url = $tag->params([
                        'src' => $image->url(),
                        'width' => $width,
                        'height' => $height,
                        'format' => $extension,
                        'dpr' => $dpr,
                        ...$config->params,
                    ])->fetch();

                    $paths[$format][] = $url.' '.($width * $dpr).'w';

                    if ($dpr === 1) {
                        $fallback = ['src' => $url, 'width' => $width, 'height' => $height];
                    }
                }
            }
        }

        return new Picture([
            'sources' => collect($paths)->map(fn (array $path): string => collect($path)->implode(', '))->all(),
            ...$fallback,
        ]);
    }

    public static function bind(): void
    {
        app()->singleton(BuildsPicture::class, static::class);
    }
}
