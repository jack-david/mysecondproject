<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Core\Models\Team;
use App\Core\Models\User;
use App\Core\Models\Office;
use App\Core\Models\Project;

class HomePageTest extends TestCase
{
    /** @test */
    public function user_can_see_projects_teams_and_offices_in_home_page()
    {
        $project = factory(Project::class)->create(['office_id' => null, 'team_id' => null]);
        $team = factory(Team::class)->create(['office_id' => null]);
        $office = factory(Office::class)->create();
        $project->members()->attach($this->user->id);
        $team->members()->attach($this->user->id);
        $office->members()->attach($this->user->id);

        $this->actingAs($this->user)
            ->get('/')
            ->assertStatus(200)
            ->assertSee($project->name)
            ->assertSee($team->name)
            ->assertSee($office->name);
    }

    /** @test */
    public function user_can_see_members_of_projects_teams()
    {
        $project = factory(Project::class)->create(['office_id' => null, 'team_id' => null]);
        $project->members()->attach($this->user->id);
        $users = factory(User::class, 5)->create();
        $project->members()->attach($users->map(function ($user) {
            return $user->id;
        }));
        $this->actingAs($this->user)
            ->get('/')
            ->assertSee($users[0]->name)
            ->assertSee($users[1]->name)
            ->assertSee($users[2]->name);
    }
}
