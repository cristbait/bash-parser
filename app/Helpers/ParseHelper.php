<?php
/**
 * Created by PhpStorm.
 * User: bulat
 * Date: 26.09.2016
 * Time: 1:47
 */

namespace App\Helpers;
use Illuminate\Support\Facades\DB;

class ParseHelper
{
    public static function seed()
    {
        $url="http://bash.im/quote/";
        $doc = new \DOMDocument();
        $doc->strictErrorChecking=false;
        $doc->recover = true;
        libxml_use_internal_errors(true);
        $maxId=self::getMaxId();

        for ($i=$maxId; $i>$maxId-100; $i--)
        {
            $doc->loadHTMLfile($url."$i");
            $brs = $doc->getElementsByTagName('br');
            foreach ($brs as $node) {
                $node->parentNode->replaceChild($doc->createTextNode("\r\n"), $node);
            }
            $a = new \DOMXPath($doc);
            $rait=$a->query('//*[@class="rating"]');
            if ($rait->item(0)->firstChild->nodeValue>1000)
            {
                $text=$a->query('//*[@class="text"]');
                $original_id=$a->query('//*[@class="id"]');
                DB::table('posts')->insert([
                    'text' =>  $text->item(0)->nodeValue,
                    'original_id' => mb_substr ($original_id->item(0)->firstChild->nodeValue, 1),
                ]);;
            }
        }
    }

    public static function getMaxId()
    {
        $doc = new \DOMDocument();
        $doc->strictErrorChecking=false;
        $doc->recover = true;
        libxml_use_internal_errors(true);
        $doc->loadHTMLfile("http://bash.im");
        $classname = "id";
        $a = new \DOMXPath($doc);
        $spans = $a->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        return  mb_substr( $spans->item(0)->firstChild->nodeValue, 1);
    }

    public static function addNew()
    {
        $url="http://bash.im/quote/441345";
        $doc = new \DOMDocument();
        $doc->strictErrorChecking=false;
        $doc->recover = true;
        libxml_use_internal_errors(true);

        $doc->loadHTMLfile($url);

        $brs = $doc->getElementsByTagName('br');

        $a = new \DOMXPath($doc);
        //dd($brs);
        //$rait=$a->query('//*[@class="rating"]');
        //    if ($rait->item(0)->firstChild->nodeValue>1000)
        //    {
        $classname = "text";

        $elements=$a->query('//*[@class="text"]');
        foreach ($elements as $element)
        {
            print_r($element->nodeValue);
            echo "<br>";
            echo "<br>";
        }
        /* $texts = $a->query("//text()[(following::br) or (preceding::br)]");
        foreach ($texts as $text) {
            print_r($text->firstChild->nodeValue);
            echo '<br>';
            echo "<br>";
        }
       */
            //$original_id=$a->query('//*[@class="id"]');
                //print_r($text->item(0)->firstChild->nodeValue);
        //        DB::table('posts')->insert([
        //            'text' =>  html_entity_decode($text->item(0)->firstChild->nodeValue, ENT_COMPAT, "UTF-8"),
       //             'original_id' => mb_substr ($original_id->item(0)->firstChild->nodeValue, 1),
        //        ]);;
        //    }

        //$classname = "quote";

        //$spans = $a->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    }

}