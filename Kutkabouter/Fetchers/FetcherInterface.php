<?php namespace Kutkabouter\Fetchers;

interface FetcherInterface
{
    /**
     * @return \Kutkabouter\Reviews\Row
     */
    public function next();
} 