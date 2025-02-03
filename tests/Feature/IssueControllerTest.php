<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class IssueControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'github_token' => 'fake-github-token',
            'github_nickname' => 'testuser',
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_shows_a_list_of_open_issues_assigned_to_the_user()
    {
        Http::fake([
            'https://api.github.com/search/issues*' => Http::response([
                'items' => [
                    [
                        'url' => 'https://api.github.com/repos/testuser/repo/issues/1',
                        'number' => 1,
                        'title' => 'Fix login bug',
                        'created_at' => now()->toISOString(),
                    ],
                    [
                        'url' => 'https://api.github.com/repos/testuser/repo/issues/2',
                        'number' => 2,
                        'title' => 'Update README',
                        'created_at' => now()->toISOString(),
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get(route('issues.index'));

        $response->assertStatus(200);
        $response->assertViewIs('issues.index');
        $response->assertViewHas('issues', function ($issues) {
            return count($issues) === 2 &&
                   $issues[0]['title'] === 'Fix login bug' &&
                   $issues[1]['title'] === 'Update README';
        });
    }

    /** @test */
    public function it_does_not_list_closed_issues()
    {
        Http::fake([
            'https://api.github.com/search/issues*' => Http::response([
                'items' => [], // Empty array because there are no open issues
            ], 200),
        ]);

        $response = $this->get(route('issues.index'));

        $response->assertStatus(200);
        $response->assertViewIs('issues.index');
        $response->assertViewHas('issues', function ($issues) {
            return count($issues) === 0;
        });
    }

    /** @test */
    public function it_shows_the_details_of_a_specific_issue()
    {
        $issueUrl = 'https://api.github.com/repos/testuser/repo/issues/1';

        Http::fake([
            $issueUrl => Http::response([
                'url' => $issueUrl,
                'number' => 1,
                'title' => 'Fix login bug',
                'body' => 'There is a critical bug in login.',
                'created_at' => now()->toISOString(),
            ], 200),
        ]);

        $response = $this->get(route('issues.show', ['url' => urlencode($issueUrl)]));

        $response->assertStatus(200);
        $response->assertViewIs('issues.show');
        $response->assertViewHas('issue', function ($issue) {
            return $issue['number'] === 1 &&
                   $issue['title'] === 'Fix login bug' &&
                   $issue['body'] === 'There is a critical bug in login.';
        });
    }

    /** @test */
    public function it_handles_non_existing_issues()
    {
        // Handle a missing issue (404 response)
        $issueUrl = 'https://api.github.com/repos/testuser/repo/issues/999';

        Http::fake([
            $issueUrl => Http::response(['message' => 'Not Found'], 404),
        ]);

        $response = $this->get(route('issues.show', ['url' => urlencode($issueUrl)]));

        $response->assertStatus(500);
    }
}
