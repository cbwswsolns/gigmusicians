<?php

namespace App\Services;

use Illuminate\Http\Request;

class ResourceItemSorting
{
    /* Sorting Method for AJAX requests */

    /**
     * Perform a sorting update on a resource
     *
     * @param \Illuminate\Http\Request                $request [the current request instance]
     * @param Illuminate\Database\Eloquent\Collection $items   [items for sorting]
     *
     * @return void
     */
    public function sortUpdate(Request $request, $items)
    {
        // loop through database items
        foreach ($items as $item) {
            // loop though re-ordered items extracted from the request
            foreach ($request->items as $itemFrontEnd) {
                if ($itemFrontEnd['id'] == $item->id) {
                    // Update item sort order in database accordingly
                    $item->update(['sortorder' => $itemFrontEnd['sortorder']]);
                }
            }
        }
    }
}
