<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory;

use Illuminate\Database\Eloquent\Factories\Sequence;
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
    /** @var array<string, mixed> */
    protected array $appliedStates = [];

    protected object $placeholder;

    protected int $count = 1;

    /**
     * @param array<string, mixed> $definition
     * @param array<string, array<string, mixed>|callable(): array<string, mixed>> $states
     * @param class-string<TDtoClass> $dto
     * @param class-string<TCollectionClass> $collection
     */
    public function __construct(
        public array $definition,
        public array $states = [],
        public string $dto = DataTransferObject::class,
        public string $collection = Collection::class,
    ) {
        $this->placeholder = new stdClass();
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

        return array_map(
            $this->resolve(...),
            array_filter(
                [...$this->definition, ...$this->appliedStates],
                fn (mixed $value) => $value !== $this->placeholder,
            ),
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
     * @param array<string, mixed>|callable(): array<string, mixed>|string $state
     */
    public function state(array|callable|string $state): static
    {
        if (is_string($state)) {
            $state = $this->states[$state] ?? throw new LogicException("State {$state} is not defined");
        }
        if (is_callable($state)) {
            $state = $state();
        }

        $clone = clone $this;
        $clone->appliedStates = array_merge($clone->appliedStates, $state);

        return $clone;
    }

    public function count(int $count): self
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

    public function sequence(array ...$sequence): static
    {
        var_dump(new Sequence(...$sequence));
        exit;

        return $this->state(new Sequence(...$sequence));
    }

    protected function resolve(mixed $value): mixed
    {
        return is_callable($value) ? $value() : $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return $this->createOne();
    }
}
