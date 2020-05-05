<?php

require_once __DIR__ . '/../src/includes/init.php';

use PHPUnit\Framework\TestCase;

final class PeriodModelTest extends TestCase
{
    private $validApp;

    protected function setUp(): void
    {
        DB::configure('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $schema = file_get_contents(__DIR__ . '/../schema.sql');
        DB::pdo()->exec($schema);
    }

    protected function tearDown(): void
    {
    }

    public function testPeriodCanBeCreated()
    {
        $this->assertInstanceOf(Period::class, new Period());

        $period = new Period([
            'beginDate'       => '2020-05-01',
            'deadline'        => '2020-06-01',
            'advisorDeadline' => '2020-06-15',
            'budget'          => 1000000
        ]);
        
        $this->assertCount(0, $period->errors());
        $this->assertTrue($period->save());
        $this->assertIsNumeric($period->id);
        $this->assertEquals(1, Period::count());
    }

    public function testInvalidPeriod()
    {
        $period = new Period([
            'beginDate'       => '2020-06-01',
            'deadline'        => '2020-05-01',
            'advisorDeadline' => '2020-04-15',
            'budget'          => -1
        ]);

        $this->assertCount(3, $period->errors());
    }
}
