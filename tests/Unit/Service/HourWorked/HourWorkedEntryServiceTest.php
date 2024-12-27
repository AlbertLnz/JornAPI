<?php

namespace Tests\Unit\Service\HourWorked;

use App\Enums\WorkTypeEnum;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Services\HourWorked\HourWorkedEntryService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HourWorkedEntryServiceTest extends TestCase
{
    use DatabaseTransactions;

    private HourWorkedEntryService $hourWorkedEntryService;

    private HourSession $hourSession;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hourSession = HourSession::factory()->create([
            'date' => '2023-01-21',
            'start_time' => '08:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
            'work_type' => WorkTypeEnum::NORMAL->value,
        ]);
        $this->hourWorkedEntryService = new HourWorkedEntryService;
    }

    public function test_cant_instantiate(): void
    {
        $this->assertInstanceOf(HourWorkedEntryService::class, $this->hourWorkedEntryService);
    }

    public function test_hour_worked_entry_service_execute(): void
    {
        $this->hourWorkedEntryService->execute($this->hourSession->id, $this->hourSession->start_time, $this->hourSession->end_time, $this->hourSession->planned_hours, WorkTypeEnum::NORMAL->value);

        $hourWorked = HourWorked::where('hour_session_id', $this->hourSession->id)->first();

        $this->assertNotNull($hourWorked);
    }
}
