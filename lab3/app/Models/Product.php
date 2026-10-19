<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    /** Допустимі способи угоди предметного варіанта №9 («Продаж комп’ютерної техніки»). */
    public const OPERATIONS = ['Продаж'];

    /** Загальні характеристики; решта ключів attributes — технічні специфікації картки. */
    public const GENERAL_ATTRIBUTES = ['Стан', 'Гарантія', 'Бренд'];

    protected $fillable = [
        'category_id',
        'slug',
        'title',
        'operation',
        'price',
        'currency',
        'short_description',
        'description',
        'image',
        'contact_name',
        'contact_phone',
        'contact_email',
        'status',
        'published_at',
        'attributes',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'published_at' => 'date',
            'attributes' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Усі характеристики як масив. Читаємо сирий JSON зі службового $this->attributes,
     * бо стовпець називається "attributes" і збігається з внутрішньою властивістю Eloquent.
     *
     * @return array<string, string>
     */
    protected function characteristics(): Attribute
    {
        return Attribute::get(function (): array {
            $raw = $this->attributes['attributes'] ?? null;
            $data = is_string($raw) ? json_decode($raw, true) : $raw;

            return is_array($data) ? $data : [];
        });
    }

    /** Технічні специфікації для картки (CPU, RAM, SSD тощо). */
    protected function specs(): Attribute
    {
        return Attribute::get(fn (): array => array_diff_key(
            $this->characteristics,
            array_flip(self::GENERAL_ATTRIBUTES)
        ));
    }

    /** Три перші продукти — рекомендовані (див. CatalogService::featured()). */
    protected function isFeatured(): Attribute
    {
        return Attribute::get(fn (): bool => $this->id !== null && $this->id <= 3);
    }

    protected function badge(): Attribute
    {
        return Attribute::get(fn (): ?string => match ($this->id) {
            1 => 'Хіт продажу',
            2 => 'Для ігор',
            3 => 'Для дизайну',
            default => null,
        });
    }

    protected function badgeClass(): Attribute
    {
        return Attribute::get(fn (): string => match ($this->id) {
            2 => 'tech-card__label--violet',
            3 => 'tech-card__label--green',
            default => '',
        });
    }

    /** Фрагмент зображення-атласу (BEM-модифікатор). */
    protected function imageClass(): Attribute
    {
        return Attribute::get(function (): string {
            $classes = [
                'atlas-frame__image--first',
                'atlas-frame__image--second',
                'atlas-frame__image--third',
            ];

            return $classes[(((int) $this->id) + 2) % 3];
        });
    }
}
