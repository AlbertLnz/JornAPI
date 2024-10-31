<?php 

namespace App\Services\Salary;

use App\Models\Employee;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Models\Salary;
use Carbon\Carbon;

class SalaryService implements  SalaryServiceInterface
{
    use CalculateSalaryTrait;
    public function execute(string $employeeId, string $date)
    {
        $date = new Carbon($date);
        // Primer día del mes
        $startOfMonth =new Carbon( $date->copy()->startOfMonth()->toDateString());

        // Último día del mes
        $endOfMonth = new Carbon($date->copy()->endOfMonth()->toDateString());// Último día del mes
        

        $employee = Employee::findOrFail($employeeId);


        // Obtenemos todas las HourSession en el rango y pluck para obtener solo los IDs
        $hourSessions = $employee->hourSessions()
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->with('hourWorked')
        ->get();

        $salary = Salary::where('employee_id', $employeeId)
        ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
        ->whereBetween('end_date', [$startOfMonth, $endOfMonth])
        ->first();
    
    // Agrupar los datos en una colección
    $hourWorkedCollection = $hourSessions->pluck('hourWorked');
       $dataSalary = $this->calculateSalary($hourWorkedCollection, $employee);

        // Ahora puedes usar la colección $hourWorkeds para cálculos adicionales o sumar sus valores
        if ($salary) {

            $salary->total_normal_hours = $dataSalary['total_normal_hours']; 
     
            $salary->total_overtime_hours = $dataSalary['total_overtime_hours'];
     
            $salary->total_holiday_hours = $dataSalary['total_holiday_hours'];
     
            $salary->total_night_hours = $dataSalary['total_night_hours'];
            $salary->total_gross_salary = $dataSalary['gross_salary'];
     
            $salary->save();
                 
             }else{
                $salary = Salary::updateOrCreate(
                    ['employee_id' => $employeeId,
                    'start_date' => $startOfMonth,
                    'end_date' => $endOfMonth,
                    'total_normal_hours' => $dataSalary['total_normal_hours'],
                    'total_overtime_hours' => $dataSalary['total_overtime_hours'],
                    'total_holiday_hours' => $dataSalary['total_holiday_hours'],
                    'total_night_hours' => $dataSalary['total_night_hours'],
                    'total_gross_salary' => $dataSalary['gross_salary'],
                    'total_net_salary' => 0
               
                  
         ]       );
             }

        // Calculamos el salario y guardamos en la entidad Salary
       


        // Devolvemos la colección $hourWorkeds si necesitas acceder a los detalles en otros puntos
        return $salary;
    }
}
