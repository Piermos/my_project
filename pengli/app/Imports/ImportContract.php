<?php

namespace App\Imports;

use App\Models\Demo;
use App\Models\imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportContract implements ToModel
{

    public function model(array $row)
    {
        return null;
    }

    
}
