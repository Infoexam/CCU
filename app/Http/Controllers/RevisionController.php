<?php

namespace App\Http\Controllers;

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
        $revisions = Revision::latest()->paginate(10);

        foreach ($revisions->items() as $revision) {
            $revision->setAttribute('user', $revision->userResponsible());
        }

        return $revisions;
    }
}
