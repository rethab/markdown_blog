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
class View extends AbstractPage {

    public function __construct(AbstractModel $model, array $config) {
        parent::__construct($model, $config);
        if (array_key_exists('description', $this->config)) {
            Head::getInstance()->addOg('description', $this->config['description']);
        }
        if (!array_key_exists('subtitle', $this->config) || $this->config['subtitle']) {
            $title = Config::getInstance()->title . ' - ' . $this->config['name'];
            Head::getInstance()->addOg('title', $title);
            Head::getInstance()->setTitle($title);
        }
    }

    public function link(Link $model, $arg, $bool) {
        return '<div class="row content-item"><div class="col-md-12">' . $model->parse($arg) . '</div></div>';
    }
}
