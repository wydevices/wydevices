<?php
/*
 * This file is part of Wydevices project, http://code.google.com/p/wydevices
 * 
 * Wydevices record utility
 * 
 * Author : Argos <argos66@gmail.com>
 * 
 */

function gui_splash($action) {
    if ($action == "shutdown") { 
        system("mv /usr/bin/splash.py /usr/bin/unsplash.py");
        system("killall python2.5");
        system("sleep 3");
        system("mv /usr/bin/unsplash.py /usr/bin/splash.py");
    }elseif ($action == "start") { 
        system("ngc -z system/splash/start");
        system("ngstart system/splash/start");
    }
}

// Function to recursively delete a directory
function rmdir_recursive($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") rmdir_recursive($dir."/".$object); else unlink($dir."/".$object);
            }
        }
        reset($objects);
        echo "Deleting: ".$dir."\n\t";
        rmdir($dir);
    }
}

// Function to cleanup XML output for writing into myrecords.fxd and record.xml
function fix_xml_output($xml_input) {
    $xml_output = str_replace("/>", " />", $xml_input);
    $xml_output = str_replace("<?xml version=\"1.0\"?>\n", "", $xml_output);

    // Decode UTF8 char for clean output
    $xml_output = str_replace("&#xA0;", " ", $xml_output); //no-break space = non-breaking space
    $xml_output = str_replace("&#xA1;", "¡", $xml_output); //inverted exclamation mark
    $xml_output = str_replace("&#xA2;", "¢", $xml_output); //cent sign
    $xml_output = str_replace("&#xA3;", "£", $xml_output); //pound sign
    $xml_output = str_replace("&#xA4;", "¤", $xml_output); //currency sign
    $xml_output = str_replace("&#xA5;", "¥", $xml_output); //yen sign = yuan sign
    $xml_output = str_replace("&#xA6;", "¦", $xml_output); //broken bar = broken vertical bar
    $xml_output = str_replace("&#xA7;", "§", $xml_output); //section sign
    $xml_output = str_replace("&#xA8;", "¨", $xml_output); //diaeresis = spacing diaeresis
    $xml_output = str_replace("&#xA9;", "©", $xml_output); //copyright sign
    $xml_output = str_replace("&#xAA;", "ª", $xml_output); //feminine ordinal indicator
    $xml_output = str_replace("&#xAB;", "«", $xml_output); //left-pointing double angle quotation mark = left pointing guillemet
    $xml_output = str_replace("&#xAC;", "¬", $xml_output); //not sign
    $xml_output = str_replace("&#xAD;",  "­", $xml_output); //soft hyphen = discretionary hyphen
    $xml_output = str_replace("&#xAE;", "®", $xml_output); //registered sign = registered trade mark sign
    $xml_output = str_replace("&#xAF;", "¯", $xml_output); //macron = spacing macron = overline = APL overbar
    $xml_output = str_replace("&#xB0;", "°", $xml_output); //degree sign
    $xml_output = str_replace("&#xB1;", "±", $xml_output); //plus-minus sign = plus-or-minus sign
    $xml_output = str_replace("&#xB2;", "²", $xml_output); //superscript two = superscript digit two = squared
    $xml_output = str_replace("&#xB3;", "³", $xml_output); //superscript three = superscript digit three = cubed
    $xml_output = str_replace("&#xB4;", "´", $xml_output); //acute accent = spacing acute
    $xml_output = str_replace("&#xB5;", "µ", $xml_output); //micro sign
    $xml_output = str_replace("&#xB6;", "¶", $xml_output); //pilcrow sign = paragraph sign
    $xml_output = str_replace("&#xB7;", "·", $xml_output); //middle dot = Georgian comma = Greek middle dot
    $xml_output = str_replace("&#xB8;", "¸", $xml_output); //cedilla = spacing cedilla
    $xml_output = str_replace("&#xB9;", "¹", $xml_output); //superscript one = superscript digit one
    $xml_output = str_replace("&#xBA;", "º", $xml_output); //masculine ordinal indicator
    $xml_output = str_replace("&#xBB;", "»", $xml_output); //right-pointing double angle quotation mark = right pointing guillemet
    $xml_output = str_replace("&#xBC;", "¼", $xml_output); //vulgar fraction one quarter = fraction one quarter
    $xml_output = str_replace("&#xBD;", "½", $xml_output); //vulgar fraction one half = fraction one half
    $xml_output = str_replace("&#xBE;", "¾", $xml_output); //vulgar fraction three quarters = fraction three quarters
    $xml_output = str_replace("&#xBF;", "¿", $xml_output); //inverted question mark = turned question mark
    $xml_output = str_replace("&#xC0;", "À", $xml_output); //Latin capital letter A with grave = Latin capital letter A grave
    $xml_output = str_replace("&#xC1;", "Á", $xml_output); //Latin capital letter A with acute
    $xml_output = str_replace("&#xC2;", "Â", $xml_output); //Latin capital letter A with circumflex
    $xml_output = str_replace("&#xC3;", "Ã", $xml_output); //Latin capital letter A with tilde
    $xml_output = str_replace("&#xC4;", "Ä", $xml_output); //Latin capital letter A with diaeresis
    $xml_output = str_replace("&#xC5;", "Å", $xml_output); //Latin capital letter A with ring above = Latin capital letter A ring
    $xml_output = str_replace("&#xC6;", "Æ", $xml_output); //Latin capital letter AE = Latin capital ligature AE
    $xml_output = str_replace("&#xC7;", "Ç", $xml_output); //Latin capital letter C with cedilla
    $xml_output = str_replace("&#xC8;", "È", $xml_output); //Latin capital letter E with grave
    $xml_output = str_replace("&#xC9;", "É", $xml_output); //Latin capital letter E with acute
    $xml_output = str_replace("&#xCA;", "Ê", $xml_output); //Latin capital letter E with circumflex
    $xml_output = str_replace("&#xCB;", "Ë", $xml_output); //Latin capital letter E with diaeresis
    $xml_output = str_replace("&#xCC;", "Ì", $xml_output); //Latin capital letter I with grave
    $xml_output = str_replace("&#xCD;", "Í", $xml_output); //Latin capital letter I with acute
    $xml_output = str_replace("&#xCE;", "Î", $xml_output); //Latin capital letter I with circumflex
    $xml_output = str_replace("&#xCF;", "Ï", $xml_output); //Latin capital letter I with diaeresis
    $xml_output = str_replace("&#xD0;", "Ð", $xml_output); //Latin capital letter ETH
    $xml_output = str_replace("&#xD1;", "Ñ", $xml_output); //Latin capital letter N with tilde
    $xml_output = str_replace("&#xD2;", "Ò", $xml_output); //Latin capital letter O with grave
    $xml_output = str_replace("&#xD3;", "Ó", $xml_output); //Latin capital letter O with acute
    $xml_output = str_replace("&#xD4;", "Ô", $xml_output); //Latin capital letter O with circumflex
    $xml_output = str_replace("&#xD5;", "Õ", $xml_output); //Latin capital letter O with tilde
    $xml_output = str_replace("&#xD6;", "Ö", $xml_output); //Latin capital letter O with diaeresis
    $xml_output = str_replace("&#xD7;", "×", $xml_output); //multiplication sign
    $xml_output = str_replace("&#xD8;", "Ø", $xml_output); //Latin capital letter O with stroke = Latin capital letter O slash
    $xml_output = str_replace("&#xD9;", "Ù", $xml_output); //Latin capital letter U with grave
    $xml_output = str_replace("&#xDA;", "Ú", $xml_output); //Latin capital letter U with acute
    $xml_output = str_replace("&#xDB;", "Û", $xml_output); //Latin capital letter U with circumflex
    $xml_output = str_replace("&#xDC;", "Ü", $xml_output); //Latin capital letter U with diaeresis
    $xml_output = str_replace("&#xDD;", "Ý", $xml_output); //Latin capital letter Y with acute
    $xml_output = str_replace("&#xDE;", "Þ", $xml_output); //Latin capital letter THORN
    $xml_output = str_replace("&#xDF;", "ß", $xml_output); //Latin small letter sharp s = ess-zed
    $xml_output = str_replace("&#xE0;", "à", $xml_output); //Latin small letter a with grave = Latin small letter a grave
    $xml_output = str_replace("&#xE1;", "á", $xml_output); //Latin small letter a with acute
    $xml_output = str_replace("&#xE2;", "â", $xml_output); //Latin small letter a with circumflex
    $xml_output = str_replace("&#xE3;", "ã", $xml_output); //Latin small letter a with tilde
    $xml_output = str_replace("&#xE4;", "ä", $xml_output); //Latin small letter a with diaeresis
    $xml_output = str_replace("&#xE5;", "å", $xml_output); //Latin small letter a with ring above = Latin small letter a ring
    $xml_output = str_replace("&#xE6;", "æ", $xml_output); //Latin small letter ae = Latin small ligature ae
    $xml_output = str_replace("&#xE7;", "ç", $xml_output); //Latin small letter c with cedilla
    $xml_output = str_replace("&#xE8;", "è", $xml_output); //Latin small letter e with grave
    $xml_output = str_replace("&#xE9;", "é", $xml_output); //Latin small letter e with acute
    $xml_output = str_replace("&#xEA;", "ê", $xml_output); //Latin small letter e with circumflex
    $xml_output = str_replace("&#xEB;", "ë", $xml_output); //Latin small letter e with diaeresis
    $xml_output = str_replace("&#xEC;", "ì", $xml_output); //Latin small letter i with grave
    $xml_output = str_replace("&#xED;", "í", $xml_output); //Latin small letter i with acute
    $xml_output = str_replace("&#xEE;", "î", $xml_output); //Latin small letter i with circumflex
    $xml_output = str_replace("&#xEF;", "ï", $xml_output); //Latin small letter i with diaeresis
    $xml_output = str_replace("&#xF0;", "ð", $xml_output); //Latin small letter eth
    $xml_output = str_replace("&#xF1;", "ñ", $xml_output); //Latin small letter n with tilde
    $xml_output = str_replace("&#xF2;", "ò", $xml_output); //Latin small letter o with grave
    $xml_output = str_replace("&#xF3;", "ó", $xml_output); //Latin small letter o with acute
    $xml_output = str_replace("&#xF4;", "ô", $xml_output); //Latin small letter o with circumflex
    $xml_output = str_replace("&#xF5;", "õ", $xml_output); //Latin small letter o with tilde
    $xml_output = str_replace("&#xF6;", "ö", $xml_output); //Latin small letter o with diaeresis
    $xml_output = str_replace("&#xF7;", "÷", $xml_output); //division sign
    $xml_output = str_replace("&#xF8;", "ø", $xml_output); //Latin small letter o with stroke = Latin small letter o slash
    $xml_output = str_replace("&#xF9;", "ù", $xml_output); //Latin small letter u with grave
    $xml_output = str_replace("&#xFA;", "ú", $xml_output); //Latin small letter u with acute
    $xml_output = str_replace("&#xFB;", "û", $xml_output); //Latin small letter u with circumflex
    $xml_output = str_replace("&#xFC;", "ü", $xml_output); //Latin small letter u with diaeresis
    $xml_output = str_replace("&#xFD;", "ý", $xml_output); //Latin small letter y with acute
    $xml_output = str_replace("&#xFE;", "þ", $xml_output); //Latin small letter thorn
    $xml_output = str_replace("&#xFF;", "ÿ", $xml_output); //Latin small letter y with diaeresis

    return $xml_output;
}
?>
