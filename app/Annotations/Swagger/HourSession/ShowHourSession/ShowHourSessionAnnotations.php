<?php 
namespace App\Annotations\Swagger\HourSession\ShowHourSession;


class ShowHourSessionAnnotations
{
    /**
     * @OA\Get(
     *     path="/HourSession?date={date}",
     *     summary="Retrieve hour worked details",
     *     description="Fetches the details of the hour worked associated with the provided JWT token. The token must be valid and authorized.",
     *     tags={"HourSession"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hour worked found successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hour worked found successfully"),
     *             @OA\Property(property="hour_worked", type="object",
     *                 @OA\Property(property="date", type="string", example="2020-01-01"),
     *                 @OA\Property(property="normal_hours", type="number", format="float", example=8.0),
     *                 @OA\Property(property="overtime_hours", type="number", format="float", example=4.0),
     *                 @OA\Property(property="night_hours", type="number", format="float", example=0.0),
     *                 @OA\Property(property="holiday_hours", type="number", format="float", example=0.0),
     * 
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized or invalid token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized or invalid token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Hour worked not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hour worked not found")
     *         )
     *     )
     * )
     */
    public function showHourSessionAnnotations(){}
}