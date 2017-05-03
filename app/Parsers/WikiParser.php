<?php

namespace App\Parsers;

use App\Models\Page;

class WikiParser extends \Parsedown
{
    public function __construct()
    {
        // Escape {{ ... }} - Vue JS crashes if they are rendered in the page.
        $this->InlineTypes['{'][] = 'EscapeVar';
        $this->inlineMarkerList .= '{';

        // InterLinks
        $this->InlineTypes['['][] = 'InterLink';
        $this->inlineMarkerList .= '[';
    }

    /**
     * Escape Vue JS variables.
     *
     * @param $excerpt
     *
     * @return array
     */
    public function inlineEscapeVar($excerpt)
    {
        if (preg_match('/\{\{(.+)\}\}/', $excerpt['text'], $matches))
        {
            return [
                'extent' => strlen($matches[0]),
                'element' => [
                    'name' => 'span',
                    'text' => $matches[1],
                ],
            ];
        }
    }

    /**
     * Generate InterLinks
     *
     * @param $excerpt
     *
     * @return array
     */
    public function inlineInterLink($excerpt)
    {
        if (preg_match('/\[\[(.+?)\]\]/', $excerpt['text'], $matches))
        {
            $page = Page::findByTitle($matches[1]);
            $page->count() !== 0 ? $class = 'blue-link' : $class = 'red-link';
            $page->count() !== 0 ? $link = route('page.show', ['reference' => $page->reference]) : $link = url('/wiki/' . str_replace(' ', '_', $matches[1]));

            return [
                'extent' => strlen($matches[0]),
                'element' => [
                    'name' => 'a',
                    'text' => $matches[1],
                    'attributes' => [
                        'href' => $link,
                        'class' => $class
                    ]
                ],
            ];
        }
    }

    protected function blockHeader($Line)
    {
        if (isset($Line['text'][1]))
        {
            $level = 1;

            while (isset($Line['text'][$level]) and $Line['text'][$level] === '#')
            {
                $level ++;
            }

            $level++;

            if ($level > 6)
            {
                return;
            }

            $text = trim($Line['text'], '# ');

            $link_icon = '<a href="#'.camel_case($text).'" class="header-link"><i class="fa fa-fw fa-link"></i></a>';

            $Block = array(
                'element' => array(
                    'name' => 'h' . min(6, $level),
                    'text' => $text . $link_icon,
                    'handler' => 'line',
                    'attributes' => [
                        'id' => camel_case($text)
                    ]
                ),
            );

            return $Block;
        }
    }
}