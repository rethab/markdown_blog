<?php

/**
 * This file is part of the MarkdownBlog project.
 * TODO ...
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
class Config
{
    public $general;
    public $config;
    public $error;
    public $app_root;
    public $title;

    private static $instance = null;

    private function __construct()
    {
        // General settings
        $this->general = IniParser::parseMerged(array(SRC_DIR.'defaults.ini', CONFIG_DIR.'general.ini'));
        // Sitemap settings
        $this->config = IniParser::parseMerged(array(CONFIG_DIR.'config.ini'));
        // Error settings
        $files = file_exists(CONFIG_DIR.'error.ini') ? array(SRC_DIR.'error.ini', CONFIG_DIR.'error.ini') : array(SRC_DIR.'error.ini');
        $this->error = IniParser::parseMerged($files);
        // extract most recently used
        $this->app_root = $this->general['general']['app_root'];
        $this->title = array_key_exists('title', $this->general['head']) ? $this->general['head']['title'] : '';
    }

    private function __clone() {}

    /**
     * returns the instance created by its first invoke.
     *
     * @return Config
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * returns the nested array of the config settings according to the given
     * key if exists returns an empty array() otherwise
     *
     * @param string $key of the array element
     * @return array
     */
    public function getConfigArray($key){
        return array_key_exists($key, $this->config) ? $this->config[$key] : array();
    }

    /**
     * returns the item of the given nested array of the config settings
     * according to the given key if exists, returns false otherwise
     *
     * @param string $array name of the nested array
     * @param string $key name of the item to get
     * @return bool
     */
    public function getConfig($array, $key){
        if(array_key_exists($array, $this->config)){
            return array_key_exists($key, $this->config[$array]) ? $this->config[$array][$key] : false;
        }
        return false;
    }

    /**
     * returns the nested array of the general settings according to the given
     * key if exists returns an empty array() otherwise
     *
     * @param string $key of the array element
     * @return array
     */
    public function getGeneralArray($key){
        return array_key_exists($key, $this->general) ? $this->general[$key] : array();
    }

    /**
     * returns the item of the given nested array of the general settings
     * according to the given key if exists, returns false otherwise
     *
     * @param string $array name of the nested array
     * @param string $key name of the item to get
     * @return bool
     */
    public function getGeneral($array, $key){
        if(array_key_exists($array, $this->general)){
            return array_key_exists($key, $this->general[$array]) ? $this->general[$array][$key] : false;
        }
        return false;
    }

    /**
     * returns the nested array of the error settings according to the given key
     * if exists returns an empty array() otherwise
     *
     * @param string $key of the array element
     * @return array
     */
    public function getErrorArray($key){
        return array_key_exists($key, $this->error) ? $this->error[$key] : array();
    }

    /**
     * returns the item of the given nested array of the error settings according
     * to the given key if exists, returns false otherwise
     *
     * @param string $array name of the nested array
     * @param string $key name of the item to get
     * @return bool
     */
    public function getError($array, $key){
        if(array_key_exists($array, $this->error)){
            return array_key_exists($key, $this->error[$array]) ? $this->error[$array][$key] : false;
        }
        return false;
    }
}
