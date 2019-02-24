<?php
namespace Core;

class Helper
{
    public static function getUrl()
    {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') .
            '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
    public static function snakeToStudlyCaps($text, $capitalise_first_char = true)
    {
        $text_arr = explode('-', $text);
        foreach ($text_arr as $num => $value) {
            $text_arr[$num] = ucfirst($text_arr[$num]);
        }
        $text = implode('', $text_arr);
        if (!$capitalise_first_char) {
            $text[0] = strtolower($text[0]);
        }
        return $text;
    }

    public static function safeText($text)
    {
        $text = preg_replace("/[^a-zA-Z0-9_]+/", "", $text);
        return $text;
    }

    /**
     * Interpolates context values into the message placeholders.
     */
    public function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
