<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public const string CLI_API = 'cli';
    public const string ENV_PROD = 'prod';
    public const string ENV_DEV = 'dev';
    public const string ENV_TEST = 'test';
}
