<?php

namespace App\Libs\Libs;

use Illuminate\Support\Collection;

/**
 * Help to manipulate tree structure
 */
class WannadsCallback
{
    private $subid;
    private $transId;
    private $reward;
    private $payout;
    private $signature;
    private $status;
    private $userIp;
    private $campaign_id;
    private $campaign_name;
    private $country;
    private $uuid;
    private $subId2;
    private $data;

    public function __construct($postdata)
    {
        $this->data = $postdata;

        $this->subid = $postdata['subid'];
        $this->transId = $postdata['transId'];
        $this->reward = $postdata['reward'];
        $this->payout = $postdata['payout'];
        $this->signature = $postdata['signature'];
        $this->status = $postdata['status'];
        $this->userIp = $postdata['userIp'];
        $this->campaign_id = $postdata['campaign_id'];
        $this->campaign_name = $postdata['campaign_name'];
        $this->country = $postdata['country'];
        $this->uuid = $postdata['uuid'];
        $this->subId2 = $postdata['subId2'];
    }

    public function getJson()
    {
        return json_encode($this->data);
    }

    // Only for debugging
    public static function getUrl($api, $userId)
    {
        return "https://surveywall.wannads.com?apiKey={$api}&userId={$userId}";
    }
}
