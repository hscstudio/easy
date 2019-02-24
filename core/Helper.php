<?php
namespace core;
class Helper
{
  public static function getUrl(){
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . 
      '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    return [
      'full' => $url,
      'path' => parse_url($url, PHP_URL_PATH),
      'query' => parse_url($url, PHP_URL_QUERY),
      'fragment' => parse_url($url, PHP_URL_FRAGMENT),
    ];
  }

  /*
    snake-case => StudlyCaps / camelCase
    foo => Foo
    foo-bar => FooBar
  */
  public static function snakeToStudlyCaps($text, $capitalise_first_char = true){
    $text_arr = explode('-', $text);
    foreach($text_arr as $num=>$value){
      $text_arr[$num] = ucfirst($text_arr[$num]);
    }
    $text = implode('', $text_arr);
    if(!$capitalise_first_char) {
      $text[0] = strtolower($text[0]);
    }
    return $text;
  }

  public static function safeText($text){
    $text = preg_replace("/[^a-zA-Z0-9_]+/", "", $text);
    return $text;
  }
}