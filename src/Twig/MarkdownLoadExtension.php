<?php

namespace App\Twig;

use App\Util;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use Parsedown;

class MarkdownLoadExtension extends AbstractExtension
{

    protected $util;
    protected $parsedown;

    public function __construct(Util $util)
    {
        $this->util = $util;
        $this->parsedown = new Parsedown();
        $this->parsedown->setSafeMode(false);
        $this->parsedown->setMarkupEscaped(true);
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('load_markdown', [$this, 'load_markdown'], ["is_safe" => ["html"]]),
            new TwigFunction('load_local_markdown', [$this, 'load_local_markdown'], ["is_safe" => ["html"]])
        ];
    }

    public function load_markdown($file, $extension = "md")
    {
        return $this->parsedown->text(
            file_get_contents($this->util->get_parameter("workspace_directory") . $file . "." . $extension)
        );
    }

    public function load_local_markdown($file, $locale = null, $extension = "md")
    {
        $locale = $locale ?? $this->util->get_language()["code"];
        return $this->load_markdown($file . "." . $locale, $extension);
    }
}