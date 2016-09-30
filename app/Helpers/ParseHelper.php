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
        $posts = DB::table('posts')->first();
        if (!$posts) {
            $maxId = self::getMaxId();
            //dd($maxId);
            for ($i = $maxId; $i > $maxId - 100; $i--) {
                self::parseQuote($i);
            }
        }
    }

    public static function addNew()
    {
        $maxId=self::getMaxId();
        $lastId=DB::table('posts')->orderBy('original_id', 'desc')->first();
        if (!$lastId) $lastId=$maxId-100;
        for ($i=$maxId; $i>$lastId; $i--)
        {
            self::parseQuote($i);
        }
        /*
        $url="http://bash.im/";
        $doc = new \DOMDocument();
        $doc->strictErrorChecking=false;
        $doc->recover = true;
        libxml_use_internal_errors(true);

        $doc->loadHTMLfile($url);

        $a = new \DOMXPath($doc);

        $elements=$a->query('//*[@class="text"]');
        foreach ($elements as $element) {
            print_r($element->nodeValue);
            echo "<br>";
            echo "<br>";
        }
        */
    }

    public static function getMaxId()
    {
        $doc = new \DOMDocument();
        $doc->strictErrorChecking=false;
        $doc->recover = true;
        libxml_use_internal_errors(true);
        $doc->loadHTMLfile("http://bash.im");
        $a = new \DOMXPath($doc);
        $spans = $a->query('//*[@class="id"]');
        return  mb_substr( $spans->item(0)->nodeValue, 1);
    }

    public static function parseQuote($id)
    {
        $url="http://bash.im/quote/";
        $doc = new \DOMDocument();
        $doc->strictErrorChecking=false;
        $doc->recover = true;
        $doc->loadHTMLfile($url."$id");
        $brs = $doc->getElementsByTagName('br');
        foreach ($brs as $node) {
            $node->parentNode->replaceChild($doc->createTextNode("\r\n"), $node);
        }
        $a = new \DOMXPath($doc);
        $rait=$a->query('//*[@class="rating"]');
        if ($rait->item(0)->nodeValue>1000)
        {
            $text=$a->query('//*[@class="text"]');
            $original_id=$a->query('//*[@class="id"]');
            DB::table('posts')->insert([
                'text' =>  $text->item(0)->nodeValue,
                'original_id' => mb_substr ($original_id->item(0)->nodeValue, 1),
            ]);;
        }
    }
}
