<?php

namespace App\Models;


use App\Parsers\WikiParser;

class Section
{
    public $title;

    public $markdown;

    public $level;

    public $children;

    public function __construct($title, $markdown, $level = 1)
    {
        $this->children = collect([]);

        $this->title = trim($title);

        $this->markdown = $markdown;

        $this->level = $level;

        return $this;
    }

    public function addChild(Section $section)
    {
        $lastChild = $this->children->last();

        if ($lastChild != null && $lastChild->level < $section->level)
        {
            $lastChild->addChild($section);
        }
        else
        {
            $this->children->push($section);
        }

        return $this;
    }

    public function renderHTML()
    {
        $wikiParser = new WikiParser();
        $html = $wikiParser->parse($this->markdown);

        if ($this->children->count() !== 0)
        {
            foreach ($this->children as $child)
            {
                $html .= $child->renderHTML();
            }
        }

        return $html;
    }

}