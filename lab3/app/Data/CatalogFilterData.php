<?php

namespace App\Data;

/**
 * Типізований незмінний набір перевірених параметрів каталогу.
 * Створюється з $request->validated() і передається в CatalogService.
 */
final readonly class CatalogFilterData
{
    public const SORTS = [
        'newest' => 'Спочатку нові',
        'price_asc' => 'Від дешевших',
        'price_desc' => 'Від дорожчих',
    ];

    public const PER_PAGE_OPTIONS = [9, 12, 24];

    public const DEFAULT_SORT = 'newest';

    public const DEFAULT_PER_PAGE = 9;

    public function __construct(
        public ?string $q = null,
        public ?string $category = null,
        public ?string $operation = null,
        public string $sort = self::DEFAULT_SORT,
        public int $page = 1,
        public int $perPage = self::DEFAULT_PER_PAGE,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            q: self::text($data['q'] ?? null),
            category: self::text($data['category'] ?? null),
            operation: self::text($data['operation'] ?? null),
            sort: (string) ($data['sort'] ?? self::DEFAULT_SORT),
            page: (int) ($data['page'] ?? 1),
            perPage: (int) ($data['per_page'] ?? self::DEFAULT_PER_PAGE),
        );
    }

    /**
     * Лише перевірені фільтри для посилань пагінації (без page і невідомих параметрів).
     *
     * @return array<string, string|int>
     */
    public function toQuery(): array
    {
        return array_filter([
            'q' => $this->q,
            'category' => $this->category,
            'operation' => $this->operation,
            'sort' => $this->sort !== self::DEFAULT_SORT ? $this->sort : null,
            'per_page' => $this->perPage !== self::DEFAULT_PER_PAGE ? $this->perPage : null,
        ], fn ($value) => $value !== null && $value !== '');
    }

    private static function text(mixed $value): ?string
    {
        $value = is_string($value) ? trim($value) : null;

        return $value === '' ? null : $value;
    }
}
