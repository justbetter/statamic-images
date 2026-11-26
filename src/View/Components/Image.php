<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use JustBetter\StatamicImages\Contracts\BuildsPicture;
use JustBetter\StatamicImages\Data\Config;
use JustBetter\StatamicImages\Data\Picture;
use Statamic\Assets\Asset;

class Image extends Component
{
    /** @param array<int, array<string, int|string>> $dimensions */
    public function __construct(
        protected BuildsPicture $buildsPicture,
        protected Asset $image,
        protected array $dimensions = [['w' => 960]],
        protected string $sizes = 'auto, 100vw',
        protected int $quality = 90
    ) {}

    public function render(): mixed
    {
        $dimensions = collect($this->dimensions);

        $picture = $this->picture($dimensions->whereNull('media'));

        // The browser uses the first <source> that matches, so the default must come last.
        $pictures = $dimensions
            ->whereNotNull('media')
            ->groupBy('media')
            ->map(fn (Collection $dimensions, string $media): array => [
                'media' => $media,
                'picture' => $this->picture($dimensions),
            ])
            ->values()
            ->concat([['media' => null, 'picture' => $picture]]);

        /** @var view-string $view */
        $view = 'justbetter::components.image';

        return view($view, [
            'image' => $this->image,
            'sizes' => $this->sizes,
            'pictures' => $pictures,
            'picture' => $picture,
        ]);
    }

    /** @param Collection<int, array<string, int|string>> $dimensions */
    protected function picture(Collection $dimensions): Picture
    {
        return $this->buildsPicture->build($this->image, Config::new([
            'dimensions' => $dimensions->values()->all(),
            'params' => [
                'quality' => $this->quality,
            ],
        ]));
    }
}
