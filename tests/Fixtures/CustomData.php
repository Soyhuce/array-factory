<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory\Tests\Fixtures;

use Spatie\LaravelData\Data;

class CustomData extends Data
{
    public function __construct(
        public ?int $id,
        public ?string $email,
    ) {
    }
}
