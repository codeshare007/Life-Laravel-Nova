<?php

namespace Tests;

use App\User;
use Laravel\Passport\Passport;
use \Illuminate\Support\Fluent;
use Tests\Traits\CreatesApplication;
use \Illuminate\Database\Schema\Blueprint;
use \Illuminate\Database\SQLiteConnection;
use JMac\Testing\Traits\HttpTestAssertions;
use \Illuminate\Database\Schema\SQLiteBuilder;
use App\Services\CommentService\CommentService;
use App\Services\QuestionService\QuestionService;
use App\Services\CommentService\FakeCommentService;
use App\Services\QuestionService\FakeQuestionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;
    use HttpTestAssertions;

    /** @var User */
    protected $user;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->hotfixSqlite();
    }

    public function setUp()
    {
        parent::setUp();

        $this->app->singleton(QuestionService::class, FakeQuestionService::class);
        $this->app->singleton(CommentService::class, FakeCommentService::class);
    }

    public function signIn($userAttributes = []): User
    {
        $this->user = Passport::actingAs(factory(User::class)->create($userAttributes));

        return $this->user;
    }

    /**
     * Remove the error associated with running tests using sqlite when migrations contain dropForeign
     * https://github.com/laravel/framework/issues/25475.
     *
     * @return void
     */
    protected function hotfixSqlite()
    {
        \Illuminate\Database\Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
            return new class($connection, $database, $prefix, $config) extends SQLiteConnection {
                public function getSchemaBuilder()
                {
                    if ($this->schemaGrammar === null) {
                        $this->useDefaultSchemaGrammar();
                    }

                    return new class($this) extends SQLiteBuilder {
                        protected function createBlueprint($table, ?\Closure $callback = null)
                        {
                            return new class($table, $callback) extends Blueprint {
                                public function dropForeign($index)
                                {
                                    return new Fluent();
                                }
                            };
                        }
                    };
                }
            };
        });
    }
}
