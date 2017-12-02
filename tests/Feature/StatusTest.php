<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class StatusTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The root api must show only the system status.
     *
     * @test
     */
    public function testApplicationStatus()
    {
        $response = $this->json('GET', '/');
        $response->assertResponseStatus(200);
        $response->seeJsonContains([
            'status' => true,
            'message' => 'JegueStreaming up and running.'
        ]);
    }
}
