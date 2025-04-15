<?php

namespace App\Exports;

use App\Models\Code;
use Maatwebsite\Excel\Concerns\FromCollection;

class CodesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $groupId;

    public function __construct($groupId)
    {
        $this->groupId = $groupId;
    }

    public function collection()
    {
        return Code::where('codes_group_id', $this->groupId)->where('is_used',false)->get(['code', 'price']);
    }
}
