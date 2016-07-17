<?php

/**
 * This file is part of the MarkdownBlog project.
 * It provides a view that displays formatted markdown/html files as a list.
 *
 * MarkdownBlog is a lightweight blog software written in php and twitter bootstrap.
 * Its purpose is to provide a easy way to share your thoughts without any Database
 * or special setup needed.
 *
 * Copyright (C) 2014 Philipp Gamper & Max Schrimpf
 *
 * The project is free software: You can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The file is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the project. if not, write to the Free Software Foundation, Inc.
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */
abstract class AbstractNavigationView extends AbstractView {

    protected $anchor;

    /**
     *
     * @param Container $model
     * @param boolean $anchor indicates whether to use anchors (true) or urls (false)
     */
    public function __construct(Container $model, $anchor) {
        $this->model = $model;
        $this->anchor = $anchor;
    }

    /**
     *
     * @return string HTML to show
     */
    public function show() {
        $nav = '';
        foreach ($this->model->getModels() as $key => $value) {
            $nav .= $this->visit($value, $key);
        }
        return $nav;
    }

    public function typedContainer(TypedContainer $model, $arg) {
        return $this->container($model, $arg);
    }

    public function composite(Composite $model, $arg) {
        return $this->li($model, $arg, $this->anchor);
    }

    public function collection(Collection $model, $arg) {
        return $this->li($model, $arg, $this->anchor);
    }

    public function markup(Markup $model, $arg) {
        return $this->li($model, $arg, $this->anchor);
    }

    public function markdown(Markdown $model, $arg) {
        return $this->markup($model, $arg);
    }

    public function hyperTextMarkup(HyperTextMarkup $model, $arg) {
        return $this->markup($model, $arg);
    }

    public function hypertextPreprocessor(HypertextPreprocessor $model, $arg) {
        return $this->li($model, $arg, $this->anchor);
    }

    public function remote(Remote $model, $arg) {
        return $this->markdown($model, $arg);
    }

    /**
     * @param Link $model
     * @param $arg
     * @return mixed|string
     */
    public function link(Link $model, $arg) {
        return $this::li($model, $model->config['path'], false);
    }

    public function image(Image $model, $arg) {
        return $this->li($model, $arg, $this->anchor);
    }

    public function carousel(OwlCarousel $model, $arg) {
        return $this->li($model, $arg, $this->anchor);
    }

    /**
     * @param AbstractModel $model
     * @param mixed $arg
     * @param boolean $anchor indicates whether the link is an anchor (true) or url (false)
     * @return mixed
     */
    protected abstract function li(AbstractModel $model, $arg, $anchor);

    protected function active($arg) {
        $active = true;
        $level = count(explode('/', $this->config['root'])) - 2;
        $regexp = '/' . str_replace('/', '\/', $this->config['root']) . '/';
        foreach (explode('/', preg_replace($regexp, '', $arg, 1)) as $module) {
            $active &= URLs::getInstance()->module($level) == $module;
            $level++;
        }
        return $active ? 'class="active"' : '';
    }

    protected function prefix($arg, $anchor) {
        if (URLs::getInstance()->isRaw()) {
            $prefix = Config::getInstance()->app_root;
        } else if ($anchor && URLs::getInstance()->isRoot()) {
            $prefix = '#';
        } else if ($anchor) {
            $prefix = Config::getInstance()->app_root . '#';
        } else {
            $prefix = substr($arg, 0, 1) == '#' && !URLs::getInstance()->isRoot() ? Config::getInstance()->app_root : '';
        }
        return $prefix;
    }
}
