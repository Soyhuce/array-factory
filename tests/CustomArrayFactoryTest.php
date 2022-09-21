<?php declare(strict_types=1);

use Soyhuce\ArrayFactory\Tests\Fixtures\CustomArrayFactory;
use Soyhuce\ArrayFactory\Tests\Fixtures\CustomCollection;
use Soyhuce\ArrayFactory\Tests\Fixtures\CustomDTO;

it('can create an array with custom factory', function (): void {
    $factory = new CustomArrayFactory();

    expect($factory->createOne())->toBe([
        'id' => 1,
        'email' => 'email@email.com',
    ]);
});

it('instantiate a custom array factory with new', function (): void {
    $factory = CustomArrayFactory::new(['id' => 2]);

    expect($factory->createOne())->toBe([
        'id' => 2,
        'email' => 'email@email.com',
    ]);
});

it('can create a dto with custom factory', function (): void {
    $factory = new CustomArrayFactory();

    expect($factory->asDto())
        ->toBeInstanceOf(CustomDTO::class)
        ->id->toBe(1)
        ->email->toBe('email@email.com');
});

it('can call a custom state', function (): void {
    $factory = new CustomArrayFactory();

    expect($factory->withoutId()->createOne())->toBe(['email' => 'email@email.com']);
});

it('can create a collection with custom factory', function (): void {
    $factory = new CustomArrayFactory();

    expect($factory->count(2)->asCollection())
        ->toBeInstanceOf(CustomCollection::class)
        ->all()->toBe([
            ['id' => 1, 'email' => 'email@email.com'],
            ['id' => 1, 'email' => 'email@email.com'],
        ]);
});
