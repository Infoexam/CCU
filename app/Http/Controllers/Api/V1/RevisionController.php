<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Venturecraft\Revisionable\Revision;

class RevisionController extends Controller
{
    /**
     * Get revisions.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        $revisions = Revision::latest()->paginate();

        foreach ($revisions->items() as $revision) {
            $revision->setAttribute('user', $revision->userResponsible());
        }

        return $revisions;
    }
}
