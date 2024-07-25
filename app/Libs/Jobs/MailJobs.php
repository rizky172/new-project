<?php

namespace App\Libs\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Libs\Repository\AccessControl;
use App\Libs\Repository\SalarySlip;
use App\Libs\LibKernel;

use App\Invoice as Model;
use App\User;
use App\Person;
use App\LogM;
use App\Category;

class MailJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    private $userId;

    public function __construct($id, $userId)
    {
        $this->id =  $id;
        $this->userId =  $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // throw new \Exception("Error");
        LibKernel::smptFromDb();

        $model = Model::find($this->getId());
        $repo = new SalarySlip($model);
        $repo->setAccessControl(new AccessControl(User::find($this->getUserId())));
        $repo->email();
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        // Send user notification of failure, etc...
        $model = Model::find($this->getId());

        //need to count remider + 1
        if ($model->reminder + 1 == 5) {
            $pattern = 'Pengirim: %s. Email pemblokiran gagal terkirim';
            $notes = sprintf($pattern, User::find($this->getUserId())->name);
        } else {
            $pattern = 'Pengirim: %s. Email pemberitahuan ke %s gagal terkirim';
            $notes = sprintf($pattern, User::find($this->getUserId())->name, Person::find($model->person_id)->name);
        }

        $logCategoryId = Category::where('name', 'email')
                                  ->where('group_by', 'log')
                                  ->first()
                                  ->id;

        if(!empty($notes)) {
            LogM::create([
                'fk_id' => $model->id,
                'log_category_id' => $logCategoryId,
                'table_name' => $model->getTable(),
                'notes' => $notes,
                'created' => new \DateTime
            ]);
        }
    }
}
