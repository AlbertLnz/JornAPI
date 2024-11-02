<?php

namespace App\Annotations\Swagger\Salary;


class SalaryByMonthAnnotations{

    /**
     * @OA\Get(
     *     path="/salary/,
     *     summary="Get salary by month",
     *     tags={"Salary"},
     *     @OA\Parameter(
     *         name="month",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="year",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Get salary by month",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Get salary by month"),
     *             @OA\Property(property="salary", type="float", example=5000.00)
     )
     )
         
     )
     )
     */
}