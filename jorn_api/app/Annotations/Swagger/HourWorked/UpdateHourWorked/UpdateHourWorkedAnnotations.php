<?php 
namespace App\Annotations\Swagger\HourWorked\UpdateHourWorked;


class UpdateHourWorkedAnnotations
{
    /**
     * @OA\Put(
     *     path="/hourworked",
     *     summary="Update a hour worked",
     *     description="Updates the details of the hour worked associated with the provided JWT token. The token must be valid and authorized.",
     *     tags={"HourWorked"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"date", "date", "start_time", "end_time", "planned_hours", "is_holiday", "is_overtime"},
     *             @OA\Property(property="date", type="date", example="2022-01-01"),
     *             @OA\Property(property="start_time", type="time", example="08:00"),
     *             @OA\Property(property="end_time", type="time", example="17:00"),
     *             @OA\Property(property="planned_hours", type="integer", example=8),
     *             @OA\Property(property="is_holiday", type="boolean", example=true),
     *             @OA\Property(property="is_overtime", type="boolean", example=true),
     *         )         
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hour worked updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hour worked updated successfully")
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
    public function updateHourWorkedAnnotations(){}

}