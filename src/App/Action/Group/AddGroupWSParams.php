<?php

namespace App\Action\Group;

class AddGroupWSParams
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $icon;

    /**
     * AddGroupWSParams constructor.
     * @param string $name
     * @param string $icon
     */
    public function __construct($name, $icon)
    {
        $this->name = $name;
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * return bool
     */
    public function isValid()
    {
        return $this->name && $this->icon;
    }

}