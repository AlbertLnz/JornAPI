<?php

namespace App\Annotations\Swagger\Salary;

class SalaryByMonthAnnotations
{
    /**
     * @OA\Get(
     *     path="/salary",
     *     summary="Retrieve Salary by Month",
     *     description="Fetches the details of the salary associated with the provided JWT token. The token must be valid and authorized.",
     *     tags={"Salary"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="month",
     *         in="query",
     *         required=true,
     *         description="The month for which the salary is retrieved",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         required=true,
     *         description="The year for which the salary is retrieved",
     *
     *         @OA\Schema(type="integer", example=2020)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Salary found successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Salary found successfully"),
     *             @OA\Property(property="salary", type="object",
     *                 @OA\Property(property="total_normal_hours", type="number", format="float", example=8.0),
     *                 @OA\Property(property="total_overtime_hours", type="number", format="float", example=4.0),
     *                 @OA\Property(property="total_holiday_hours", type="number", format="float", example=0.0),
     *                 @OA\Property(property="total_gross_salary", type="number", format="float", example=120.0),
     *                 @OA\Property(property="total_net_salary", type="number", format="float", example=108.0)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Salary not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Salary not found")
     *         )
     *     )
     * )
     */
    public function showHourSessionAnnotations() {}
}
