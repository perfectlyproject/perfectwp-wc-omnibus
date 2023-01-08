<?php

namespace PerfectWPWCO\Managers;

use PerfectWPWCO\Plugin;

class ListenerManager
{
    private $registered = [];

    private $plugin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function register(string $class)
    {
        if (isset($this->registered[$class])) {
            throw new \Exception('Listener already registered');
        }

        $listener = $this->plugin->get($class);
        $listener->register();

        $this->registered[$class] = $listener;
    }

    public function unregister(string $class)
    {
        if (!isset($this->registered[$class])) {
            throw new \Exception('Listener not registered');
        }

        $this->registered[$class]->unregister();
        unset($this->registered[$class]);
    }
}