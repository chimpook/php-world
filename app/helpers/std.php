<?php
function sortTitle($column, $data)
{
    $output = '';
    if (isset($data['sort']) && isset($data['dir'])) {
        $baseurl = $_SERVER['REQUEST_URI'];
        if (strpos($baseurl, 'SortBy')) {
            $baseurl = substr($baseurl, 0, strrpos($baseurl, '/SortBy', 0));
        }
        if (substr($_SERVER['REQUEST_URI'], -1) === '/') {
            $baseurl = substr_replace($baseurl, "", -1);
        }
        if ($data['sort'] === 'SortBy' . $column['field']) {
            $output .= '<span class="fa fa-angle-'. ($data['dir'] === 'Asc' ? 'down' : 'up') . '"></span>'
                . '<a href="' . $baseurl . '/SortBy'. $column['field'] . '/' 
                . (strtoupper($data['dir']) === 'ASC' ? 'Desc' : 'Asc') . '">';
        } else {
            $output .= '<a href="'. $baseurl .'/SortBy' . $column['field'] . '/Asc">';
        }
        $output .= $column['title'] . '</a>';
    }
    return $output;
}
