<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\PeopleService;

class ReadCSVTest extends TestCase
{
    // Simple happy path example
    public function test_reads_successfully(): void
    {
        $service = new PeopleService();

        // input
        $csv = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($csv, "Mr Simon Howden\nMrs Marie Fowler & Mr Homer Simpson\nDr A. Nick\n");
        $people = $service->readCsv($csv);

        // assert
        $this->assertIsArray($people);
        $this->assertCount(4, $people);
        $this->assertEquals('Mr',  $people[0]['title']);
        $this->assertEquals('Howden', $people[0]['last_name']);
        $this->assertEquals('Dr',  $people[3]['title']);
        $this->assertEquals('A',   $people[3]['initial']);
    }

    // Simple unhappy path for bad csv structure check
    public function test_handles_incorrect_csv_format(): void
    {
        $service = new PeopleService();

        // input
        $csv = tempnam(sys_get_temp_dir(), 'test');

        // add an 'age' to csv which is not part of the agreed format
        file_put_contents($csv, "Mr Simon Howden,37\nMrs Marie Fowler,29\nInvalidRowWithoutComma\n");

        // run
        $people = $service->readCsv($csv);

        // assert
        $this->assertIsArray($people);
        $this->assertNotEmpty($people);
        foreach ($people as $person) {
            $this->assertArrayHasKey('title', $person);
            $this->assertArrayHasKey('last_name', $person);
        }
    }

    // Simple unhappy path for file check
    public function test_handles_missing_input(): void
    {
        $service = new PeopleService();

        // fake input
        $path = sys_get_temp_dir() . '/no_such_file_' . uniqid() . '.csv';

        // try
        try {
            $people = $service->readCsv($path);
        } catch (\Throwable $e) {
            $people = [];
        }

        // assert
        $this->assertIsArray($people);
        $this->assertCount(0, $people);
    }
}
