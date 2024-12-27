<?php 
namespace App\Annotations\Swagger\HourSession\ShowHourSession;

class ShowHourSessionAnnotations
{
    /**
     * @OA\Get(
     *     path="/hour_session",
     *     summary="Retrieve hour session details",
     *     description="Fetches the details of the hour session associated with the provided JWT token. The token must be valid and authorized.",
     *     tags={"HourSession"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=true,
     *         description="The date of the hour session to retrieve (format: YYYY-MM-DD).",
     *         @OA\Schema(type="string", format="date", example="2020-01-01")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hour session found successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hour session found successfully"),
     *             @OA\Property(property="hour_session", type="object",
     *                 @OA\Property(property="date", type="string", format="date", example="2020-01-01"),
     *                 @OA\Property(property="normal_hours", type="number", format="float", example=8.0),
     *                 @OA\Property(property="overtime_hours", type="number", format="float", example=4.0),
     *                 @OA\Property(property="night_hours", type="number", format="float", example=0.0),
     *                 @OA\Property(property="holiday_hours", type="number", format="float", example=0.0)
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
     *         description="Hour session not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hour session not found")
     *         )
     *     )
     * )
     */
    public function showHourSessionAnnotations() {}
}
