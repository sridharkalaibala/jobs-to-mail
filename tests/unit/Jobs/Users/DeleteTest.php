<?php namespace JobApis\JobsToMail\Tests\Unit\Jobs\Users;

use JobApis\JobsToMail\Http\Messages\FlashMessage;
use JobApis\JobsToMail\Jobs\Users\Delete;
use JobApis\JobsToMail\Tests\TestCase;
use Mockery as m;

class DeleteTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->userId = $this->faker->uuid();
        $this->job = new Delete($this->userId);
    }

    public function testItCanHandleIfUserConfirmed()
    {
        $userRepository = m::mock('JobApis\JobsToMail\Repositories\Contracts\UserRepositoryInterface');

        $userRepository->shouldReceive('delete')
            ->with($this->userId)
            ->once()
            ->andReturn(true);

        $result = $this->job->handle($userRepository);

        $this->assertEquals(FlashMessage::class, get_class($result));
        $this->assertEquals('alert-success', $result->type);
    }

    public function testItCanHandleIfExceptionThrown()
    {
        $userRepository = m::mock('JobApis\JobsToMail\Repositories\Contracts\UserRepositoryInterface');

        $userRepository->shouldReceive('delete')
            ->with($this->userId)
            ->once()
            ->andReturn(false);

        $result = $this->job->handle($userRepository);

        $this->assertEquals(FlashMessage::class, get_class($result));
        $this->assertEquals('alert-danger', $result->type);
    }
}
