<?php

namespace GetCandy\Hub\Actions\Pricing;

use GetCandy\Models\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UpdateCustomerGroupPricing
{
    /**
     * Execute the action.
     *
     * @param  Model  $owner
     * @param  \Illuminate\Support\Collection  $groups
     * @return void
     */
    public function execute(Model $owner, Collection $groups)
    {
        $groupsArray = $groups->toArray();

        foreach ($groupsArray as $groupId => $prices) {
            $groupsArray[$groupId] = app(UpdatePrices::class)->execute(
                $owner,
                collect($prices)
            );
        }

        return collect($groupsArray);
    }
}
