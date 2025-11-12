<?php

namespace App\Console\Commands;
use App\Services\PeopleService;

use Illuminate\Console\Command;

class ReadCSV extends Command
{
    // accept an input and an output
    protected $signature = 'csv:write 
        {input : Path to the input CSV file}
        {output : Path to the output CSV file}';

    protected $description = 'Read the CSV and convert to consistent format';

    public function handle(PeopleService $service)
    {
        // input
        $input = $this->argument('input');
        $output = $this->argument('output');

        if (! file_exists($input)) {
            $this->error("File not found: {$input}");
            return self::FAILURE;
        }

        // use of service
        $people = $service->readCsv($input);

        //output
        $this->line(json_encode($people, JSON_PRETTY_PRINT));

        // csv specific output
        $out = fopen($output, 'w');
        if ($out === false) {
            $this->error("Error opening file");
            return self::FAILURE;
        }
        // format of csv (specific to our json schema, we arent using separate schemas for this now)
        fputcsv($out, ['title', 'first_name', 'initial', 'last_name']);

        // give data based on task example file
        foreach ($people as $p) {
            fputcsv($out, [
                $p['title']      ?? '',
                $p['first_name'] ?? '',
                $p['initial']    ?? '',
                $p['last_name']  ?? '',
            ]);
        }

        // complete
        fclose($out);

        // show success message
        $this->info("{$output} written");

        return self::SUCCESS;
    }
}
