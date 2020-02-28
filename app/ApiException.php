<?php
namespace app;

use RuntimeException;

class ApiException extends RuntimeException
{
    protected $data;

    public function __construct($code = -1, $message = '服务器异常', $data = null)
    {
        parent::__construct($message, $code);

        $this->data = $data;
    }

    public function toArray() {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'data' => $this->data,
        ];
    }
}