<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages\View\Components;

use Illuminate\View\Component;
use JustBetter\StatamicImages\Contracts\BuildsPicture;
use JustBetter\StatamicImages\Data\Config;
use Statamic\Assets\Asset;

class Image extends Component
{
    /** @param array<int, array<string, int>> $dimensions */
    public function __construct(
        protected BuildsPicture $buildsPicture,
        protected Asset $image,
        protected array $dimensions = [['w' => 960]],
        protected string $sizes = 'auto, 100vw',
        protected int $quality = 90
    ) {}

    public function render(): mixed
    {
        $config = Config::new([
            'dimensions' => $this->dimensions,
            'params' => [
                'quality' => $this->quality,
            ],
        ]);

        /** @var view-string $view */
        $view = 'justbetter::components.image';
        return view($view, [
            'image' => $this->image,
            'sizes' => $this->sizes,
            'picture' => $this->buildsPicture->build($this->image, $config),
        ]);
    }
}
