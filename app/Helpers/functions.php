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

function storeFileOnFirebase($remotefolder, $file)
{
    try {
        $localfolder = public_path('firebase-temp-uploads') .'/';

        $file_name = $file->hashName();

        if ($file->move($localfolder, $file_name)) {
            $uploadedfile = fopen($localfolder.$file_name, 'r');
            $file_path = $remotefolder . '/' . $file_name;
            app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $file_path]);
            unlink($localfolder . $file_name);
            return $file_path;
        }
    } catch (Throwable $e) {
        throw $e;
    }
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
