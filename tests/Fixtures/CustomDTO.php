<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory\Tests\Fixtures;

use Spatie\DataTransferObject\DataTransferObject;

class CustomDTO extends DataTransferObject
{
    public int $id;

    public string $email;
}
