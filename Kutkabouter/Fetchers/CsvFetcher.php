<?php namespace Kutkabouter\Fetchers; 

class CsvFetcher implements FetcherInterface
{
    /** @var string */
    protected $url;
    /** @var string */
    protected $separator;
    /** @var bool */
    protected $isDownloaded = false;
    /** @var array */
    protected $lines = array();
    /** @var array */
    protected $headers = array();

    /**
     * @param string $url
     * @param string $separator
     * @throws \InvalidArgumentException
     */
    public function __construct($url, $separator)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false)
            throw new \InvalidArgumentException('Invalid url to fetch csv from.');

        $this->url = $url;
        $this->separator = $separator;
    }

    /**
     * @return array
     */
    public function next()
    {
        try
        {
            if (! $this->isDownloaded)
                $this->download();
            if (empty($this->lines))
                return array();

            return $this->getNextLineAsArray();
        }
        catch (EndOfFetchStreamException $e)
        {
            return array();
        }
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        try
        {
            if (! $this->isDownloaded)
                $this->download();

            return $this->headers;
        }
        catch (EndOfFetchStreamException $e)
        {
            return array();
        }
    }

    protected function download()
    {
        $contents = file_get_contents($this->url);
        $contents = str_replace("\r\n", "\n", $contents);
        $this->lines = explode("\n", $contents);

        $this->isDownloaded = true;
        $this->setHeaders();
    }

    protected function setHeaders()
    {
        $this->headers = array_flip($this->getNextLineAsArray());
    }

    protected function getNextLineAsArray()
    {
        if (($line = array_shift($this->lines)) === null)
            throw new EndOfFetchStreamException();

        return str_getcsv($line, $this->separator);
    }
}