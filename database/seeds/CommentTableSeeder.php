<?php

use App\Models\StaffingCompany\Comment;
use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    public function run()
    {
        Comment::create(['comment' => 'Heftruckchauffeur € 35,50 p/u']);

        Comment::create(['comment' => 'Aanpikkelateur € 28,50 p/u']);

        Comment::create(['comment' => 'Aanpikkelateur € 30,- p/u']);

        Comment::create(['comment' => 'Aanpikkelateur € 35,- p/u']);

        Comment::create(['comment' => 'Afvalagent € 28,50 p/u']);
    }
}
