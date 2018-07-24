<?php

namespace inkpro\trustpilot;
use Carbon\Carbon;

class TrustpilotData{

    public $total_number_of_reviews;
    public $sorted_number_of_reviews;
    public $stars;
    public $trust_score;
    public $stars_string;
    public $last_fetched;
    public $display_name;
    public $profile_url;


    private static $instance;
    private static $cache_path = __DIR__."/../../cachedData.php";


    static function getData(){
        // $instance = self::loadLive();
        // $instance->saveToCache();
        if($instance = self::loadCached()){
            $five_minutes_ago = (new Carbon())->subMinutes(5);
            if($instance->last_fetched < $five_minutes_ago){
                $instance = self::loadLive();
            }
        }else{
            $instance = self::loadLive();
        }
        return $instance;
    }
    static function loadCached(){
        if(!file_exists(self::$cache_path)) return false;
        self::$instance = unserialize(file_get_contents(self::$cache_path));
        return self::$instance;
    }
    static function loadLive(){
        $businessUnitId = $_ENV['TP_BUSINESS_ID'];
        $url = "https://widget.trustpilot.com/base-data?businessUnitId=".$businessUnitId;
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
        $instance->saveToCache();
        return self::$instance = $instance;
    }
    public function saveToCache(){
        $serialized = serialize($this);
        file_put_contents(TrustpilotData::$cache_path, $serialized);
    }
}