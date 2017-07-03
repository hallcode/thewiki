<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WikiController extends Controller
{
    protected $specialPages = [
        'home' => [ HomeController::class, 'index' ],
        'all' => [ PageController::class, 'index'],
        'needed' => [ PageController::class, 'needed' ],
        'random' => [ PageController::class, 'random' ],
        'recent' => [ PageController::class, 'recent'],
    ];

    /**
     * Takes all the URLs in the format /wiki/{namespace}:{reference}
     * Routes missing the namespace (i.e. /wiki/{reference}) are assumed to be pages.
     *
     * The namespace will usually be a model, or the keyword Special.
     *
     * @param $namespace
     * @param $reference
     *
     * @return mixed
     */
    public function dispatcher($namespace, $reference)
    {
        $namespace = strtolower($namespace);

        switch ($namespace)
        {
            case "special":
                return $this->specialPage($reference);
                break;
        }

        abort(404);
    }

    /**
     * Calls the correct Controller method, mapped by the $specialPages property or gives 404 error.
     *
     * @param $namespace
     * @param $reference
     *
     * @return mixed
     */
    public function specialPage($reference)
    {
        $reference = strtolower($reference);

        if (isset($this->specialPages[$reference]))
        {
            $class_name = $this->specialPages[$reference][0];
            $method = $this->specialPages[$reference][1];

            $class = new $class_name();

            return $class->$method();
        }

        abort(404);
    }
}
