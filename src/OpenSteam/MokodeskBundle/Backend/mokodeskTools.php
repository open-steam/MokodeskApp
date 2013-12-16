<?php

/**
 * Short description for file
 *
 * Long description (if any) ...
 *
 * PHP versions 4 and 5
 *
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * + Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 * + Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation and/or
 * other materials provided with the distribution.
 * + Neither the name of the <ORGANIZATION> nor the names of its contributors
 * may be used to endorse or promote products derived
 * from this software without specific prior written permission.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  CategoryName
 * @package   PackageName
 * @author    Author's name <author@mail.com>
 * @copyright 2013 Author's name
 * @license   http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version   CVS: $Id:$
 * @link      http://pear.php.net/package/PackageName
 * @see       References to other sections (if any)...
 */

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param string $webserverUrl Parameter description (if any) ...
 * @param string $content      Parameter description (if any) ...
 * @param object $object       Parameter description (if any) ...
 *
 * @return string Return description (if any) ...
 */
function gettexthtmlnew($webserverUrl, $content, $object)
{
    $current_path = substr($object->get_path(), 0, strrpos($object->get_path(), "/")) . "/";
    $content = preg_replace('/link href="(?!http)([a-z0-9.-_\/]*)"/iU', 'link href="' . $webserverUrl . '/tools/get.php?object=' . $current_path . '$1"', $content);
    $content = preg_replace('/src="\/(?!mokodesk\/tiny_mce)([a-z0-9. \-\%_\/]+)"/iU', 'src="' . $webserverUrl . '/tools/get.php?object=$1"', $content);
    $content = preg_replace('/src="(?!\/|http)([a-z0-9.\- _\/]+)"/iU', 'src="' . $webserverUrl . '/tools/get.php?object=' . $current_path . '$1"', $content);
    $content = preg_replace('/code="([a-z0-9.\-_\/]*)"/iU', 'src="' . $webserverUrl . '/tools/get.php?object=' . $current_path . '$1"', $content);
    $addition_math = "";
    $addition_anno = "";
    if (strpos($content, '<span class="AM') || strpos($content, 'image/svg+xml')) {
        $addition_math = '<script type="text/javascript" src="/bundles/opensteammokodesk/js/tiny_mce/plugins/asciimath/js/ASCIIMathMLwFallbackMin.js"></script>
                          <script type="text/javascript" src="/bundles/opensteammokodesk/js/tiny_mce/plugins/asciisvg/js/ASCIIsvgPIMin.js"></script>
                          <script type="text/javascript">
                          var AScgiloc = "/bundles/opensteammokodesk/tools/asciisvg/svgimg.php";
                          var AMTcgiloc = "/cgi-bin/mimetex.cgi";
                          </script>';
    }
    if (strpos($content, '<acronym')) {
        $addition_anno = '<link href="/bundles/opensteammokodesk/js/tiny_mce/plugins/bid_tooltip/css/content.css" type="text/css" rel="stylesheet">';
    }

    $content =  '<head>' . $addition_math . $addition_anno . '</head> ' . $content;
    if (mb_detect_encoding($content, 'UTF-8, ISO-8859-1') !== 'UTF-8') $content = utf8_encode($content);
    return $content;
}

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param unknown $desc Parameter description (if any) ...
 *
 * @return unknown Return description (if any) ...
 */
function tidyDesc($desc)
{
    $allowed = "/[^a-zÖÄÜöäüß0-9\\040\\.\\-\\_\\(\\)\\!\\,\\;\\?\\:]/i";
    if (mb_detect_encoding($desc, 'UTF-8, ISO-8859-1') !== 'UTF-8') $desc = utf8_encode($desc);
    $desc = preg_replace($allowed, "", $desc);

    return $desc;
}

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param unknown $name Parameter description (if any) ...
 *
 * @return unknown Return description (if any) ...
 */
function tidyName($name)
{
    $search =  Array(' ', 'ß', 'ö', 'ä', 'ü', 'Ö', 'Ä', 'Ü', '&');
    $replace = Array('_', 'ss','oe','ae','ue','Oe','Ae','Ue', 'und');
    $name = str_replace($search, $replace, $name);
    $allowed = "/[^a-z0-9\\040\\.\\-\\_]/i";
    $name = preg_replace($allowed, "", $name);

    return $name;
}
