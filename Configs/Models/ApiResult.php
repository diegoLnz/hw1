<?php

class ApiResult
{
    public mixed $data;
    public array $errors;

    public function __construct(mixed $data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }

}