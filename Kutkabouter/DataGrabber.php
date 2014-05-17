<?php namespace Kutkabouter;

use Kutkabouter\Fetchers\CsvFetcher;
use Kutkabouter\Db;

/**
 * Class DataGrabber
 *
 * This class is responsible for grabbing all data
 */
class DataGrabber
{
    protected $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function grabAll()
    {
        $this->grabEtenEnDrinken();
    }

    public function grabEtenEnDrinken()
    {
        $fetcher = new CsvFetcher('http://www.amsterdamopendata.nl/files/EtenDrinken.csv', ';');

        $headers = array_flip($fetcher->getHeaders());

        while ($row = $fetcher->next())
        {
            if (empty($row[$headers['Longitude']]) || empty($row[$headers['Latitude']]))
                continue;

            $row = array(
                'longtitude' => $this->convertToNumber($row[$headers['Longitude']]),
                'latitude' => $this->convertToNumber($row[$headers['Latitude']]),
                'name' => ! empty($row[$headers['Title']]) ? $row[$headers['Title']] : '',
            );

            $stmt = $this->db->prepare("insert into reviews (longtitude, latitude, name) values (?, ?, ?)");
            $stmt->bind_param('dds', $row['longtitude'], $row['latitude'], $row['name']);
            $stmt->execute();
        }
    }

    protected function convertToNumber($number)
    {
        $parts = explode(',', $number);
        return $parts[0] . '.' . substr($parts[1], 0, 3);
    }
} 