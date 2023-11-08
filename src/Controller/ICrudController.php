<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;

interface ICrudController extends IHttpMethod
{
    const PARAMETER_ID = '/{id}';
    const OPTIONS_MIN_RANGE_0 = ['options' => ['min_range' => 0]];
    const OPTIONS_MIN_RANGE_1 = ['options' => ['min_range' => 1]];
    const POSITIVE_INTEGER = ['id' => '\d+'];

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
