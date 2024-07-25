<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Libs\Libs\WannadsCallback;

class WannadsTest extends TestCase
{
    public function test_receive_callback()
    {
        $postdata = [
            'subid' => null,
            'transId' => null,
            'reward' => null,
            'payout' => null,
            'signature' => null,
            'status' => null,
            'userIp' => null,
            'campaign_id' => null,
            'campaign_name' => null,
            'country' => null,
            'uuid' => null,
            'subId2' => null
        ];

        $callback = new WannadsCallback($postdata);

        $this->assertNotEmpty($callback->getJson());
    }
}
