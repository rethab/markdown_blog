<?php

/**
 * This file is part of the MarkdownBlog project.
 * TODO
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
class Collection extends AbstractModel
{
    public $models = array();

    public $count = 0;

    public $limit = null;

    public $start = 0;

    public $cols = 1;

    public function __construct($model, $config, $path)
    {
        parent::__construct($path);

        $files = ScanDir::getFilesOfType($this->path, $model::MIME);
        $reverse = isset($config['reverse']) ? $config['reverse'] : true;
        if($reverse) // TODO Image has to be reverse = false
        {
            rsort($files);
        }
        foreach($files as $f){
            $models[] = new $model($this->path.$f);
        }
        // detect number of columns to show
        $this->cols = isset($config['columns']) && $config['columns'] > 0 ? $config['columns'] : 1;
        $this->count = count($models);
        if (isset($config['limit'])) {
            $this->limit = $config['limit'] * $this->cols;
            $this->start = isset($_GET['page']) && $_GET['page'] > 0 ? $this->limit * ($_GET['page'] - 1) : 0;
        }
        // TODO apply filter before here
        $this->limit = is_null($this->limit) ? $this->count : $this->limit;
        $it = new ArrayIterator($models);
        $i = $this->start;
        while($it->valid() && $i - $this->start < $this->limit) {
            $this->models[] = $it->current();
            $it->next();
            $i ++;
        }
    }

    /**
     *
     *
     * @param IVisitor $visitor
     * @param $arg
     * @return mixed
     */
    public function accept(IVisitor $visitor, $arg){
        return $visitor->collection($this, $this->start);
    }

    // merge get and parse?!
    public function get()
    {
        return $this->models;
    }

    /**
     * This function parse the given file into HTML and outputs a string
     * containing its content.
     *
     * @param int $index of parsed element
     * @return string parsed collection
     */
    public function parse($index)
    {
        return '';
    }
}
