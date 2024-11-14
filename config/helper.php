<?php


function url($url='')
{
    echo BURL.$url;
}


function redirect($url)
{
    return  BURL.$url;
}