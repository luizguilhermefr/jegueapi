<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class UploadTest extends TestCase
{
    use DatabaseTransactions;

//{
//"name": "Trollei minha mãe com amoeba",
//"tags": [
//"trollagem",
//"amoeba",
//"treta news"
//],
//"description": "Hoje eu trollei minha mãe com amoeba. It was cool.",
//"category_id": 3
//}
    public function testVideoCreation()
    {
        $content = [
            'name' => 'Trollei minha mãe com amoeba',
            'tags' => [
                'trollagem',
                'amoeba',
                'treta news'
            ],
            'description' => 'Hoje eu trollei minha mãe com amoeba. It was cool.',
            'category_id' => 3
        ];
    }
}
