<?php namespace Kutkabouter\Fetchers;

use Kutkabouter\Db;
use OAuthConsumer;
use OAuthRequest;
use OAuthSignatureMethod_HMAC_SHA1;
use OAuthToken;

require_once __DIR__ . '/../../Libraries/Yelp/OAuth.php';

/**
 * Class YelpFetcher
 *
 * This class is responsible for fetching reviews from Yelp
 */
class YelpFetcher
{
    protected $token;
    protected $consumer;

    public $search;

    public function __construct()
    {
        $consumerKey = 'BwBk7dOT1G8kUZw_XkewSA';
        $consumerSecret = 'F5Lg0baooBRnaRsd4JArGwBX4Ik';
        $token = 't5Mjvyi0mMiIqkQ8h1Se-r7619TqyglX';
        $tokenSecret = 'IWm5FztazTGwpPW2sHI1VQCwgsk';

        $this->consumer = new OAuthConsumer($consumerKey, $consumerSecret);
        $this->token = new OAuthToken($token, $tokenSecret);
    }

    public function fetch($search)
    {
        $this->search = $search;

        $business = $this->fetchBusiness($search);
        if (! empty($business->businesses[0]))
        {
            $businessId = $business->businesses[0]->id;
            $this->fetchReviews($businessId);
        }
        else
        {
            echo 'No business found for '.$search.PHP_EOL;
        }
    }

    protected function fetchBusiness($search)
    {
        $url = 'http://api.yelp.com/v2/search?term=' . urlencode($search) . '&location=Amsterdam';

        return $this->request($url);
    }

    protected function request($url)
    {
        // Yelp uses HMAC SHA1 encoding
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

        // Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
        $oauthrequest = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, 'GET', $url);

        // Sign the request
        $oauthrequest->sign_request($signature_method, $this->consumer, $this->token);

        // Get the signed URL
        $signed_url = $oauthrequest->to_url();

        // Send Yelp API Call
        $ch = curl_init($signed_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch); // Yelp response
        curl_close($ch);

        // Handle Yelp response data
        $response = json_decode($data);

        return $response;
    }

    protected function fetchReviews($businessId)
    {
        $url = 'http://www.yelp.nl/biz/' . $businessId . '?sort_by=rating_asc';
        $contents = file_get_contents($url);
        $pos = 0;
        $count = 0;
        while(($pos = strpos($contents, '<p class="review_comment', $pos + 1)) !== false)
        {
            if (++$count % 3 == 0)
                break;

            // only store reviews with bad score
            $stars = $this->getStars($contents, $pos);
            if ($stars > 2)
                break;

            $lineEnd = strpos($contents, "\n", $pos);
            $line = substr($contents, $pos, $lineEnd - $pos);
            $review = $stars . ' sterren: ' . trim(strip_tags($line));
            $this->saveReview($review);
            echo "Saved review for $businessId with $stars stars".PHP_EOL;
        }
    }

    protected function getStars($contents, $pos)
    {
        $contents = substr($contents, 1, $pos);
        $search = '<i class="star-img stars_';
        $starpos = strrpos($contents, $search);
        return substr($contents, $starpos + strlen($search), 1);
    }

    public function saveReview($review)
    {
        $db = new Db();

        $stmt = $db->prepare("select * from reviews where name = ?");
        $stmt->bind_param('s', $this->search);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array();
        if (empty($row))
            throw new \Exception('Cannot find business with name "'.htmlentities($this->search).'"');

        // find first empty column
        for ($i=1; $i<=3; $i++)
        {
            if (empty($row['review'.$i]))
            {
                $column = 'review'.$i;
                break;
            }
        }

        if (isset($column))
        {
            $db->query("update reviews set $column = '$review' where id = ".$row['id']);
        }
        else
        {
            echo 'All review fields already filled'.PHP_EOL;
        }

        $result->close();
    }
} 