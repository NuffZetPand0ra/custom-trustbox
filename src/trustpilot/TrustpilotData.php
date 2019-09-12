<?php

namespace inkpro\trustpilot;
use Carbon\Carbon;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use Psr\Cache\CacheItemPoolInterface;

class TrustpilotData{

    public $total_number_of_reviews;
    public $sorted_number_of_reviews;
    public $stars;
    public $trust_score;
    public $stars_string;
    public $last_fetched;
    public $display_name;
    public $profile_url;
    public $reviews;


    private static $cache_pool;


    static function getData(){
        // $instance = self::loadLive();
        // $instance->saveToCache();
        $item = self::getCachePool()->getItem("trustpilot_reviews");
        if(!$item->isHit()){
            $item->set(self::loadLive());
            $item->expiresAfter(300);
        }
        return $item->get();
    }
    static function loadCached(){
        if(!file_exists(self::$cache_path)) return false;
        self::$instance = unserialize(file_get_contents(self::$cache_path));
        return self::$instance;
    }
    static function loadLive(){
        $businessUnitId = $_ENV['TP_BUSINESS_ID'];
        $review_count = 15;
        $request_data = [
            "businessUnitId"=>$businessUnitId,
            "includeReviews"=>"true",
            "reviewsPerPage"=>15,
            "reviewStars"=>5,
            "locale"=>"DA-dk"
        ];
        // $url = "https://widget.trustpilot.com/base-data?".http_build_query($request_data);
        $url = "https://widget.trustpilot.com/trustbox-data/53aa8912dec7e10d38f59f36?".http_build_query($request_data);
        $data = json_decode(file_get_contents($url));
        $instance = new TrustpilotData;
        $review_numbers = $data->businessUnit->numberOfReviews;
        $instance->total_number_of_reviews = $review_numbers->total;
        $instance->sorted_number_of_reviews = [
            "1"=>$review_numbers->oneStar,
            "2"=>$review_numbers->twoStars,
            "3"=>$review_numbers->threeStars,
            "4"=>$review_numbers->fourStars,
            "5"=>$review_numbers->fiveStars
        ];
        $instance->last_fetched = new Carbon();
        $instance->stars = $data->businessUnit->stars;
        $instance->trust_score = $data->businessUnit->trustScore;
        $instance->profile_url = $data->links->profileUrl;
        $instance->display_name = $data->businessUnit->displayName;
        foreach($data->reviews as $this_review){
            $review = new TrustpilotReview;
            $review->stars = $this_review->stars;
            $review->created_at = new Carbon($this_review->createdAt);
            $review->title = $this_review->reviewUrl;
            $review->text = $this_review->text;
            $review->review_url = $this_review->reviewUrl;
            $review->consumer_name = $this_review->consumer->displayName;
            $instance->reviews[] = $review;
        }
        // $instance->saveToCache();
        return $instance;
    }
    public function saveToCache(){
        $serialized = serialize($this);
        file_put_contents(TrustpilotData::$cache_path, $serialized);
    }
    private static function getCachePool()
    {
        if(!self::$cache_pool){
            $filesystemAdapter = new Local(__DIR__.'/../');
            $filesystem = new Filesystem($filesystemAdapter);
            self::$cache_pool = new FilesystemCachePool($filesystem);
            self::$cache_pool->setFolder('cache');
        }
        return self::$cache_pool;
    }
}