<?php

use App\Category;
use App\User;
use App\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UploadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var User
     */
    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * Given a user trying to create a new video, let it be created.
     * The playable is uploaded later.
     *
     * @test
     */
    public function testVideoCreation()
    {
        $category = factory(Category::class)->create();
        $content = [
            'name' => 'Trollei minha mãe com amoeba',
            'tags' => [
                'trollagem',
                'amoeba',
                'treta news',
                'trollagem',
            ],
            'description' => 'Hoje eu trollei minha mãe com amoeba. It was cool.',
            'category_id' => $category->id,
        ];
        $expectedReturn = [
            'success' => true,
        ];
        $response = $this->json('POST', '/videos', $content, [
            'X-token' => $this->user->remember_token,
        ]);
        $response->assertResponseStatus(201);
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
        $category = factory(Category::class)->create();
        $content = [
            'name' => 'Tr',
            'tags' => [
                'trollagem',
            ],
            'description' => 'Esqueci o ponto e vírgula...',
            'category_id' => $category->id,
        ];
        $expectedReturn = [
            'success' => false,
            'error' => 'INVALID_STRING_LENGTH',
        ];
        $response = $this->json('POST', '/videos', $content, [
            'X-token' => $this->user->remember_token,
        ]);
        $response->assertResponseStatus(400);
        $response->seeJsonContains($expectedReturn);
    }

    /**
     * Given that a user may put an invalid category
     * id, it must be refused.
     *
     * @test
     */
    public function testInvalidCategory()
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
            'category_id' => 999,
        ];
        $expectedReturn = [
            'success' => false,
            'error' => 'CATEGORY_NOT_FOUND',
        ];
        $response = $this->json('POST', '/videos', $content, [
            'X-token' => $user->remember_token,
        ]);
        $response->assertResponseStatus(404);
        $response->seeJsonContains($expectedReturn);
    }

    /**
     * Given that the user must be authenticated to  create or
     * upload a video, refuse any try of uploading without X-token.
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
            'error' => 'UNAUTHORIZED_USER',
        ];
        $response = $this->json('POST', '/videos', $content);
        $response->assertResponseStatus(403);
        $response->seeJsonContains($expectedReturn);
        //
        $video = factory(Video::class)->create([
            'playable' => null,
            'owner' => $this->user->username,
        ]);
        Storage::fake('videos');
        $response = $this->json('POST', "/videos/{$video->id}", [
            'video' => UploadedFile::fake()
                ->create('bear.mp4', '1024'),
        ], [
            'X-token' => 'notmyuniquetoken',
        ]);
        $response->assertResponseStatus(403);
        $response->seeJsonContains([
            'success' => false,
            'error' => 'UNAUTHORIZED_USER',
        ]);
    }

    /**
     * Given that a user may try to upload a video
     * without a single tag, enforce him to put at least one.
     *
     * @test
     */
    public function testRequiredTags()
    {
        $category = factory(Category::class)->create();
        $content = [
            'name' => 'Trollei minha mãe com amoeba',
            'description' => 'Hoje eu trollei minha mãe com amoeba. It was cool.',
            'category_id' => $category->id,
        ];
        $expectedReturn = [
            'success' => false,
            'error' => 'REQUIRED_PARAMETER',
        ];
        $response = $this->json('POST', '/videos', $content, [
            'X-token' => $this->user->remember_token,
        ]);
        $response->assertResponseStatus(400);
        $response->seeJsonContains($expectedReturn);
    }

    /**
     * Given an user trying to upload a video to the
     * storage, assert it get success.
     *
     * @test
     */
    public function testVideoUpload()
    {
        // TODO: Faked UploadedFile always returns invalid file instead of invalid extension.
        $this->markTestSkipped();
        $video = factory(Video::class)->create([
            'owner' => $this->user->username,
            'playable' => null,
        ]);
        Storage::fake('public');
        $response = $this->json('POST', "/videos/{$video->id}", [
            'video' => UploadedFile::fake()
                ->create('bear.mp4', 1024),
        ], [
            'X-token' => $this->user->remember_token,
        ]);
        $response->assertResponseStatus(200);
        $response->seeJsonContains([
            'success' => true,
        ]);
        Storage::disk('public')
            ->assertExists("{$video->id}.mp4");
    }

    /**
     * Given an user trying to upload to an unexistent video,
     * deny him.
     *
     * @test
     */
    public function testVideoNotFound()
    {
        $response = $this->json('POST', '/videos/65536', [], [
            'X-token' => $this->user->remember_token,
        ]);
        $response->assertResponseStatus(404);
        $response->seeJsonContains([
            'success' => false,
            'error' => 'VIDEO_NOT_FOUND',
        ]);
    }

    /**
     * Given that the user may upload a file that is null
     * or with size equal to zero, throw the appropriate exception.
     *
     * @test
     */
    public function testUploadWithoutFile()
    {
        $video = factory(Video::class)->create([
            'owner' => $this->user->username,
            'playable' => null
        ]);
        $response = $this->json('POST', "/videos/{$video->id}", [], [
            'X-token' => $this->user->remember_token,
        ]);
        $response->assertResponseStatus(400);
        $response->seeJsonContains([
            'success' => false,
            'error' => 'REQUIRED_PARAMETER',
        ]);
    }

    /**
     * Given that the file must be greater than zero bytes, refuse
     * any try of uploading empty file.
     *
     * @test
     */
    public function testUploadEmptyFile()
    {
        $video = factory(Video::class)->create([
            'owner' => $this->user->username,
            'playable' => null
        ]);
        Storage::fake('videos');
        $response = $this->json('POST', "/videos/{$video->id}", [
            'video' => UploadedFile::fake()
                ->create('bear.mp4', 0),
        ], [
            'X-token' => $this->user->remember_token,
        ]);
        $response->assertResponseStatus(400);
        $response->seeJsonContains([
            'success' => false,
            'error' => 'REQUIRED_PARAMETER',
        ]);
    }

    /**
     * Given that the file must be mp4 file, refuse
     * any try of uploading mov, jpg, etc.
     *
     * @test
     */
    public function testWrongExtension()
    {
        // TODO: Faked UploadedFile always returns invalid file instead of invalid extension.
        $this->markTestSkipped();
        $video = factory(Video::class)->create([
            'owner' => $this->user->username,
            'playable' => null
        ]);
        Storage::fake('videos');
        $response = $this->json('POST', "/videos/{$video->id}", [
            'video' => UploadedFile::fake()
                ->image('bear', '100', '100'),
        ], [
            'X-token' => $this->user->remember_token,
        ]);
        $response->assertResponseStatus(400);
        $response->seeJsonContains([
            'success' => false,
            'error' => 'INVALID_EXTENSION',
        ]);
    }

    /**
     * Given an user trying to upload a video that
     * has been already uploaded, deny him.
     *
     * @test
     */
    public function testVideoAlreadyUploaded()
    {
        $video = factory(Video::class)->create([
            'owner' => $this->user->username
        ]);
        Storage::fake('videos');
        $response = $this->json('POST', "/videos/{$video->id}", [
            'video' => UploadedFile::fake()
                ->create('bear.mp4', 1024),
        ], [
            'X-token' => $this->user->remember_token,
        ]);
        $response->assertResponseStatus(409);
        $response->seeJsonContains([
            'success' => false,
            'error' => 'VIDEO_ALREADY_UPLOADED',
        ]);
    }
}
