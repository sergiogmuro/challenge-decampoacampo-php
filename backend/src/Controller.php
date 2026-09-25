<?php

namespace Src;

abstract class Controller
{
    protected View $view;

    public function setView(View $view): void
    {
        $this->view = $view;
    }
}
