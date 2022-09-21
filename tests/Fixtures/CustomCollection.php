<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory\Tests\Fixtures;

use Illuminate\Support\Collection;

/**
 * @template TKey of array-key
 * @template TValue
 * @extends \Illuminate\Support\Collection<TKey, TValue>
 */
class CustomCollection extends Collection
{
}
