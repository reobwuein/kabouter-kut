<?php namespace Kutkabouter\Fetchers; 

class CsvFetcher implements FetcherInterface
{
    /** @var string */
    protected $url;
    /** @var string */
    protected $filename;
    /** @var bool */
    protected $isDownloaded = false;

    /**
     * @param string $url
     * @throws \InvalidArgumentException
     */
    public function __construct($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false)
            throw new \InvalidArgumentException('Invalid url to fetch csv from.');

        $this->url = $url;
    }

    /**
     * @return \Kutkabouter\Reviews\Row
     */
    public function next()
    {
        if (! $this->isDownloaded)
            $this->download();
    }

    protected function download()
    {

    }
}