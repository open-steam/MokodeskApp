<?php

/**
 * Short description for file
 *
 * Long description (if any) ...
 *
 * PHP version 5
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
 * Description for require_once
 */
require_once dirname(__FILE__) . '/../etc/default.def.php';

/**
 * Description for require_once
 */
require_once dirname(__FILE__) . '/MokodeskSteam.php';

/**
 * Description for require_once
 */
require_once dirname(__FILE__) . '/mokodeskLang.php';

/**
 * Description for require_once
 */
require_once dirname(__FILE__) . '/mokodeskTools.php';

$task = isset($_POST['task']) ? ($_POST['task']) : null;
session_name("bidowl_session");
session_start();
$loginName = isset($_SESSION['user']) ? ($_SESSION['user']) : null;
$loginPwd = isset($_SESSION['pass']) ? ($_SESSION['pass']) : null;
$LANG = isset($_SESSION['language']) ? ($_SESSION['language']) : "de";
session_write_close();
$id = ($_POST['id']) ? ($_POST['id']) : null;
$steam = OpenSteam\MokodeskBundle\Backend\MokodeskSteam::connect(STEAM_SERVER, STEAM_PORT, $loginName, $loginPwd);
if (!$steam || !$steam->get_login_status()) {
    echo "No server connection!";
    exit();
}

switch ($task) {
case "saveEditNewName":
    saveEditNewName($steam, $id);
    $steam->disconnect();

    break;

case "saveEdit":
    setContentHtml($steam, $id);
    $steam->disconnect();

    break;

case "saveTitleAndTextMessage":
    saveTitleAndTextMessage($steam, $id);
    $steam->disconnect();

    break;

case "edit":
    getHtml($steam, $id);

    break;

default:
    $steam->disconnect();
    echo json_encode(array(
        'success' => false
    ));

    break;
} //end switch

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param object  $steam Parameter description (if any) ...
 * @param unknown $id    Parameter description (if any) ...
 *
 * @return void
 */
function setContentHtml($steam, $id)
{
    $content = ($_POST['textField']) ? ($_POST['textField']) : null;
    $origContent = ($_POST['origValue']) ? ($_POST['origValue']) : null;
    $document = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
    $access_write = $document->check_access_write($steam->get_current_steam_user());
    //$access_read = $document->check_access_read($steam->get_current_steam_user());
    $content = stripslashes($content);
    $content = preg_replace('#src="' . PATH_URL . '/get/path([a-z0-9.\- _\/]*)"#iU', 'src="$1"', $content);
    $current_path = substr($document->get_path(), 0, strrpos($document->get_path(), "/")) . "/";
    $content = preg_replace('#' . $current_path . '#iU', '', $content);
    if (!$access_write) {
        echo json_encode(array(
            'success' => false,
            'message' => "Keine Berechtigung zum Schreiben des Dokuments"
        ));
        exit;
    }
    $serverContent = $document->get_content();
    $serverContent = $serverContent ? $serverContent : "";
    $serverContent = stripslashes($serverContent);
    $serverContent = preg_replace('#src="' . PATH_URL . '/get/path([a-z0-9.\- _\/]*)"#iU', 'src="$1"', $serverContent);
    $origContent = stripslashes($origContent);
    $origContent = preg_replace('#src="' . PATH_URL . '/get/path([a-z0-9.\- _\/]*)"#iU', 'src="$1"', $origContent);
    $origContent = preg_replace('#' . $current_path . '#iU', '', $origContent);
    if ($origContent === $serverContent) {
        $result = $document->set_content($content);
        echo json_encode(array(
            'success' => true,
            'message' => $result
        ));
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => "Originalinhalt hat sich geändert. Keine Änderungen vorgenommen!",
            'changed' => true,
            'oldName' => $document->get_attribute("OBJ_DESC") ,
            'oldId' => $document->get_id()
        ));
    }
}

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param unknown $steam Parameter description (if any) ...
 * @param unknown $id    Parameter description (if any) ...
 *
 * @return void
 */
function saveEditNewName($steam, $id)
{
    $content = ($_POST['textField']) ? ($_POST['textField']) : null;
    $name = ($_POST['name']) ? $_POST['name'] : "Neu";
    $document = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
    $current_folder = $document->get_environment();
    steam_factory::create_document($GLOBALS["STEAM"]->get_id(), tidyName($name) . ".html", stripslashes($content), "text/html", $current_folder, tidyDesc($name));
    echo json_encode(array(
        'success' => true,
        'message' => ''
    ));
}

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param object  $steam Parameter description (if any) ...
 * @param unknown $id    Parameter description (if any) ...
 *
 * @return void
 */
function saveTitleAndTextMessage($steam, $id)
{
    $content = ($_POST['textField']) ? ($_POST['textField']) : null;
    $name = ($_POST['name']) ? $_POST['name'] : "Neu";
    $document = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
    if ($document instanceof steam_link) {
        $document = $document->get_link_object();
    }
    if ($document instanceof steam_container) {
        $current_folder = $document;
        //#######
        $attribute_names = $current_folder->get_attribute_names();
        if (in_array("FOLDER_DISCUSSION", $attribute_names)) {
            $discussion_folder = $current_folder->get_attribute("FOLDER_DISCUSSION");
            if (!($discussion_folder->check_access_write($steam->get_current_steam_user()))) {
                echo json_encode(array(
                    'success' => false,
                    'name' => msg('NO_WRITE_ACCESS')
                ));
                exit;
            }
            //TODO: Übergangsweise wird das Attribut erstellt.
            $discussion_folder->set_attribute("OBJ_TYPE", "LARS_MESSAGES");
        } else {
            $discussion_folder = steam_factory::create_container($GLOBALS["STEAM"]->get_id(), tidyName("Mitteilungen_zu_" . $current_folder->get_name()), $current_folder, tidyDesc(msg('MSG_FOR') . $current_folder->get_attribute("OBJ_DESC")));
            $discussion_folder->set_attribute("LARS_HIDDEN", true);
            $discussion_folder->set_attribute("OBJ_TYPE", "LARS_MESSAGES");
            $current_folder->set_attribute("FOLDER_DISCUSSION", $discussion_folder);
        }
        $discussion_folder = $current_folder->get_attribute("FOLDER_DISCUSSION");
        if (!($discussion_folder->check_access_write($steam->get_current_steam_user()))) {
            echo json_encode(array(
                'success' => false,
                'name' => msg('NO_WRITE_ACCESS')
            ));
            exit;
        }
        steam_factory::create_document($GLOBALS["STEAM"]->get_id(), tidyName($name) . ".html", stripslashes($content), "text/html", $discussion_folder, tidyDesc($name));
    } else {
        $document->set_attribute("OBJ_DESC", tidyDesc($name)); //Name wird nicht geändert bei erneutem editieren
        $document->set_content(stripslashes($content));
    }
    echo json_encode(array(
        'success' => true,
        'name' => msg('MSG_SAVED')
    ));
}

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param object  $steam Parameter description (if any) ...
 * @param unknown $id    Parameter description (if any) ...
 *
 * @return void
 */
function getHtml($steam, $id)
{
    $object = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
    if ($object instanceof steam_document) {
        $current_path = substr($object->get_path(), 0, strrpos($object->get_path(), "/")) . "/";
        $content = $object->get_content();
        $content = $content ? $content : "";
        $content = preg_replace('/\n\n/', '</p>', $content);
        $content = stripslashes($content);
        //        $content = preg_replace('/src="\/([a-z0-9.\-\%_\/]*)"/iU', 'src="' . PATH_URL . '/tools/get.php?object=$1"', $content);
        //        $content = preg_replace('/src="+(?!http)([a-z0-9.\-_\/]*)"/iU', 'src="' . PATH_URL . '/tools/get.php?object=' . $current_path . '$1"', $content);
        $content = preg_replace('/src="([a-z0-9.\- _\/]*)"/iU', 'src="' . PATH_URL . '/get/path' . $current_path . '$1"', $content);
        //        $content = preg_replace('#src="([a-z0-9.\-_\/]*)'.$current_path.'([a-z0-9.\-_\/]*)"#iU', 'src="' . PATH_URL . '/tools/get.php?object=$1' . $current_path . '$1"', $content);
        echo json_encode(array(
            'success' => true,
            'html' => $content
        ));
    } else {
        echo json_encode(array(
            'success' => false
        ));
    }
    $steam->disconnect();
}
