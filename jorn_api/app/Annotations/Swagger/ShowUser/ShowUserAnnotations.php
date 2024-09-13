<?php

namespace App\Annotations\Swagger\ShowUser;

class ShowUserAnnotations{
    /**
 * @OA\Get(
 *     path="/user/show",
 *     summary="Retrieve user details",
 *     description="Fetches the details of the user associated with the provided JWT token. The token must be valid and authorized.",
 *     tags={"User"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="User found successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User found successfully"),
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *                 @OA\Property(property="role", type="string", example="employee")
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
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User not found")
 *         )
 *     )
 * )
 */
    public function shouUser(){}
}