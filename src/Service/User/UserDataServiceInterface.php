<?php

namespace App\Service\User;

use App\Collection\WordGroups;
use Symfony\Component\HttpFoundation\Request;

interface UserDataServiceInterface
{
    public function update(Request $request): void;

    public function getLearningGroups(): ?WordGroups;

    public function updateLearningGroups(Request $request): void;

    public function addLearningGroup($id): void;

    public function removeLearningGroup($id): void;
}
