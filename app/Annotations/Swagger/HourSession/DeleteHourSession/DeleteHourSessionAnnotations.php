<?php 
namespace App\Annotations\Swagger\HourSession\DeleteHourSession;

class DeleteHourSessionAnnotations
{
    /** 
     * @OA\delete(
     *     path="/HourSession/delete",
     *     summary="Delete a hour worked",
     *     description="Deletes the hour worked associated with the provided JWT token. The token must be valid and authorized to perform this action.",
     *     tags={"HourSession"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Hour worked deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hour worked deleted successfully")
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
     * 
     */
    public function deleteHourSessionAnnotations(){}
}