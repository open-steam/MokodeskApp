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
require_once dirname(__FILE__) . '/mokodeskTools.php';

/**
 * Description for require_once
 */
require_once dirname(__FILE__) . '/../lib/derive_mimetype.php';

session_name("bidowl_session");
session_start();
$loginName = (isset($_SESSION['user'])) ? ($_SESSION['user']) : null;
$loginPwd = (isset($_SESSION['pass'])) ? ($_SESSION['pass']) : null;
session_write_close();
$action = (isset($_POST['aktion'])) ? ($_POST['aktion']) : null;
$id = (isset($_POST['id'])) ? ($_POST['id']) : null;
$hidden = (isset($_POST['bidHidden'])) ? ($_POST['bidHidden']) : false;
$type = (isset($_POST['larsType'])) ? ($_POST['larsType']) : 0;
$steam = OpenSteam\MokodeskBundle\Backend\MokodeskSteam::connect(STEAM_SERVER, STEAM_PORT, $loginName, $loginPwd);
if (!$steam || !$steam->get_login_status()) {
    echo json_encode(array("success" => false, "name" => "Keine Verbindung"));
    exit();
}
switch ($action) {
case "image":
    $current_room = false;
    break;
default:
    if (!$id) {
        echo json_encode(array("success" => false, "name" => "Unbekannter Fehler"));
        exit;
    }
    $current_room = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
    if (!($current_room instanceof steam_container)) {
        $current_room = $current_room->get_environment();
    }
    break;
}

try {
    if (isset($_FILES["file"]) && $_FILES["file"]["name"] != "") {
        if ($_FILES['file']['size'] > 16000000) {
            echo json_encode(array("success" => false, "name" => "Datei ist zu groß oder kann nicht gelesen werden"));
            $steam->disconnect();
            exit;
        }
        $baseFileName = $_FILES["file"]["name"];
        $description = trim($_POST['description']) ? trim($_POST['description']) : $baseFileName;
        $fileContent = file_get_contents($_FILES["file"]["tmp_name"]);
        $mimetype = derive_mimetype($_FILES["file"]["name"]);
        if ($fileContent === false) {
            echo json_encode(array("success" => false, "name" => "Kann Datei nicht von der Festplatte laden"));
            exit;
        }
        $newDocument = steam_factory::create_document($GLOBALS["STEAM"]->get_id(), tidyName($baseFileName), $fileContent, $mimetype, $current_room, tidyDesc($description));
        $newDocument->set_attribute("bid:hidden", $hidden);
        $newDocument->set_attribute("LARS_TYPE", $type);
        if ($action === "image") {
            $steam->get_current_steam_user()->set_attribute("OBJ_ICON", $newDocument);
        }
        echo json_encode(array('success' => true, 'fileName' => PATH_URL . "/get/" . $newDocument->get_id()));
    } elseif (!isset($_FILES["file"]) || $_FILES["file"]["name"] != "") {
        echo json_encode(array("success" => false, "name" => "Files nicht gesetzt"));
    }
} catch (Exception $e) {
    error_log("exception: " . $e->getMessage());
    echo json_encode(array("success" => false, "name" => "LoginException"));
}
$steam->disconnect();
