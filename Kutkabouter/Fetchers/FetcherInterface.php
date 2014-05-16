<?php namespace Kutkabouter\Fetchers;

interface FetcherInterface
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