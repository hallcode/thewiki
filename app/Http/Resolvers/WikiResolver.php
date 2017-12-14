<?php


namespace App\Http\Resolvers;


use App\Models\Page;

class WikiResolver
{
    /**
     * These are protected because they are not routed normally
     * so, they can't be created / updated / deleted in the same
     * way as other pages.
     **/
    public static $protectedNamespaces = [
        'special',
        'infobox',
        'talk',
        'data',
        'attachments'
    ];

    public $namespace;
    public $title;

    public function __construct($reference = null)
    {
        if ($reference !== null)
        {
            $this->decodeReference($reference);

            if ($this->isNamespaceProtected())
            {
                throw new \Exception("You are not allowed to use a restricted namespace");
            }
        }

        return null;
    }

    /**
     * References should in formatted as
     * namespace:title
     * @param $reference
     */
    public function decodeReference($reference)
    {
        if (str_contains($reference, ':'))
        {
            $this->namespace = str_before($reference,':');
            $this->title = str_after($reference,':');
        }
        else
        {
            $this->namespace = null;
            $this->title = $reference;
        }
    }

    public function returnPageObject()
    {
        return Page::findByTitle($this->title, $this->namespace);
    }

    public static function isProtected($namespace)
    {
        return str_contains(strtolower($namespace), self::$protectedNamespaces);
    }

    public function isNamespaceProtected()
    {
        return self::isProtected($this->namespace);
    }
}