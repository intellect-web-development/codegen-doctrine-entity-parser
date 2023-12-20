<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\Service;

use Doctrine\Inflector\Inflector as DoctrineInflector;
use Doctrine\Inflector\Rules\English\InflectorFactory;

/**
 * @psalm-suppress RedundantPropertyInitializationCheck
 */
class Inflector
{
    public static function camelize(string $word): string
    {
        $inflector = (new InflectorFactory())->build();

        return $inflector->camelize($word);
    }

}
