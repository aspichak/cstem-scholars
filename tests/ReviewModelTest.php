<?php

require_once __DIR__ . '/../src/includes/init.php';

use PHPUnit\Framework\TestCase;

final class ReviewModelTest extends TestCase
{
    protected function setUp(): void
    {
        DB::configure('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $schema = file_get_contents(__DIR__ . '/../setup.sql');
        DB::pdo()->exec($schema);
    }

    protected function tearDown(): void
    {
    }

    public function testPeriodCanBeCreated()
    {
        $this->assertInstanceOf(Review::class, new Review());

        $review = new Review([
            'q1' => '0',
            'q2' => '0',
            'q3' => '0',
            'q4' => '0',
            'q5' => '0',
            'q6' => '0',
            'comments' => '',
            'fundingRecommended' => true
        ]);

        $review->applicationID = 0;
        $review->periodID = 0;
        $review->submitted = true;

        $this->assertCount(0, $review->errors());
        $this->assertTrue($review->save());
        $this->assertIsNumeric($review->id);
        $this->assertEquals(1, Review::count());
    }

    public function testInvalidPeriod()
    {
        $review = new Review([
            'q1' => '99',
            'q2' => '-99'
        ]);

        $this->assertCount(7, $review->errors());
    }
}
