<?php

namespace App\Models;


use Symfony\Component\Yaml\Yaml;

class Infobox
{
    public $yaml;

    public $sections;

    public $html = "";

    public $color;

    public function __construct($yaml)
    {
        $this->yaml = $yaml;

        $this->sections = Yaml::parse($this->yaml);

        return $this;
    }

    public function renderHTML()
    {
        if ($this->yaml == null)
        {
            return null;
        }

        $this->html .= "<aside id=\"sidebar\" class=\"wiki-box sidebar\">";

        foreach ($this->sections as $title => $section)
        {
            if ($title == 'colour' || $title == 'color')
            {
                $this->color = $section;

                continue;
            }

            if (substr($title, 0, 1) == '_')
            {
                // Section refers to a template
                $neededTemplate = ltrim($title, '_');

                // First check default templates
                if (file_exists(resource_path('templates/'.$neededTemplate.'.mustache')))
                {
                    $template = file_get_contents(resource_path('templates/'.$neededTemplate.'.mustache'));
                    $section['_color'] = $this->color;

                    $this->addBlockToHTML($template, $section);
                }
                else
                {
                    // So, it's not a default template, lets check if it's a user template
                    if (file_exists(storage_path('templates/infoboxes/'.$neededTemplate.'.mustache')))
                    {
                        $template = file_get_contents(storage_path('templates/infoboxes/'.$neededTemplate.'.mustache'));
                        $section['_color'] = $this->color;

                        $this->addBlockToHTML($template, $section);
                    }
                    else
                    {
                        $this->html .= '<div style="color: red">Template '.$template.' not found.</div>';
                    }
                }
            }
            else
            {
                // Is not a template, use default
                $template = file_get_contents(resource_path('templates/default.mustache'));

                $variables = [
                    "header" => $title,
                    "_color" => $this->color,
                    "keys" => []
                ];

                foreach ($section as $key => $value)
                {
                    $variables['keys'][] = [
                        "key" => $key,
                        "value" => $value
                    ];
                }

                $this->addBlockToHTML($template, $variables);
            }
        }

        $this->html .= "</aside>";

        return $this->html;
    }

    public function addBlockToHTML($template, $variables)
    {
        $mustache = new \Mustache_Engine();

        $this->html .= $mustache->render($template, $variables);
    }
}