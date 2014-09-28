<?php

/**
 * This file is part of the MarkdownBlog project.
 * It provides the central part of the application and is responsible for loading 
 * and parsing the HTML files.
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

class HyperTextMarkup implements IModel
{
    public $path;
    public $count = 0;
    
    public function __construct($path)
    {
        $this->path = $path;
        Head::getInstance()->link(CSS_DIR.'markdown.css');
    }
    
    /**
     * TODO maybe use template method pattern
     * 
     * This function loads all respectively specified files contained in the folder
     * stored in $this->path, parses them into a HTML string and returns it.
     * 
     * @param unknown $start    - offset where to start, if given
     * @param unknown $limit    - maximum number of files to parse, if given
     */
    public function getList($start = 0, $limit = null)
    {
        $list = array();
        $files = ScanDir::getFilesOfType($this->path, '.html');
        $this->count = count($files);        
        rsort($files);
        $limit = is_null($limit) ? count($files) : $limit;
        
        for ($i = $start; $i < count($files) && $i - $start < $limit; $i++)
        {
            $list[] = $this->parse($this->path.$files[$i]);
        }
        return $list;
    }
    
    /**
     * This function loads the file specified in $this->path, parses it into
     * a HTML string and returns it.
     */
    public function get()
    {
        return $this->parse($this->path);
    }
    
    /**
     * This function parse the given file into HTML and outputs a string containing its content. 
     * 
     * @param unknown $file - file to parse
     */
    public function parse($file)
    {
        $string = '';
        $fh = fopen($file, 'r');
        
        if ($fh)
        {
            $string = fread($fh, filesize($file));
        }
        else
        {
            Logger::getInstance()->add(new Error('Can not open '.$file, 'HyperTextMarkup::parse("'.$file.'")'));
        }
        
        return $string;
    }
}

?>