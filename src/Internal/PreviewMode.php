<?php

namespace Sandstorm\NeosApiClient\Internal;

enum PreviewMode: string
{
    case Desktop = 'desktop';
    case TabletLandscape = 'tabletLandscape';
    case TabletPortrait = 'tabletPortrait';
    case Mobile = 'mobile';

    public static function fromString(string $previewMode): PreviewMode
    {
        foreach(self::cases() as $case) {
            if($case->value === $previewMode) {
                return $case;
            }
        }
    }
}
