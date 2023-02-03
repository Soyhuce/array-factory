# This is array-factory package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/soyhuce/array-factory.svg?style=flat-square)](https://packagist.org/packages/soyhuce/array-factory)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/soyhuce/array-factory/run-tests?label=tests)](https://github.com/soyhuce/array-factory/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/soyhuce/array-factory/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/soyhuce/array-factory/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![GitHub PHPStan Action Status](https://img.shields.io/github/workflow/status/soyhuce/array-factory/PHPStan?label=phpstan)](https://github.com/soyhuce/array-factory/actions?query=workflow%3APHPStan+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/soyhuce/array-factory.svg?style=flat-square)](https://packagist.org/packages/soyhuce/array-factory)

When testing your application, you may need to write the same data structures (lists, collections, Etc.) to populate models or something else. These often duplicated in multiple test files.

A bit like Laravel's Factory classes, ArrayFactory permits to generate and reuse custom data structures and usable among your tests.

## Installation

You can install the package via composer:

```bash
composer require --dev soyhuce/array-factory
```

## Usage

#### Create a factory
```php
use Soyhuce\ArrayFactory;

$factory = new ArrayFactory(['foo' => 'bar']); // from an array
$factory = ArrayFactory::new(['foo' => 'bar']); // from static method new
$factory = ArrayFactory::new(fn () => ['foo' => 'bar']); // with a callable
```

#### Use it
```php
$factory->createOne();
// ['foo' => 'bar']
$factory->createMany(['foo' => 'qux'], ['foo' => 'bar'])
// [['foo' => 'qux'], ['foo' => 'bar']]
$factory->count(2)->asCollection();
// Illuminate\Support\Collection([ ['foo' => 'bar'], ['foo' => 'bar'] ])
```

#### Add states
You may need to define states and use it in a specific context.

```php
$factory = new ArrayFactory(
    definition: ['foo' => 'bar'],
    states: [
        'qux' => ['foo' => 'qux'],
    ]
);

$factory->createOne();
// ['foo' => 'bar']
$factory->state('qux')->createOne();
// ['foo' => 'qux']
```

#### Add custom Collection
You can transform the array into a custom Collection.

```php
$factory = new ArrayFactory(
    collection: FooCollection::class,
);

$foo = FooFactory::new()->asCollection();
```

You can also transform the array into a custom Data object by defining the Spatie\LaravelData\Data class.

#### Add custom \Spatie\LaravelData\Data

```php
$factory = new ArrayFactory(
    data: FooData::class,
);

$factory = FooFactory::new()->asData();
```

It allows get many results as collection of datas
```php
$datas = $factory->manyAsDataCollection(['id' => 2], ['id' => 3]);
// Collection([FooData(id: 2), FooData(id: 3)])
```

### Create an extended class

```php
class FooFactory extends ArrayFactory
{    
    protected string $collection = FooCollection::class;
    
    protected string $data = FooData::class;
    
    public function definition(): array
    {
        return [
            'id' => 1,
            'activated' => true,
            'value' => 0,
        ];
    }

    public function id(int $id): static
    {
        return $this->state(['id' => $id]);
    }

    public function activated(bool $activated = true): static
    {
        return $this->state(['activated' => $activated]);
    }
}
```
Then, you can simply use it in your tests. 
```php
    $foo1 = FooFactory::new()->createOne(); 
    // ['id' => 1, 'activated' => true, 'value' => 0]
    $foo1 = FooFactory::new()->createOne(['value' => 1]]);
     // ['id' => 1, 'activated' => true, 'value' => 1]
    $foo2 = FooFactory::new()->id(2)->createOne();
     // ['id' => 2, 'activated' => true, 'value' => 0]
    $foo3 = FooFactory::new()->activated(false)->createOne();
     // ['id' => 1, 'activated' => false, 'value' => 0]
```

## Testing
Runs tests.
```bash
composer test
```
Runs all pre-commit checks.
```bash
composer all
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Bastien Philippe](https://github.com/bastien-phi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
