<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1\HourSession\RegisterHourSession;

use App\DTO\HourSession\HourSessionDTO;
use App\Enums\WorkTypeEnum;
use App\Exceptions\HourSessionExistException;
use App\Exceptions\TimeEntryException;
use App\Exceptions\TodayDateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterHourSessionRequest;
use App\Services\HourSession\RegisterHourSessionService;
use Illuminate\Http\JsonResponse;

class RegitsterHourSessionController extends Controller
{
    /**
     * Summary of __construct
     */
    public function __construct(private RegisterHourSessionService $hourSessionRegisterService) {}

    /**
     * Summary of __invoke
     */
    public function __invoke(RegisterHourSessionRequest $request): JsonResponse
    {

        try {
            $employee = $request->user()->employee;
            $workType = WorkTypeEnum::fromValue($request->work_type);
            $hourSessionDTO = new HourSessionDTO($request->date, $request->start_time, $request->end_time, $request->planned_hours, $workType->value);

            $this->hourSessionRegisterService->execute(
                $employee->id,
                $hourSessionDTO);

            return response()->json(['message' => 'Hour session registered successfully'], 201);
        } catch (HourSessionExistException|TodayDateException|TimeEntryException $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

    }
}
