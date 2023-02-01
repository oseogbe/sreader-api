<?php

use Illuminate\Support\Facades\DB;

function getSubjectNameById($id)
{
    return DB::table('subjects')->find($id)->name;
}

function getLevelNameById($id)
{
    return DB::table('levels')->find($id)->name;
}
