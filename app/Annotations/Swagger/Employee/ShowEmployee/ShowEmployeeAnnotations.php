<?php
namespace App\Annotations\Swagger\Employee\ShowEmployee;


class ShowEmployeeAnnotations{
    /**
 * @OA\Get(
 *     path="/employee",
 *     summary="Retrieve employee details",
 *     description="Fetches the details of the employee associated with the authenticated user. The provided JWT token must belong to a user with the role of 'employee'.",
 *     tags={"Employee"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Employee found successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Employee found successfully"),
 *             @OA\Property(property="employee", type="object", 
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *                 @OA\Property(property="normal_hourly_rate", type="number", format="float", example=20.00),
 *                 @OA\Property(property="overtime_hourly_rate", type="number", format="float", example=30.00),
 *                 @OA\Property(property="night_hourly_rate", type="number", format="float", example=25.00),
 *                 @OA\Property(property="holiday_hourly_rate", type="number", format="float", example=35.00),
 *                 @OA\Property(property="irpf", type="number", format="float", example=5.00)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Token not provided",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid token")
 *         )
 *     )
 * )
 */
    public function showEmployeeAnnotations(){}
}