<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory;

use Closure;
use Illuminate\Database\Eloquent\Factories\CrossJoinSequence;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use LogicException;
use Spatie\DataTransferObject\DataTransferObject;
use stdClass;
use function count;
use function is_callable;
use function is_string;

/**
 * @template TDtoClass of \Spatie\DataTransferObject\DataTransferObject
 * @template TCollectionClass of \Illuminate\Support\Collection
 */
class ArrayFactory
{
    /** @var class-string<TDtoClass> */
    protected string $dto;

    /** @var class-string<TCollectionClass> */
    protected string $collection;

    /** @var array<int, callable(array<string, mixed>, callable(array<string,mixed>): array<string,mixed>): array<string, mixed>> */
    protected array $appliedStates = [];

    protected object $placeholder;

    protected int $count = 1;

    /**
     * @param array<string, mixed> $definition
     * @param array<string, array<string, mixed>|callable(): array<string, mixed>> $states
     * @param class-string<TDtoClass> $dto
     * @param class-string<TCollectionClass> $collection
     */
    final public function __construct(
        public array $definition = [],
        public array $states = [],
        string $dto = DataTransferObject::class,
        string $collection = Collection::class,
    ) {
        $this->dto ??= $dto;
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

        return (new Pipeline())
            ->send($this->definition)
            ->through($this->appliedStates)
            ->then(fn (array $attributes) => new Collection($attributes))
            ->filter(fn (mixed $attribute) => $attribute !== $this->placeholder)
            ->map(fn (mixed $attribute) => $this->isCallable($attribute) ? $attribute() : $attribute)
            ->toArray();
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
     * @return TDtoClass
     */
    public function asDto(array $state = []): DataTransferObject
    {
        return new ($this->dto)($this->createOne($state));
    }

    /**
     * @param array<string, mixed> $state
     * @return array<int, TDtoClass>
     */
    public function asDtos(array $state = []): array
    {
        return array_map(fn (array $attributes) => new ($this->dto)($attributes), $this->create($state));
    }

    /**
     * @param array<string, mixed> ...$state
     * @return array<int, TDtoClass>
     */
    public function manyAsDtos(array ...$state): array
    {
        return $this->forEachSequence(...$state)->asDtos();
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
     * @return TCollectionClass<int, TDtoClass>
     */
    public function asDtoCollection(array $state = []): Collection
    {
        return new ($this->collection)($this->asDtos($state));
    }

    /**
     * @param array<string, mixed> $state
     * @return TCollectionClass<int, TDtoClass>
     */
    public function manyAsDtoCollection(array ...$state): Collection
    {
        return $this->forEachSequence(...$state)->asDtoCollection();
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
