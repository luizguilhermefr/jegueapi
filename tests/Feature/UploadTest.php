<?php

use App\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UploadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Given a user trying to create a new video, let it be created.
     * The playable is uploaded later.
     *
     * @test
     */
    public function testVideoCreation()
    {
        $user = factory(User::class)->create();
        $content = [
            'name' => 'Trollei minha mãe com amoeba',
            'tags' => [
                'trollagem',
                'amoeba',
                'treta news',
            ],
            'description' => 'Hoje eu trollei minha mãe com amoeba. It was cool.',
            'category_id' => 3,
        ];
        $expectedReturn = [
            'success' => true
        ];
        $response = $this->json('POST', '/videos', $content, [
            'X-token' => $user->remember_token
        ]);
        $response->assertResponseStatus(200);
        $response->seeJsonContains($expectedReturn);
    }

    /**
     * Given that the minimum video name length is three,
     * refuse any try of creating something different than that.
     *
     * @test
     */
    public function testTooSmallResponse()
    {
        $user = factory(User::class)->create();
        $content = [
            'name' => 'Tro',
            'tags' => [
                'trollagem'
            ],
            'description' => 'Esqueci o ponto e vírgula...',
            'category_id' => 3,
        ];
        $expectedReturn = [
            'success' => false,
            'error' => 'INVALID_STRING_LENGTH'
        ];
        $response = $this->json('POST', '/videos', $content, [
            'X-token' => $user->remember_token
        ]);
        $response->assertResponseStatus(400);
        $response->seeJsonContains($expectedReturn);
    }

    /**
     * Given that the user must be authenticated to upload a vide,
     * refuse any try of uploading without X-token.
     *
     * @test
     */
    public function testUnauthorization()
    {
        $content = [
            'name' => 'Trollei minha mãe com amoeba',
            'tags' => [
                'trollagem',
                'amoeba',
                'treta news',
            ],
            'description' => 'Hoje eu trollei minha mãe com amoeba. It was cool.',
            'category_id' => 3,
        ];
        $expectedReturn = [
            'success' => false,
            'error' => 'UNAUTHORIZED_USER'
        ];
        $response = $this->json('POST', '/videos', $content);
        $response->assertResponseStatus(403);
        $response->seeJsonContains($expectedReturn);
    }
}
