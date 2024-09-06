<?php

namespace App\Annotations\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Jornalia API",
 *     description="This is the API documentation for the Jornalia application.",
 *     termsOfService="https://example.com/terms",
 *     contact={
 *         "name": "API Support",
 *         "url": "https://example.com/support",
 *         "email": "support@example.com"
 *     },
 *     license={
 *         "name": "MIT",
 *         "url": "https://opensource.org/licenses/MIT"
 *     }
 * )
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Local development server"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Bearer token authentication",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 */
class AnnotationsInfo
{
}
