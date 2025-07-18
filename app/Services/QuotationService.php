<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Quotation;
use InvalidArgumentException;
use App\DataTransferObjects\QuotationSummary;
use Illuminate\Validation\ValidationException;

class QuotationService
{
    private const FIXED_RATE = 3;

    public function calculateQuotation(
        array $ages,
        string $currencyId,
        Carbon $startDate,
        Carbon $endDate
    ): QuotationSummary
    {
        $tripLength = $this->calculateTripLength($startDate, $endDate);
        
        $total = collect($ages)->sum(fn($age) => self::FIXED_RATE * $tripLength * $this->getAgeLoad($age));

        $quotation = Quotation::create(
            [
                'ages' => $ages,
                'currency_id' => $currencyId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'trip_length' => $tripLength,
                'total' => $total
            ]
        );

        return new QuotationSummary($total, $currencyId, $quotation->id);
    }

    private function calculateTripLength(Carbon $startDate, Carbon $endDate): int
    {
        return $startDate->diffInDays($endDate) + 1;
    }

    private function getAgeLoad(int $age): float
    {
        return match (true) {
            $age >= 18 && $age <= 30 => 0.6,
            $age >= 31 && $age <= 40 => 0.7,
            $age >= 41 && $age <= 50 => 0.8,
            $age >= 51 && $age <= 60 => 0.9,
            $age >= 61 && $age <= 70 => 1.0,
            default => throw new InvalidArgumentException("Age {$age} is not supported")
        };
    }
}