<?php

namespace App\Entity;

interface EntityInterface
{
    public function getId(): ?int;

    public function setId(int $id);

}
