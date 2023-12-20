<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\Enum;

enum Orientation: string
{
    case Bidirectional = 'bidirectional';
    case Unidirectional = 'unidirectional';
}
