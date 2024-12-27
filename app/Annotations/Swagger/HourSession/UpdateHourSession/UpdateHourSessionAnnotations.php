<?php 
namespace App\Annotations\Swagger\HourSession\UpdateHourSession;

class UpdateHourSessionAnnotations
{
    /**
     * @OA\Put(
     *     path="/hour_session",
     *     summary="Update a hour session",
     *     description="Updates the details of the hour session associated with the provided JWT token. The token must be valid and authorized.",
     *     tags={"HourSession"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=true,
     *         description="The date of the hour session to be updated. This field cannot be edited.",
     *         @OA\Schema(type="string", format="date", example="2022-01-01")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="start_time", type="string", format="time", example="08:00"),
     *             @OA\Property(property="end_time", type="string", format="time", example="17:00"),
     *             @OA\Property(property="planned_hours", type="integer", example=8),
     *             @OA\Property(property="is_holiday", type="boolean", example=true),
     *             @OA\Property(property="is_overtime", type="boolean", example=true)
     *         )         
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hour session updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hour session updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token not provided",
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
    public function updateHourSessionAnnotations() {}
}
