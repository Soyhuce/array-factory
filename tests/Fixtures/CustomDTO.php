<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory\Tests\Fixtures;

class CustomDTO extends Spatie\DataTransferObject\DataTransferObject
{
    public int $id;

    public string $email;
}
