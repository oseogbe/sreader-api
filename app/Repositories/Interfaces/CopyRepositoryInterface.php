<?php

namespace App\Repositories\Interfaces;

interface CopyRepositoryInterface
{
    public function addCopy(array $copy_data): array;
}
