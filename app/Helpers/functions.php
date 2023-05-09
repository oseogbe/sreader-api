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

function generateRandomString($length = 12) {
    $characters = 'CD8TUPQRSV9ABxyzEFGHMNO0uvw1WXYZdefghi2jklm3nIJKLop4qrst56abc7';
    $randomString = substr(str_shuffle($characters), 0, $length);
    return $randomString;
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

function growthBetweenTimePeriods($Model, $period)
{
    if($period) {
        $periodParts = explode(' ', $period);
        if (is_numeric($periodParts[0])) {
            $value = $periodParts[0];
            $unit = $periodParts[1];

            if($unit == 'week')
            {
                $firstStartDate = now()->subWeeks(($value * 2) - 1)->startOfWeek();
                $firstEndDate = now()->subWeeks($value)->endOfWeek();

                $secondStartDate = now()->subWeeks($value - 1)->startOfWeek();
                $secondEndDate = now()->endOfWeek();
            }
            else {
                $firstStartDate = now()->subMonths(($value * 2) - 1)->startOfMonth();
                $firstEndDate = now()->subMonths($value)->endOfMonth();

                $secondStartDate = now()->subMonths($value - 1)->startOfMonth();
                $secondEndDate = now()->endOfMonth();
            }

            // $Model = str_replace('&quot;', '', "App\Models\\$Model");

            $startCount = $Model->whereBetween('created_at', [$firstStartDate, $firstEndDate])->count();

            $endCount = $Model->whereBetween('created_at', [$secondStartDate, $secondEndDate])->count();

            if($startCount == 0) return 0;

            // calculate the percentage increase
            $increase = ($endCount - $startCount) / $startCount * 100;

            return $increase;
        }
    }
}

function getFirstAndLastName($name)
{
    $name_words = str_word_count($name, 1);
    $firstname = $name_words[0];
    $lastname = $name_words[count($name_words) - 1];

    return [$firstname, $lastname];
}
