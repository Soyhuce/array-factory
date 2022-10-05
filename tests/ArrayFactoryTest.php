<?php declare(strict_types=1);

use Illuminate\Support\Collection;
use Soyhuce\ArrayFactory\ArrayFactory;
use Soyhuce\ArrayFactory\Tests\Fixtures\CustomCollection;
use Soyhuce\ArrayFactory\Tests\Fixtures\CustomDTO;

it('can create an array', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->createOne())->toBe(['foo' => 'bar']);
});

it('instantiate an array factory with new', function (): void {
    $factory = ArrayFactory::new(['foo' => 'bar']);

    expect($factory->createOne())->toBe(['foo' => 'bar']);
});

it('can create an array with callable value', function (): void {
    $factory = new ArrayFactory(['foo' => fn () => 'bar']);

    expect($factory->createOne())->toBe(['foo' => 'bar']);
});

it('can create an array calling the factory', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory())->toBe(['foo' => 'bar']);
});

it('resolves inner factories', function (): void {
    $factory = new ArrayFactory([
        'foo' => new ArrayFactory(['bar' => 'baz']),
    ]);

    expect($factory->createOne())->toBe(['foo' => ['bar' => 'baz']]);
});

it('allows to override a value', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->createOne(['foo' => 'qux']))->toBe(['foo' => 'qux']);
});

it('allows to override a value with callable', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->createOne(['foo' => fn () => 'qux']))->toBe(['foo' => 'qux']);
});

it('allows to override a value with a state', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->state(['foo' => 'qux'])->createOne())->toBe(['foo' => 'qux']);
});

it('state does not changes original factory', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->state(['foo' => 'qux'])->createOne())->toBe(['foo' => 'qux']);
    expect($factory->createOne())->toBe(['foo' => 'bar']);
});

it('allows to define custom state', function (): void {
    $factory = new ArrayFactory(
        definition: ['foo' => 'bar'],
        states: [
            'qux' => ['foo' => 'qux'],
        ]
    );

    expect($factory->state('qux')->createOne())->toBe(['foo' => 'qux']);
});

it('allows to define custom state with callable', function (): void {
    $factory = new ArrayFactory(
        definition: ['foo' => 'bar'],
        states: [
            'qux' => fn () => ['foo' => 'qux'],
        ]
    );

    expect($factory->state('qux')->createOne())->toBe(['foo' => 'qux']);
});

it('allows to remove some fields', function (): void {
    $factory = new ArrayFactory(
        definition: ['foo' => 'bar', 'baz' => 'qux'],
    );

    expect($factory->without('foo')->createOne())->toBe(['baz' => 'qux']);
});

it('allows to remove multiple fields', function (): void {
    $factory = new ArrayFactory(
        definition: ['foo' => 'bar', 'baz' => 'qux'],
    );

    expect($factory->without('foo', 'baz')->createOne())->toBe([]);
});

it('allows to remove and add fields', function (): void {
    $factory = new ArrayFactory(
        definition: ['foo' => 'bar'],
    );

    expect($factory->without('foo')->createOne(['foo' => 'plop']))->toBe(['foo' => 'plop']);
});

it('allows to add and remove multiple fields', function (): void {
    $factory = new ArrayFactory(
        definition: ['foo' => 'bar'],
    );

    expect($factory->state(['rux' => 'tax'])->without('rux')->createOne())->toBe(['foo' => 'bar']);
});

it('allows to nullify some fields', function (): void {
    $factory = new ArrayFactory(
        definition: ['foo' => 'bar', 'baz' => 'qux'],
    );

    expect($factory->nullify('foo')->createOne())->toBe(['foo' => null, 'baz' => 'qux']);
});

it('allows to nullify multiple fields', function (): void {
    $factory = new ArrayFactory(
        definition: ['foo' => 'bar', 'baz' => 'qux'],
    );

    expect($factory->nullify('foo', 'baz')->createOne())->toBe(['foo' => null, 'baz' => null]);
});

it('allows to create multiple items at a time', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->count(2)->create())->toBe([
        ['foo' => 'bar'],
        ['foo' => 'bar'],
    ]);
});

it('allows to create multiple items at a time with static times call', function (): void {
    expect(ArrayFactory::times(2)->create(['foo' => 'bar']))->toBe([
        ['foo' => 'bar'],
        ['foo' => 'bar'],
    ]);
});

it('allows to create multiple items at a time with override', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->count(2)->create(['foo' => 'qux']))->toBe([
        ['foo' => 'qux'],
        ['foo' => 'qux'],
    ]);
});

it('allows to create multiple items at a time with sequence', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->count(2)->sequence(['foo' => 'qux'], ['foo' => 'bar'])->create())->toBe([
        ['foo' => 'qux'],
        ['foo' => 'bar'],
    ]);
});

it('allows to create multiple items at a time with sequence with more item than the sequence', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->count(3)->sequence(['foo' => 'qux'], ['foo' => 'bar'])->create())->toBe([
        ['foo' => 'qux'],
        ['foo' => 'bar'],
        ['foo' => 'qux'],
    ]);
});

it('allows to create multiple items at a time with sequence with less items than the sequence', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->count(2)->sequence(['foo' => 'qux'], ['foo' => 'bar'], ['foo' => 'baz'])->create())->toBe([
        ['foo' => 'qux'],
        ['foo' => 'bar'],
    ]);
});

it('allows to create all items of the sequence', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->forEachSequence(['foo' => 'qux'], ['foo' => 'bar'])->create())->toBe([
        ['foo' => 'qux'],
        ['foo' => 'bar'],
    ]);
});

it('allows to create many items', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->createMany(['foo' => 'qux'], ['foo' => 'bar']))->toBe([
        ['foo' => 'qux'],
        ['foo' => 'bar'],
    ]);
});

it('allows to create with cross joined sequence', function (): void {
    $factory = new ArrayFactory();

    expect(
        $factory
            ->crossJoinSequence(
                [['foo' => 'qux'], ['foo' => 'bar']],
                [['toto' => 'tata'], ['toto' => 'titi']],
            )
            ->count(4)
            ->create()
    )->toBe([
        ['foo' => 'qux', 'toto' => 'tata'],
        ['foo' => 'qux', 'toto' => 'titi'],
        ['foo' => 'bar', 'toto' => 'tata'],
        ['foo' => 'bar', 'toto' => 'titi'],
    ]);
});

it('allows get the result as dto', function (): void {
    $factory = new ArrayFactory(
        definition: ['id' => 1, 'email' => 'email@email.com'],
        dto: CustomDTO::class,
    );

    expect($factory->asDto())
        ->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(1)
        ->email->toBe('email@email.com');
});

it('allows get the result as dto with override', function (): void {
    $factory = new ArrayFactory(
        definition: ['id' => 1, 'email' => 'email@email.com'],
        dto: CustomDTO::class,
    );

    expect($factory->asDto(['id' => 2]))
        ->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(2)
        ->email->toBe('email@email.com');
});

it('allows get the result as array of dtos', function (): void {
    $factory = new ArrayFactory(
        definition: ['id' => 1, 'email' => 'email@email.com'],
        dto: CustomDTO::class,
    );

    $dtos = $factory->count(2)->asDtos();
    expect($dtos)
        ->toBeArray()
        ->toHaveCount(2);

    expect($dtos[0])->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(1)
        ->email->toBe('email@email.com');

    expect($dtos[1])->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(1)
        ->email->toBe('email@email.com');
});

it('allows get the result as array of dtos with override', function (): void {
    $factory = new ArrayFactory(
        definition: ['id' => 1, 'email' => 'email@email.com'],
        dto: CustomDTO::class,
    );

    $dtos = $factory->asDtos(['id' => 2]);
    expect($dtos)
        ->toBeArray()
        ->toHaveCount(1);

    expect($dtos[0])->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(2)
        ->email->toBe('email@email.com');
});

it('allows get many results as array of dtos', function (): void {
    $factory = new ArrayFactory(
        definition: ['id' => 1, 'email' => 'email@email.com'],
        dto: CustomDTO::class,
    );

    $dtos = $factory->manyAsDtos(['id' => 2], ['id' => 3]);
    expect($dtos)
        ->toBeArray()
        ->toHaveCount(2);

    expect($dtos[0])->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(2)
        ->email->toBe('email@email.com');

    expect($dtos[1])->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(3)
        ->email->toBe('email@email.com');
});

it('allows to create multiple items as a collection', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->count(2)->asCollection())
        ->toBeInstanceOf(Collection::class)
        ->all()->toBe([
            ['foo' => 'bar'],
            ['foo' => 'bar'],
        ]);
});

it('allows to create multiple items as a collection with override', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->count(2)->asCollection(['foo' => 'qux']))
        ->toBeInstanceOf(Collection::class)
        ->all()->toBe([
            ['foo' => 'qux'],
            ['foo' => 'qux'],
        ]);
});

it('allows to create many items as a collection', function (): void {
    $factory = new ArrayFactory(['foo' => 'bar']);

    expect($factory->manyAsCollection(['foo' => 'qux'], ['foo' => 'baz']))
        ->toBeInstanceOf(Collection::class)
        ->all()->toBe([
            ['foo' => 'qux'],
            ['foo' => 'baz'],
        ]);
});

it('allows to create multiple items as a custom collection', function (): void {
    $factory = new ArrayFactory(
        definition: ['foo' => 'bar'],
        collection: CustomCollection::class
    );

    expect($factory->count(2)->asCollection())
        ->toBeInstanceOf(CustomCollection::class)
        ->all()->toBe([
            ['foo' => 'bar'],
            ['foo' => 'bar'],
        ]);
});

it('allows get the result as collection of dtos', function (): void {
    $factory = new ArrayFactory(
        definition: ['id' => 1, 'email' => 'email@email.com'],
        dto: CustomDTO::class,
        collection: CustomCollection::class,
    );

    $dtos = $factory->count(2)->asDtoCollection();
    expect($dtos)
        ->toBeInstanceOf(CustomCollection::class)
        ->toHaveCount(2);

    expect($dtos->first())->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(1)
        ->email->toBe('email@email.com');

    expect($dtos->last())->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(1)
        ->email->toBe('email@email.com');
});

it('allows get the result as collection of dtos with override', function (): void {
    $factory = new ArrayFactory(
        definition: ['id' => 1, 'email' => 'email@email.com'],
        dto: CustomDTO::class,
        collection: CustomCollection::class,
    );

    $dtos = $factory->asDtoCollection(['id' => 2]);
    expect($dtos)
        ->toBeInstanceOf(CustomCollection::class)
        ->toHaveCount(1);

    expect($dtos->first())->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(2)
        ->email->toBe('email@email.com');
});

it('allows get many results as collection of dtos', function (): void {
    $factory = new ArrayFactory(
        definition: ['id' => 1, 'email' => 'email@email.com'],
        dto: CustomDTO::class,
        collection: CustomCollection::class,
    );

    $dtos = $factory->manyAsDtoCollection(['id' => 2], ['id' => 3]);
    expect($dtos)
        ->toBeInstanceOf(CustomCollection::class)
        ->toHaveCount(2);

    expect($dtos->first())->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(2)
        ->email->toBe('email@email.com');

    expect($dtos->last())->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(3)
        ->email->toBe('email@email.com');
});

it('does not call function if string is callable', function (): void {
    $factory = new ArrayFactory(['foo' => 'pi']);

    expect($factory->createOne())->toBe(['foo' => 'pi']);
});

it('does not call function if array is callable', function (): void {
    $factory = new ArrayFactory(['foo' => [ArrayFactory::class, 'new']]);

    expect($factory->createOne())->toBe(['foo' => [ArrayFactory::class, 'new']]);
});
