<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Locale;
use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $message
 * @property Locale $locale
 * @property bool $is_read
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $full_name
 */
class Message extends Model
{
    /** @use HasFactory<MessageFactory> */
    use HasFactory;

    /**
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::get(
            fn (): string => trim("{$this->first_name} {$this->last_name}"),
        );
    }

    /**
     * @param  Builder<Message>  $query
     * @return Builder<Message>
     */
    #[Scope]
    protected function unread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'locale' => Locale::class,
            'is_read' => 'boolean',
        ];
    }
}
