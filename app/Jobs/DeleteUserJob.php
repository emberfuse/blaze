<?php

namespace App\Jobs;

use Throwable;
use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Contracts\Auth\DeletesUsers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteUserJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Instance of user requested to be deleted.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\User
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param \App\Contracts\Auth\DeletesUsers $deletor
     *
     * @return void
     */
    public function handle(DeletesUsers $deletor)
    {
        try {
            $deletor->delete($this->user);
        } catch (Throwable $e) {
            app('log')->error($e->getMessage(), ['user' => $this->user]);

            throw $e;
        }
    }
}
