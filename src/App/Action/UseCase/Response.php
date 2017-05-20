<?php

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/6/17
 * Time: 8:37 AM
 */
class Response
{

    /**
     * @var int
     */
    private $state;

    /**
     * @var string
     */
    private $message;

    /**
     * @var mixed
     */
    private $data;

    /**
     * Response constructor.
     * @param int $state
     * @param string $message
     * @param mixed $data
     */
    public function __construct($state, $message, $data = null)
    {
        $this->state = $state;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->state == 200;
    }
}