<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InvoiceCounter;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceCounter>
 */
class InvoiceCounterFactory extends Factory
{
    protected $model = InvoiceCounter::class;

    public function definition(): array
    {
        return [
            'day' => now()->toDateString(),
            'key' => strtoupper($this->faker->bothify('???-##') ),
            'last_number' => 0,
        ];
    }
}
