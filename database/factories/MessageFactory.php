<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Locale;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->e164PhoneNumber(),
            'message' => fake()->paragraphs(2, true),
            'locale' => fake()->randomElement(Locale::cases())->value,
            'is_read' => false,
        ];
    }
}
