<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * get existing tags optons for ui autocomplete usage
     *
     * @return void
     */
    public function getTagOptions() {
        $tags = DB::table('tags')->select('name')->orderBy('name', 'asc')->get()->toArray();
        $tags = array_map(fn ($value) => ['text' => Str::title(json_decode($value->name)->en)], $tags); // wrap for frontend vuejs-tags usage
        return $tags;
    }
}
