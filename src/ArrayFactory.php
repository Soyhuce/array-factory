<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory;

use Closure;
use Illuminate\Database\Eloquent\Factories\CrossJoinSequence;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use LogicException;
use Spatie\LaravelData\Data;
use stdClass;
use function count;
use function is_callable;
use function is_object;
use function is_string;

/**
 * @template TDataClass of \Spatie\LaravelData\Data
 * @template TCollectionClass of \Illuminate\Support\Collection
 */
class ArrayFactory
{
    /** @var class-string<TDataClass> */
    protected string $data;

    /** @var class-string<TCollectionClass> */
    protected string $collection;

    /** @var array<int, callable(array<string, mixed>, callable(array<string,mixed>): array<string,mixed>): array<string, mixed>> */
    protected array $appliedStates = [];

    protected object $placeholder;

    protected int $count = 1;

    /**
     * @param array<string, mixed> $definition
     * @param array<string, array<string, mixed>|callable(): array<string, mixed>> $states
     * @param class-string<TDataClass> $data
     * @param class-string<TCollectionClass> $collection
     */
    final public function __construct(
        public array $definition = [],
        public array $states = [],
        string $data = Data::class,
        string $collection = Collection::class,
    ) {
        $this->data ??= $data;
        $this->collection ??= $collection;
        $this->placeholder = new stdClass();

        if ($this->definition === []) {
            $this->definition = $this->definition();
        }
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function new(array $attributes = []): static
    {
        return (new static())->state($attributes);
    }

    public static function times(int $count): static
    {
        return static::new()->count($count);
    }

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $state
     * @return array<string, mixed>
     */
    public function createOne(array $state = []): array
    {
        if (count($state) > 0) {
            return $this->state($state)->createOne();
        }

        /** @var Collection<string, mixed> $attributes */
        $attributes = (new Pipeline())
            ->send($this->definition)
            ->through($this->appliedStates)
            ->then(fn (array $attributes) => new Collection($attributes))
            ->filter(fn (mixed $attribute) => $attribute !== $this->placeholder);

        return $attributes->reduce(
            function (array $carry, mixed $attribute, string $key) {
                $carry[$key] = $this->isCallable($attribute) ? $attribute($carry) : $attribute;

                return $carry;
            },
            $attributes->all()
        );
    }

    /**
     * @param array<string, mixed> $state
     * @return array<int, array<string, mixed>>
     */
    public function create(array $state = []): array
    {
        if (count($state) > 0) {
            return $this->state($state)->create();
        }

        return array_map(fn () => $this->createOne(), range(1, $this->count));
    }

    /**
     * @param array<string, mixed> ...$state
     * @return array<int, array<string, mixed>>
     */
    public function createMany(array ...$state): array
    {
        return $this->forEachSequence(...$state)->create();
    }

    /**
     * @param array<string, mixed> $state
     * @return TDataClass
     */
    public function asData(array $state = []): Data
    {
        return $this->data::from($this->createOne($state));
    }

    /**
     * @param array<string, mixed> $state
     * @return array<int, TDataClass>
     */
    public function asDatas(array $state = []): array
    {
        return array_map(fn (array $attributes) => $this->data::from($attributes), $this->create($state));
    }

    /**
     * @param array<string, mixed> ...$state
     * @return array<int, TDataClass>
     */
    public function manyAsDatas(array ...$state): array
    {
        return $this->forEachSequence(...$state)->asDatas();
    }

    /**
     * @param array<string, mixed> $state
     * @return TCollectionClass<int, array<string, mixed>>
     */
    public function asCollection(array $state = []): Collection
    {
        return new ($this->collection)($this->create($state));
    }

    /**
     * @param array<string, mixed> ...$state
     * @return TCollectionClass<int, array<string, mixed>>
     */
    public function manyAsCollection(array ...$state): Collection
    {
        return $this->forEachSequence(...$state)->asCollection();
    }

    /**
     * @param array<string, mixed> $state
     * @return TCollectionClass<int, TDataClass>
     */
    public function asCollectionOfData(array $state = []): Collection
    {
        return new ($this->collection)($this->asDatas($state));
    }

    /**
     * @param array<string, mixed> $state
     * @return TCollectionClass<int, TDataClass>
     */
    public function manyAsCollectionOfData(array ...$state): Collection
    {
        return $this->forEachSequence(...$state)->asCollectionOfData();
    }

    /**
     * @param array<string, mixed>|callable(): array<string, mixed>|string $state
     */
    public function state(array|callable|string $state): static
    {
        if (is_string($state)) {
            $state = $this->states[$state] ?? throw new LogicException("State {$state} is not defined");
        }
        if (!is_callable($state)) {
            $state = fn () => $state;
        }

        $clone = clone $this;
        $clone->appliedStates[] = fn (array $attributes, callable $next) => $next([...$attributes, ...$state()]);

        return $clone;
    }

    public function count(int $count): static
    {
        $clone = clone $this;
        $clone->count = $count;

        return $clone;
    }

    public function without(string ...$fields): static
    {
        return $this->state(array_fill_keys($fields, $this->placeholder));
    }

    public function nullify(string ...$fields): static
    {
        return $this->state(array_fill_keys($fields, null));
    }

    /**
     * @param array<string, mixed> ...$sequence
     */
    public function sequence(array ...$sequence): static
    {
        return $this->state(new Sequence(...$sequence));
    }

    /**
     * @param array<string, mixed> ...$sequence
     */
    public function forEachSequence(array ...$sequence): static
    {
        return $this->state(new Sequence(...$sequence))->count(count($sequence));
    }

    /**
     * @param array<int, array<string, mixed>> ...$sequence
     */
    public function crossJoinSequence(array ...$sequence): static
    {
        return $this->state(new CrossJoinSequence(...$sequence));
    }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return $this->createOne();
    }

    private function isCallable(mixed $attribute): bool
    {
        if ($attribute instanceof Closure) {
            return true;
        }
        if (is_object($attribute) && is_callable($attribute)) {
            return true;
        }

        return false;
    }
}
