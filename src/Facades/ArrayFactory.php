<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Soyhuce\ArrayFactory\ArrayFactory
 */
class ArrayFactory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Soyhuce\ArrayFactory\ArrayFactory::class;
    }
}
