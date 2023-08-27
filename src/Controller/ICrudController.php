<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;

interface ICrudController extends IHttpMethod
{
    const PARAMETER_ID = '/{id}';

    /**
     * Method return collection of items
     * @param int $firstItem
     * @param int $maxItem
     * @return JsonResponse
     */
    public function fetchAll(int $firstItem, int $maxItem): JsonResponse;

    public function fetchById(int $id): JsonResponse;

    public function deleteItem(int $id): JsonResponse;
}
