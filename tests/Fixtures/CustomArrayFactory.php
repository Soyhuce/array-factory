<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory\Tests\Fixtures;

use Soyhuce\ArrayFactory\ArrayFactory;

/**
 * @extends ArrayFactory<CustomData, CustomCollection>
 */
class CustomArrayFactory extends ArrayFactory
{
    public string $data = CustomData::class;

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
