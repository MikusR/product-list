<?php

declare(strict_types=1);

namespace App;

class JsonResponse implements Response
{
    private int $status;
    private array $data;

    public function __construct(int $status = 200, array $data = [])
    {
        $this->status = $status;
        $this->data = $data;
    }


    public function getData(): array
    {
        return $this->data;
    }


    public function getStatus(): int
    {
        return $this->status;
    }
}