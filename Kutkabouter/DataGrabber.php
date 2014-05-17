<?php namespace Kutkabouter;

use Kutkabouter\Fetchers\GeoFetcher;
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
        $this->grabHotels();
    }

    public function grabEtenEnDrinken()
    {
        $fetcher = new CsvFetcher('http://www.amsterdamopendata.nl/files/EtenDrinken.csv', ';');

        $headers = array_flip($fetcher->getHeaders());

        $count = 0;
        while ($row = $fetcher->next())
        {
            if (empty($row[$headers['Longitude']]) || empty($row[$headers['Latitude']]))
                continue;

            $row = array(
                'longtitude' => $this->convertToNumber($row[$headers['Longitude']]),
                'latitude' => $this->convertToNumber($row[$headers['Latitude']]),
                'name' => ! empty($row[$headers['Title']]) ? $row[$headers['Title']] : '',
            );

            $stmt = $this->db->prepare("insert into reviews (longtitude, latitude, name, category) values (?, ?, ?, ?)");
            $stmt->bind_param('ddss', $row['longtitude'], $row['latitude'], $row['name'], 'Bar/Restaurant');
            $stmt->execute();

            $count++;
            echo 'added restaurant '.$row['name'].PHP_EOL;
        }

        echo 'Finished importing '.$count.' restaurants'.PHP_EOL.PHP_EOL;
    }

    public function grabHotels()
    {
        $fetcher = new CsvFetcher('http://www.amsterdamopendata.nl/documents/10180/25203/Lijst%20hotels%20MRA%202012.csv', ';');
        $headers = array_flip($fetcher->getHeaders());

        $gf = new GeoFetcher();

        $count = 0;
        while ($row = $fetcher->next())
        {
            if ($row[$headers['gemeente']] != 'AMSTERDAM')
                continue;

            $name = $row[$headers['hotel naam in 2012']];
            $postcode = $row[$headers['postcode']];
            $geo = $gf->fetch($postcode);
            $long = $this->convertToNumber($geo['longtitude']);
            $lat = $this->convertToNumber($geo['latitude']);

            $stars = $row[$headers['sterklasse NHC 2012']];
            $review1 = ($stars <= 1) ? 'This cheap excuse for a hotel has only '.$stars.' stars' : null;

            $stmt = $this->db->prepare("insert into reviews (longtitude, latitude, name, category, review1) values (?, ?, ?, ?, ?)");
            $stmt->bind_param('ddsss', $long, $lat, $name, 'Hotel', $review1);
            $stmt->execute();

            $count++;
            echo 'added hotel '.$name.PHP_EOL;
        }

        echo 'Finished importing '.$count.' hotels'.PHP_EOL.PHP_EOL;
    }

    protected function convertToNumber($number)
    {
        $number = str_replace(',', '.', $number);
        return round((float) $number, 3);
    }
} 