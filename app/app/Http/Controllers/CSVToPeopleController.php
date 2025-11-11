<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PeopleFromCsvService;

class CSVToPeopleController extends Controller
{
    public function import(Request $request, CSVToPeopleController $service)
    {
        $request->validate(['file' => ['required', 'file']]);

        $path = $request->file(key: 'file')->getRealPath();
        $people = $service->readCsv($path);

        return response()->json($people);
    }
}
