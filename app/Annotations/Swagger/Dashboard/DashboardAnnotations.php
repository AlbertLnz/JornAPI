<?php 
namespace App\Annotations\Swagger\Dashboard;

class DashboardAnnotations {

  /**
 * @OA\Get(
 *     path="/dashboard",
 *     summary="Retrieve User Dashboard",
 *     description="Fetches the dashboard details for the authenticated user.",
 *     tags={"Dashboard"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Dashboard retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Dashboard retrieved successfully"),
 *             @OA\Property(property="dashboardData", type="object",
 *                 @OA\Property(property="totalHoursWorked", type="number", format="float", example=120.5),
 *                 @OA\Property(property="currentMonthSalary", type="number", format="float", example=1000.0),
 *                 @OA\Property(property="dailyWorkHours", type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="date", type="string", format="date", example="2023-11-02"),
 *                         @OA\Property(property="startTime", type="string", format="time", example="09:00:00"),
 *                         @OA\Property(property="endTime", type="string", format="time", example="17:00:00"),
 *                         @OA\Property(property="plannedHours", type="number", format="float", example=8.0),
 *                         @OA\Property(property="actualHours", type="number", format="float", example=7.5),
 *                         @OA\Property(property="workType", type="string", enum={"NORMAL", "OVERTIME", "HOLIDAY"})
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized access"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */

     public function dashboardAnnotations() {}

}