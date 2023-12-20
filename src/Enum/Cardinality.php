<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\Enum;

enum Cardinality: string
{
    case Many = 'many';
    case One = 'one';
}
