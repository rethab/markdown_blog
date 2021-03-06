<?php

/**
 * This file is part of the MarkdownBlog project.
 * It provides the abstract central part of the application and is responsible for loading
 * and parsing the files.
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
abstract class AbstractModel implements IModel {

    // TODO public / private
    public $config;

    public function __construct($config) {
        $this->config = $config;
    }

    /**
     * 
     * @param IVisitor $visitor used to travers the tree 
     * @param mixed $arg passed through while traversing the tree
     * @param bool $bool passed through while traversing the tree
     * @return mixed return value pushed up to the root
     */
    public abstract function accept(IVisitor $visitor, $arg, $bool);
}
