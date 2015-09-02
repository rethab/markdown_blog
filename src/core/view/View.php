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
class View extends AbstractView
{

    public function image(Image $model, $arg)
    {
        // TODO content-body needed? many divs ...
        return '<div class="row"><div class="col-md-12 content-body">' . $model->parse($arg) . '</div></div>';
    }

    public function carousel(Carousel $model, $arg){
        $index = !isset($config['reverse']) || $config['reverse'] ? 0 : 1;
        $carousel = '<div class="modal-dialog"><div class="modal-content">'.$model->parse($index).'</div></div>';
        return '<div class="modal fade" id="carousel-modal" role="dialog">'.$carousel.'</div>';
    }

    public function composite(Composite $model, $arg){
       return '';
    }

    /**
     *
     * @param Collection $model
     * @param $arg
     * @return string
     */
    public function collection(Collection $model, $arg){
        $string = '';
        $cols = isset($this->config['columns']) && $this->config['columns'] > 0 ? $this->config['columns'] : 1;
        $width = floor(12 / $cols);
        $break = $arg;
        $it = new ArrayIterator($model->get());
        $item_left = $it->count();
        while ($it->valid()) {
            $column = '';
            $break += ceil($item_left / $cols);
            while ($it->valid() && $arg < $break) {
                $column .= '<div class="row"><div class="col-md-12 list-item">'.$this->visit($it->current(),$arg).'</div></div>';
                $it->next();
                $item_left --;
                $arg++;
            }
            $cols--;
            $string .= '<div class="col-md-'.$width.' list-column">'.$column.'</div>';
        }
        $string = '<div class="row list">' . $string . '</div>';
        return $string.$this->pager($model->count, $model->limit);

    }

    public function markup(Markup $model, $arg){
        $body = '<div class="row"><div class="col-md-12 content-item">'.$model->parse($arg).'</div></div>';

        if (!is_null($arg) && (!array_key_exists('static', $this->config) || $this->config['static'])){
            $href = Config::getInstance()->app_root.URLs::getInstance()->getURI().'/'.preg_replace('/\\.[^.\\s]{2,4}$/', '', basename($model->path));
            // social
            $social = '';
            $general = Config::getInstance()->getGeneralArray('general');
            // TODO description
            if (array_key_exists('social', $general) && $general['social']) {
                $social = Social::getInstance()->socialButtons('https://'.$_SERVER['HTTP_HOST'].$href, Head::getInstance()->getTitle());
            }
            $static = '<a class="btn btn-default" href="'.$href.'" role="button">Static <i class="fa fa-share-alt"></i></a>';
            $body .= '<div class="row"><div class="col-md-4"><div class="row">'.$social.'</div></div><div class="col-md-8 text-right">'.$static.'</div></div>';
        }
        return self::head($model->parseTags($model->path)).$body;
    }

    public function markdown(Markdown $model, $arg)
    {
        return self::markup($model, $arg);
    }

    public function hyperTextMarkup(HyperTextMarkup $model, $arg)
    {
        return self::markup($model, $arg);
    }

    /**
     * This function builds the head containing the meta information
     *
     * TODO move to markup()?!
     *
     * @param array $tags
     * @return string
     */
    private function head(array $tags)
    {
        $head = '';

        if (! empty($tags)) {
            $left = '';
            if (isset($tags['published'])) {
                $left .= $tags['published'];
            }
            if (isset($tags['author'])) {
                $left = $left ? $left . ' | ' . $tags['author'] : $tags['author'];
            }
            $left = $left ? '<div class="col-md-5"><p>' . $left . '</p></div>' : '';
            $right = '';
            if (isset($tags['category'])) {
                $href = Config::getInstance()->app_root.URLs::getInstance()->getURI() . '?' . QueryString::removeAll(array('tag', 'page'), $_SERVER['QUERY_STRING']);
                foreach ($tags['category'] as $c) {
                    $right .= ' | <a href="' . $href . '&tag=' . $c . '">#' . trim($c) . '</a>';
                    // $right .= ' | #' . trim($c);
                }
                $right = '<div class="col-md-7 pull-right text-right">' . substr($right, 3) . '</div>';
            }
            $head = $left . $right ? '<div class="row content-head">' . $left . $right . '</div>' : '';
            // adding meta tags
            if (isset($tags['meta'])) {
                foreach ($tags['meta'] as $name => $content) {
                    Head::getInstance()->addMeta($name, $content);
                }
            }
        }
        return $head;
    }

    // TODO move to collection()?!
    private function pager($count, $limit){
        $pager = '';
        if (!is_null($limit)) {
            $prev = isset($_GET['page']) ? $_GET['page'] - 1 : 0;
            $next = isset($_GET['page']) ? $_GET['page'] + 1 : 2;
            $self = URLs::getInstance()->getURI() . '?' . QueryString::remove('page', $_SERVER['QUERY_STRING']) . '&page=';
            $self = Config::getInstance()->app_root.$self;
            if ($prev > 0) {
                $pager = '<li class="previous"><a href="' . $self . $prev . '">&larr; Newer</a></li>';
            }
            if ($next <= ceil($count / ($limit))) {
                $pager .= '<li class="next"><a href="' . $self . $next . '">Older &rarr;</a></li>';
            }
            $pager = '<ul class="pager">' . $pager . '</ul>';
            $pager = '<div class="row"><div class="col-md-12">' . $pager . '</div></div>';
        }
        return $pager;
    }
}

?>
