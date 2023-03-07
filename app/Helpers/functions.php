<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

function getSubjectNameById($id)
{
    return DB::table('subjects')->find($id)->name;
}

function getLevelNameById($id)
{
    return DB::table('levels')->find($id)->name;
}

function paginate($items, $perPage = 10, $page = null)
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

    $total = count($items);

    $currentpage = $page;

    $offset = ($currentpage * $perPage) - $perPage ;

    $itemstoshow = array_slice($items, $offset, $perPage);

    return new LengthAwarePaginator($itemstoshow, $total, $perPage);
}
