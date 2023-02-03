<?php declare(strict_types=1);

use Soyhuce\ArrayFactory\ArrayFactory;
use Soyhuce\ArrayFactory\Tests\Fixtures\CustomArrayFactory;
use Soyhuce\ArrayFactory\Tests\Fixtures\CustomCollection;
use Soyhuce\ArrayFactory\Tests\Fixtures\CustomData;
use function PHPStan\Testing\assertType;

$factory = new ArrayFactory(['foo' => 'bar']);

assertType(
    'Soyhuce\\ArrayFactory\\ArrayFactory<Spatie\\LaravelData\\Data, Illuminate\\Support\\Collection>',
    $factory
);
assertType(
    'array<string, mixed>',
    $factory->createOne()
);
assertType(
    'array<int, array<string, mixed>>',
    $factory->create()
);
assertType(
    'array<int, array<string, mixed>>',
    $factory->createMany()
);
assertType(
    'Spatie\\LaravelData\\Data',
    $factory->asData()
);
assertType(
    'array<int, Spatie\\LaravelData\\Data>',
    $factory->asDatas()
);
assertType(
    'array<int, Spatie\\LaravelData\\Data>',
    $factory->manyAsDatas()
);
assertType(
    'Illuminate\\Support\\Collection<int, array<string, mixed>>',
    $factory->asCollection()
);
assertType(
    'Illuminate\\Support\\Collection<int, array<string, mixed>>',
    $factory->manyAsCollection()
);
assertType(
    'Spatie\\LaravelData\\DataCollection<int, Spatie\\LaravelData\\Data>',
    $factory->asDataCollection()
);
assertType(
    'Spatie\\LaravelData\\DataCollection<int, Spatie\\LaravelData\\Data>',
    $factory->manyAsDataCollection()
);

$newFactory = ArrayFactory::new(['foo' => 'bar']);

assertType(
    'Soyhuce\\ArrayFactory\\ArrayFactory',
    $newFactory
);
assertType(
    'array<int, Spatie\\LaravelData\\Data>',
    $newFactory->asDatas()
);
assertType(
    'array<int, Spatie\\LaravelData\\Data>',
    $newFactory->manyAsDatas()
);
assertType(
    'Illuminate\\Support\\Collection<int, array<string, mixed>>',
    $newFactory->asCollection()
);
assertType(
    'Illuminate\\Support\\Collection<int, array<string, mixed>>',
    $newFactory->manyAsCollection()
);
assertType(
    'Spatie\\LaravelData\\DataCollection<int, Spatie\\LaravelData\\Data>',
    $newFactory->asDataCollection()
);
assertType(
    'Spatie\\LaravelData\\DataCollection<int, Spatie\\LaravelData\\Data>',
    $newFactory->manyAsDataCollection()
);

$timesFactory = ArrayFactory::times(2);
assertType(
    'Soyhuce\\ArrayFactory\\ArrayFactory',
    ArrayFactory::times(2)
);

$factoryWithCustoms = new ArrayFactory(['foo' => 'bar'], data: CustomData::class, collection: CustomCollection::class);

assertType(
    'Soyhuce\\ArrayFactory\\ArrayFactory<Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection>',
    $factoryWithCustoms
);
assertType(
    'array<string, mixed>',
    $factoryWithCustoms->createOne()
);
assertType(
    'array<int, array<string, mixed>>',
    $factoryWithCustoms->create()
);
assertType(
    'array<int, array<string, mixed>>',
    $factoryWithCustoms->createMany()
);
assertType(
    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData',
    $factoryWithCustoms->asData()
);
assertType(
    'array<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData>',
    $factoryWithCustoms->asDatas()
);
assertType(
    'array<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData>',
    $factoryWithCustoms->manyAsDatas()
);
// assertType(
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, array<string, mixed>>',
//    $factoryWithCustoms->asCollection()
// );
// assertType(
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, array<string, mixed>>',
//    $factoryWithCustoms->manyAsCollection()
// );
// assertType(
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData>',
//    $factoryWithCustoms->asDataCollection()
// );
// assertType(
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData>',
//    $factoryWithCustoms->manyAsDataCollection()
// );

$customFactory = new CustomArrayFactory();

assertType(
    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomArrayFactory',
    $customFactory
);
assertType(
    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomArrayFactory',
    $customFactory->withoutId()
);
assertType(
    'array<string, mixed>',
    $customFactory->createOne()
);
assertType(
    'array<int, array<string, mixed>>',
    $customFactory->create()
);
assertType(
    'array<int, array<string, mixed>>',
    $customFactory->createMany()
);
assertType(
    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData',
    $customFactory->asData()
);
assertType(
    'array<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData>',
    $customFactory->asDatas()
);
assertType(
    'array<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData>',
    $customFactory->manyAsDatas()
);
// assertType(
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, array<string, mixed>>',
//    $customFactory->asCollection()
// );
// assertType(
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, array<string, mixed>>',
//    $customFactory->manyAsCollection()
// );
// assertType(
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData>',
//    $customFactory->asDataCollection()
// );
// assertType(
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomData>',
//    $customFactory->manyAsDataCollection()
// );

assertType(
    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomArrayFactory',
    CustomArrayFactory::new()
);

assertType(
    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomArrayFactory',
    CustomArrayFactory::times(2)
);
