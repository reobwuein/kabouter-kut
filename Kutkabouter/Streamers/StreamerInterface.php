<?php namespace Kutkabouter\Streamers;

interface StreamerInterface
{
    /**
     * @return array
     */
    public function next();

    /**
     * @return array
     */
    public function getHeaders();
} 