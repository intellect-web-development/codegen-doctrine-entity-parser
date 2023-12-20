<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\Enum;

enum TableAttributeType: string
{
    case BigInteger = 'big_integer';
    case Boolean = 'boolean';
    case Date = 'date';
    case Datetime = 'datetime';
    case Float = 'float';
    case Integer = 'integer';
    case Json = 'json';
    case String = 'string';
    case Text = 'text';
    case Undefined = 'undefined';
    case UnsignedBigInteger = 'unsigned_big_integer';
    case UnsignedInteger = 'unsigned_integer';
    case Uuid = 'uuid';
}
