<?php

namespace App\Entity;

interface IEntity
{
    public function getId(): ?int;

    public function setId(int $id);

}
