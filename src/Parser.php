<?php

namespace IWD\CodeGen\CodegenDoctrineEntityParser;

use Doctrine\ORM\EntityManagerInterface;

readonly class Parser
{
    public function __construct(
        private EntityManagerInterface $em,
        private CodeGenSdk $sdk,
    ) {
    }

    public function process(Command $command): void
    {
    }
}
