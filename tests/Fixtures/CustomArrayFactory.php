<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory\Tests\Fixtures;

use Soyhuce\ArrayFactory\ArrayFactory;

/**
 * @extends ArrayFactory<CustomDTO, CustomCollection>
 */
class CustomArrayFactory extends ArrayFactory
{
    public string $dto = CustomDTO::class;

    public string $collection = CustomCollection::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => 1,
            'email' => 'email@email.com',
        ];
    }

    public function withoutId(): static
    {
        return $this->without('id');
    }
}
