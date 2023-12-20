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
    protected static DoctrineInflector $inflector;

    public static function init(): void
    {
        if (!isset(static::$inflector)) {
            static::$inflector = (new InflectorFactory())->build();
        }
    }

    public static function urlize(string $word): string
    {
        self::init();

        return self::$inflector->urlize($word);
    }

    public static function camelize(string $word): string
    {
        self::init();

        return self::$inflector->camelize($word);
    }

    public static function classify(string $word): string
    {
        self::init();

        return self::$inflector->classify($word);
    }

    public static function tableize(string $word): string
    {
        self::init();

        return mb_strtolower((string) preg_replace('/\s+/', '_', self::$inflector->tableize($word)));
    }

    public static function kebab(string $word): string
    {
        self::init();

        return (string) str_replace('_', '-', mb_strtolower((string) preg_replace('/\s+/', '-', self::$inflector->tableize($word))));
    }

    public static function capitalize(string $word): string
    {
        self::init();

        return self::$inflector->capitalize($word);
    }

    public static function constantize(string $word): string
    {
        self::init();

        return mb_strtoupper(
            self::$inflector->tableize($word)
        );
    }

    public static function pluralize(string $word): string
    {
        self::init();

        return self::$inflector->pluralize($word);
    }

    public static function singularize(string $word): string
    {
        self::init();

        return self::$inflector->singularize($word);
    }
}
