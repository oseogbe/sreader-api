<?php

namespace App\Repositories\Interfaces;

interface ParentRepositoryInterface
{
    public function getParentsData(): array;
    public function getParentData(string $parent_id): array;
    public function createParent(array $parent_data): array;
}
