<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\QuotationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\QuotationRequest;
use App\Support\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class QuotationController extends Controller
{
    public function __construct(
        private readonly QuotationService $quotationService
    ) {}

    public function store(QuotationRequest $request): JsonResponse
    {
        $quotation = $this->quotationService->calculateQuotation(
            ages: $request->getAges(),
            currencyId: $request->validated('currency_id'),
            startDate: $request->getStartDate(),
            endDate: $request->getEndDate()
        );

        return ApiResponse::format(
            success: true,
            message: 'quotation summary calculated!',
            status: Response::HTTP_OK,
            data: $quotation->toArray()
        );
    }
}
