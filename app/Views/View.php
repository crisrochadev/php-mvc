<?php

namespace App\Views;

class View
{
    private static function getViewContent($view)
    {
        $path = __DIR__ . "/../../resources/" . $view . ".html";
        if (file_exists($path)) {
            $content = file_get_contents($path);
            return $content;
        } else {
            return "";
        }
    }
    public static function render($view, $vars = [])
    {
        $vars["URL"] = URL;
        $keys = array_map(function ($key) {
            return "{{" . $key . "}}";
        }, array_keys($vars));

        $content = self::getViewContent($view);


        preg_match_all('/<component(.*?)\/>/', $content, $matches);
        if ($matches[0]) {
            $arrComponents = [];
            $arrComponentsContent = [];
            foreach ($matches[0] as $match) {
                array_push($arrComponents, $match);
                //Pega as variaveis
                $strVars = [];
                if (stripos($match, "vars=") !== false) {
                    preg_match_all('/vars=\[(.*?)\]/s', $match, $matchesVars);
                    foreach ($matchesVars[0] as $v) {

                        $start = strpos($v, "vars=[") + 6;
                        $end = strpos($v, "]", $start);
                        $strVars = json_decode(substr($v, $start, $end - $start), true);
                    }
                }
                //Pega a view
                $strView = "";
                if (stripos($match, "name=") !== false) {


                    preg_match_all('/name=\[(.*?)\]/s', $match, $matchesView);
                    foreach ($matchesView[0] as $v) {

                        $start = strpos($v, "vars=[") + 6;
                        $end = strpos($v, "]", $start);
                        $strView = substr($v, $start, $end - $start);
                    }
                }

                array_push($arrComponentsContent, self::render($strView, $strVars));
            }
            $content = str_replace($arrComponents, $arrComponentsContent, $content);
        }



        $repleced = str_replace($keys, array_values($vars), $content);

        return $repleced;
    }
}
