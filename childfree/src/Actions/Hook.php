<?php

namespace Wz\Childfree\Actions;


class Hook
{
    /**
     * Action priority
     *
     * @var int
     */
    public static int $priority = 10;

    /**
     * Number of action arguments
     *
     * @var int
     */
    public static int $arguments = 1;

    /**
     * Hook names that this listens to
     *
     * @var array
     */
    public static array $hooks = array();
}
