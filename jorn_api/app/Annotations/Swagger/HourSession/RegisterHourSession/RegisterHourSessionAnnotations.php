<?php 
namespace App\Annotations\Swagger\HourSession\RegisterHourSession;


class RegisterHourSessionAnnotations
{
    /**
     * @OA\Post(
     *     path="/HourSession",
     *     summary="Register a new hour worked",
     *     description="Registers a new hour worked with the provided details.",
     *     tags={"HourSession"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"employee_id", "date", "start_time", "end_time", "planned_hours", "is_holiday", "is_overtime"},
     *             @OA\Property(property="employee_id", type="uuid", example="123e4567-e89b-12d3-a456-426614174000"),
     *             @OA\Property(property="date", type="date", example="2022-01-01"),
     *             @OA\Property(property="start_time", type="time", example="08:00"),
     *             @OA\Property(property="end_time", type="time", example="17:00"),
     *             @OA\Property(property="planned_hours", type="integer", example=8),
     *             @OA\Property(property="is_holiday", type="boolean", example=true),
     *             @OA\Property(property="is_overtime", type="boolean", example=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Hour worked created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hour worked created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input data",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Validation failed")
     *         )
     *     )
     * )
     */
    public function register(){}
}