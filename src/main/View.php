<?php

namespace Src;

class View
{
    private $viewFile;
    private $data;

    public function __construct($viewFile, $data = [])
    {
        $this->viewFile = $viewFile;
        $this->data = $data;
    }

    public function render()
    {
        ob_start();
        extract($this->data);
        include $this->viewFile;
        return ob_get_clean();
    }
}
