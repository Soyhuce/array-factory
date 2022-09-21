<?php declare(strict_types=1);

use Soyhuce\ArrayFactory\ArrayFactory;
use Soyhuce\ArrayFactory\Tests\Fixtures\CustomArrayFactory;
use Soyhuce\ArrayFactory\Tests\Fixtures\CustomCollection;
use Soyhuce\ArrayFactory\Tests\Fixtures\CustomDTO;
use function PHPStan\Testing\assertType;

$factory = new ArrayFactory(['foo' => 'bar']);

assertType(
    'Soyhuce\\ArrayFactory\\ArrayFactory<Spatie\\DataTransferObject\\DataTransferObject, Illuminate\\Support\\Collection>',
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
    'Spatie\\DataTransferObject\\DataTransferObject',
    $factory->asDto()
);
assertType(
    'array<int, Spatie\\DataTransferObject\\DataTransferObject>',
    $factory->asDtos()
);
assertType(
    'array<int, Spatie\\DataTransferObject\\DataTransferObject>',
    $factory->manyAsDtos()
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
    'Illuminate\\Support\\Collection<int, Spatie\\DataTransferObject\\DataTransferObject>',
    $factory->asDtoCollection()
);
assertType(
    'Illuminate\\Support\\Collection<int, Spatie\\DataTransferObject\\DataTransferObject>',
    $factory->manyAsDtoCollection()
);

$newFactory = ArrayFactory::new(['foo' => 'bar']);

assertType(
    'Soyhuce\\ArrayFactory\\ArrayFactory',
    $newFactory
);
assertType(
    'array<int, Spatie\\DataTransferObject\\DataTransferObject>',
    $newFactory->asDtos()
);
assertType(
    'array<int, Spatie\\DataTransferObject\\DataTransferObject>',
    $newFactory->manyAsDtos()
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
    'Illuminate\\Support\\Collection<int, Spatie\\DataTransferObject\\DataTransferObject>',
    $newFactory->asDtoCollection()
);
assertType(
    'Illuminate\\Support\\Collection<int, Spatie\\DataTransferObject\\DataTransferObject>',
    $newFactory->manyAsDtoCollection()
);

$timesFactory = ArrayFactory::times(2);
assertType(
    'Soyhuce\\ArrayFactory\\ArrayFactory',
    ArrayFactory::times(2)
);

$factoryWithCustoms = new ArrayFactory(['foo' => 'bar'], dto: CustomDTO::class, collection: CustomCollection::class);

assertType(
    'Soyhuce\\ArrayFactory\\ArrayFactory<Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection>',
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
    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO',
    $factoryWithCustoms->asDto()
);
assertType(
    'array<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO>',
    $factoryWithCustoms->asDtos()
);
assertType(
    'array<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO>',
    $factoryWithCustoms->manyAsDtos()
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
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO>',
//    $factoryWithCustoms->asDtoCollection()
// );
// assertType(
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO>',
//    $factoryWithCustoms->manyAsDtoCollection()
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
    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO',
    $customFactory->asDto()
);
assertType(
    'array<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO>',
    $customFactory->asDtos()
);
assertType(
    'array<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO>',
    $customFactory->manyAsDtos()
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
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO>',
//    $customFactory->asDtoCollection()
// );
// assertType(
//    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomCollection<int, Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomDTO>',
//    $customFactory->manyAsDtoCollection()
// );

assertType(
    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomArrayFactory',
    CustomArrayFactory::new()
);

assertType(
    'Soyhuce\\ArrayFactory\\Tests\\Fixtures\\CustomArrayFactory',
    CustomArrayFactory::times(2)
);
