<?php

declare(strict_types=1);

namespace ADS\Util\MetadataExtractor;

trait MetadataExtractorAware
{
    private MetadataExtractor $metadataExtractor;

    public function __construct()
    {
        $this->metadataExtractor = new MetadataExtractor(
            new AttributeExtractor(),
            new ClassExtractor(),
            new InstanceExtractor(),
        );
    }
}
