<?php

declare(strict_types=1);

namespace JustBetter\StatamicImages\Contracts;

use JustBetter\StatamicImages\Data\Config;
use JustBetter\StatamicImages\Data\Picture;
use Statamic\Assets\Asset;

interface BuildsPicture
{
    public function build(Asset $image, Config $config): Picture;
}
