<?php 

namespace App\Services;

use SplFileObject;

class PeopleService
{
    // csv-file specific
    public function readCsv(string $path): array
    {
        $file = new SplFileObject($path);
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);

        $lines = [];
        foreach ($file as $row) {
            if (!is_array($row) || !isset($row[0])) continue;
            $line = trim((string) $row[0]);
            if ($line !== '') $lines[] = $line;
        }

        return $this->readJson($lines);
    }

    // separated incase we ever use this with an endpoint
    public function readJson(array $lines): array
    {
        $people = [];
        foreach ($lines as $raw) {
            foreach ($this->parseLine((string)$raw) as $p) {
                $people[] = $p;
            }
        }
        return $people;
    }

    private function parseLine(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') return [];

        // regex type pattern check for multiple people 
        $splits = preg_split('/\s*(?:&| and )\s*/i', $raw);

        // loop through each split
        $out = [];
        foreach ($splits as $item) {
            $item = trim($item);

            // supported titles list
            if (!preg_match('/^(Mr|Mrs|Ms|Miss|Dr)\b\.?\s*(.*)$/i', $item, $m)) {
                continue; // only handling sample cases
            }

            // lower-case check after titles check
            $title = ucfirst(strtolower($m[1]));

            // whitespace
            $rest  = trim($m[2]);

            // skip if title-only
            if ($rest === '') {
                continue;
            }

            // split further for names
            $parts = preg_split('/\s+/', $rest);
            $last  = array_pop($parts) ?? '';
            $lead  = trim(implode(' ', $parts));
            $first   = null;
            $initial = null;

            // process with another regex pattern
            if ($lead !== '' && preg_match('/^[A-Za-z]\.?$/', $lead)) {
                $initial = strtoupper($lead[0]);
            } elseif ($lead !== '') {
                $first = $lead;
            }

            // skip if theres not at least one name
            if ($first === null && $initial === null && $last === '') {
                continue;
            }

            // output format same as in artisan command
            $out[] = [
                'title'      => $title,
                'first_name' => $first ?: null,
                'initial'    => $initial,
                'last_name'  => $last,
            ];
        }
        return $out;
    }
}
