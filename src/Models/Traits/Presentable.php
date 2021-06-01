<?php

namespace Emberfuse\Blaze\Models\Traits;

use Emberfuse\Blaze\Presenters\Presenter;

trait Presentable
{
    /**
     * Get relevant view presenter.
     *
     * @return \Emberfuse\Blaze\Presenters\Presenter
     */
    public function present(): Presenter
    {
        $presenter = $this->constructPresenter();

        return new $presenter($this);
    }

    /**
     * Create model view presenter instance.
     *
     * @return string
     */
    protected function constructPresenter(): string
    {
        $class = class_basename($this);

        return "App\\Presenters\\{$class}Presenter";
    }
}
