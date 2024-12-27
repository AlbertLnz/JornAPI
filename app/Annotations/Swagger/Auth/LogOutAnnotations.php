<?php

namespace App\Annotations\Swagger\Auth;

class LogOutAnnotations
{
    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Log out the user",
     *     description="Logs out the user by invalidating the JWT token",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"Authorization"},
     *
     *             @OA\Property(property="Authorization", type="string", example="Bearer your-jwt-token")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Invalid token",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", example="Invalid token")
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="string",
     *             example="Bearer your-jwt-token"
     *         )
     *     )
     * )
     */
    public function logout() {}
}
