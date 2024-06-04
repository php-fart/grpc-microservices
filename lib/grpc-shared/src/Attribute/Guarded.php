<?php

declare(strict_types=1);

namespace Internal\Shared\gRPC\Attribute;

use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

#[Attribute(Attribute::TARGET_METHOD), NamedArgumentConstructor]
final readonly class Guarded
{
    public function __construct() {}
}
