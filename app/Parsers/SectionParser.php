<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03/07/2017
 * Time: 05:19
 */

namespace App\Parsers;


use App\Models\Section;

class SectionParser
{
    protected $firstSectionRegex = '/^(?s)(.+?)(?:#|$)/';

    protected $sectionRegex = '/(#+)(.+)(?:[^#]+)/';

    public $string;

    public $sections;

    public function __construct($version)
    {
        $this->string = $version;

        $this->sections = collect([]);

        $this->setSections($this->string);

        $this->setFirstSection($this->string);

        return $this;
    }

    protected function setFirstSection($string)
    {
        preg_match($this->firstSectionRegex, $string, $match);

        if ($match != null)
        {
            $firstSection = new Section(null, $match[1], 0);

            $this->sections->prepend($firstSection);
        }
    }

    protected function setSections($string)
    {
        preg_match_all($this->sectionRegex, $string, $matches, PREG_SET_ORDER);

        if ($matches != null)
        {
            foreach ($matches as $match)
            {
                $title = trim($match[2]);
                $md = $match[0];
                $level = strlen($match[1]);

                $previousSection = $this->sections->last();
                $newSection = new Section($title, $md, $level);

                if ($previousSection !== null && $previousSection->level < $newSection->level)
                {
                    $previousSection->addChild($newSection);
                }
                else
                {
                    $this->sections->push($newSection);
                }
            }
        }
    }
}