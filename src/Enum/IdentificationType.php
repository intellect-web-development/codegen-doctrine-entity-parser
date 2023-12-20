<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\Enum;

enum IdentificationType: string
{
    case Sequence = 'sequence';
    case Uuid = 'uuid';
}
