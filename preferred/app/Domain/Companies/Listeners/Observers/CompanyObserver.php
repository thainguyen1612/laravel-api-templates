<?php

namespace Preferred\Domain\Companies\Listeners\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Neves\Events\Contracts\TransactionalEvent;
use Preferred\Domain\Companies\Entities\Company;
use Ramsey\Uuid\Uuid;

class CompanyObserver implements TransactionalEvent
{
    public function creating(Model $model)
    {
        $model->id = Uuid::uuid4();
    }

    public function updated(Company $company)
    {
        if ($company->is_active) {
            Cache::put($company->id, $company, 60);
        } else {
            Cache::forget($company->id);
        }

        Cache::tags('companies')->flush();
    }
}