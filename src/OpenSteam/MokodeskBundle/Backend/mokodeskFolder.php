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

/**
 * Description for require_once
 */
require_once dirname(__FILE__) . "/../lib/derive_url.php";

$id = (isset($_POST['node'])) ? ($_POST['node']) : null;
session_name("bidowl_session");
session_start();
$loginName = (isset($_SESSION['user'])) ? ($_SESSION['user']) : null;
$loginPwd = (isset($_SESSION['pass'])) ? ($_SESSION['pass']) : null;
$LANG = (isset($_SESSION['language'])) ? ($_SESSION['language']) : "de";
session_write_close();
$steam = OpenSteam\MokodeskBundle\Backend\MokodeskSteam::connect(STEAM_SERVER, STEAM_PORT, $loginName, $loginPwd);
if (!$steam || !$steam->get_login_status()) {
    //            ErrorException::getTrace();
    echo json_encode(array(
        'success' => false,
        'name' => msg('NO_CONNECTION')
    ));
    exit();
}
$task = ($_POST['task']) ? ($_POST['task']) : null;

switch ($task) {
case "getRoot":
    getRoot($steam, $id);

    break;

case "getResources":
    getResources($steam, $id);

    break;

case "getResourcesLinks":
    getResourcesLinks($steam, $id);

    break;

case "setResource":
    setResource($steam, $id);

    break;

case "folder":
    getFolder($steam, $id);

    break;
}

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param object $steam Parameter description (if any) ...
 * @param string $id    Parameter description (if any) ...
 *
 * @return void
 */
function getRoot($steam, $id)
{
    if ($id == "root") { //hier wird bestimmt welcher Ordner als Wurzelknoten verwendet werden soll
        $current_room = steam_factory::path_to_object($GLOBALS["STEAM"]->get_id(), '/'); //root des servers

    } elseif ($id == "home") { //hier wird bestimmt welcher Ordner als Wurzelknoten verwendet werden soll
        $current_room = $steam->get_current_steam_user()->get_workroom();
    } else {
        $current_room = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
    }
    if ($current_room instanceof steam_container) {
        $inventory = $current_room->get_inventory("", array(
            "DOC_MIME_TYPE",
            "DOC_LAST_MODIFIED",
            "CONT_LAST_MODIFIED",
            "OBJ_LAST_CHANGED",
            "DOC_EXTERN_URL",
            "OBJ_CREATION_TIME"
        ));

        foreach ($inventory as $item) {
            if ($item->get_attribute("bid:hidden") || !($item instanceof steam_container)) {

                continue;
            }
            $leaf = (($item instanceof steam_container) ? false : true);
            $arr[] = array(
                "text" => tidyDesc($item->get_attribute(OBJ_DESC)) ? tidyDesc($item->get_attribute(OBJ_DESC)) : tidyDesc($item->get_attribute(OBJ_NAME)) ,
                "origName" => $item->get_attribute(OBJ_NAME) ,
                "id" => $item->get_id() ,
                "leaf" => $leaf,
                "draggable" => !$leaf,
                //                "OBJ_KEYWORDS"=>$item->get_attribute(OBJ_NAME),
                //                "bidowl"=>$item->get_attribute("bid:subject"),
                //                "OBJ_PATH"=>$item->get_attribute(OBJ_PATH),
                //                "ddGroup"=>"TreeDD"

            );
        }
    } else {
        echo "error->" . $current_room->get_attribute(OBJ_NAME);
    }
    echo json_encode($arr);
    $steam->buffer_flush();
    $steam->disconnect();
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
function getFolder($steam, $id)
{
    $current_room = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
    $available_attributes = array(
        "xsl:attributes",
        "OBJ_URL",
        "OBJ_POSITION_Z",
        "OBJ_LAST_CHANGED",
        "xsl:access",
        "bid:region",
        "OBJ_POSITION_Y",
        "OBJ_NAME",
        "bid:collectiontype",
        "OBJ_DESCS",
        "CONT_SIZE_Z",
        "CONT_EXCHANGE_LINKS",
        "OBJ_PATH",
        "OBJ_WIDTH",
        "OBJ_ANNO_MESSAGE_IDS",
        "CONT_LAST_MODIFIED",
        "OBJ_LANGUAGE",
        "CONT_SIZE_X",
        "bid:doctype",
        "xsl:public",
        "OBJ_LINK_ICON",
        "OBJ_HEIGHT",
        "CONT_USER_MODIFIED",
        "OBJ_POSITION_X",
        "DOC_MIME_TYPE",
        "xsl:annotations",
        "bid:school",
        "OBJ_VERSIONOF",
        "OBJ_KEYWORDS",
        "bid:hidden",
        "web:sort:exits",
        "last_page",
        "bid:grade",
        "bid:presentation",
        "bid:subject",
        "CONT_SIZE_Y",
        "OBJ_CREATION_TIME",
        "xsl:content",
        "OBJ_ANNO_MISSING_IDS",
        "bid:frameset",
        "OBJ_DESC",
        "web:sort:objects",
        "properties",
        "bid:fullscreen",
        "ROOM_TRASHBIN",
        "OBJ_ICON"
    );
    if ($current_room instanceof steam_container) $inventory = $current_room->get_inventory("", array(
        "DOC_MIME_TYPE",
        "DOC_LAST_MODIFIED",
        "CONT_LAST_MODIFIED",
        "OBJ_LAST_CHANGED",
        "DOC_EXTERN_URL",
        "OBJ_CREATION_TIME",
        "GTD_PROJECT"
    ));

    foreach ($inventory as $item) {
        $leaf = ($item instanceof steam_container ? "true" : "false");
        $arr[] = array(
            "text" => $item->get_attribute(OBJ_DESC) ,
            "id" => $item->get_id() ,
            "leaf" => $leaf,
            "ddGroup" => "TreeDD"
        );
    }
    echo json_encode($arr);
    $steam->buffer_flush();
    $steam->disconnect();
}

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param object $steam Parameter description (if any) ...
 * @param string $id    Parameter description (if any) ...
 *
 * @return void
 */
function getResources($steam, $id)
{
    if ($id == "folder") {
        $current_room = $steam->get_current_steam_user()->get_attribute("LARS_RESOURCES");
    } elseif ($id == "links") {
        $current_room = $steam->get_current_steam_user()->get_attribute("USER_BOOKMARKROOM");
    } else {
        $current_room = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
    }
    if ($current_room instanceof steam_link) {
        $current_room = $current_room->get_link_object();
    }
    $arr = array();
    if ($current_room instanceof steam_container) {
        $inventory = $current_room->get_inventory_filtered(array(
            array(
                '-',
                'attribute',
                'bid:hidden',
                '==',
                'hide_always'
            ) ,
            array(
                '-',
                'attribute',
                'bid:hidden',
                '==',
                "1"
            ) ,
            array(
                '-',
                'attribute',
                'bid:hidden',
                '==',
                1
            ) ,
            array(
                '-',
                'attribute',
                'DOC_TYPE',
                '==',
                "ds_store"
            ) ,
            array(
                '-',
                'attribute',
                'DRAWING_TYPE',
                '>',
                0
            ) , // Mediarena Elements
            //                array( '+', 'class', CLASS_ROOM),
            //                array( '-', 'class', CLASS_CONTAINER),
            array(
                '+',
                'class',
                CLASS_OBJECT
            )
        ));
        $items_array = array();

        foreach ($inventory as $key => $item) {
            //    $attributes = $item->get_attribute_names();
            //    foreach ($attributes as $attribute) {
            //        print($attribute."      :      ");
            //        if ($attribute != "OBJ_SCRIPT" and $attribute != "FOLDER_DISCUSSION" and $attribute != "CONT_USER_MODIFIED" and $attribute != "xsl:public" and $attribute != "OBJ_ICON" and $attribute != "DOC_USER_MODIFIED") {
            ////        sleep(5);
            //        print($item->get_attribute($attribute)."<br>\n");
            //        }
            //    }
            $items_array[$key][OBJ_TYPE] = $item->get_attribute(OBJ_TYPE, 1);
            $items_array[$key]["bid:collectiontype"] = $item->get_attribute("bid:collectiontype", 1);
            $items_array[$key]["bid:doctype"] = $item->get_attribute("bid:doctype", 1);
            $items_array[$key]["bid:presentation"] = $item->get_attribute("bid:presentation", 1);
            $items_array[$key][OBJ_PATH] = $item->get_attribute(OBJ_PATH, 1);
            $items_array[$key][DOC_EXTERN_URL] = $item->get_attribute(DOC_EXTERN_URL, 1);
            $items_array[$key][OBJ_DESC] = $item->get_attribute(OBJ_DESC, 1);
            $items_array[$key][OBJ_NAME] = $item->get_attribute(OBJ_NAME, 1);
            $items_array[$key][DOC_MIME_TYPE] = $item->get_attribute(DOC_MIME_TYPE, 1);
        }
        $result = $steam->buffer_flush();

        foreach ($inventory as $key => $item) {
            $items_array[$key][OBJ_TYPE] = $result[$items_array[$key][OBJ_TYPE]];
            $items_array[$key]["bid:collectiontype"] = $result[$items_array[$key]["bid:collectiontype"]];
            $items_array[$key]["bid:doctype"] = $result[$items_array[$key]["bid:doctype"]];
            $items_array[$key]["bid:presentation"] = $result[$items_array[$key]["bid:presentation"]];
            $items_array[$key][OBJ_PATH] = $result[$items_array[$key][OBJ_PATH]];
            $items_array[$key][DOC_EXTERN_URL] = $result[$items_array[$key][DOC_EXTERN_URL]];
            $items_array[$key][OBJ_DESC] = $result[$items_array[$key][OBJ_DESC]];
            $items_array[$key][OBJ_NAME] = $result[$items_array[$key][OBJ_NAME]];
            $items_array[$key][DOC_MIME_TYPE] = $result[$items_array[$key][DOC_MIME_TYPE]];
        }

        foreach ($inventory as $key => $item) {
            $leaf = (($item instanceof steam_container || $item instanceof steam_link) ? false : true);
            //            switch ($item->get_attribute(OBJ_TYPE)) {
            //                case "ASSIGNEMNT_PACKAGE":
            //                    $iconCls = "package";
            //                    break;
            //            }
            $iconCls = "";
            if (substr_compare($items_array[$key][OBJ_TYPE] . "0000", "LARS", 0, 3) === 0) {
                $iconCls = "folderL";
            } elseif ($items_array[$key][OBJ_TYPE] === "ASSIGNMENT_PACKAGE") {
                $iconCls = "report";
            } elseif ($item instanceof steam_link) {
                $iconCls = "folder-link";
            } elseif ($item instanceof steam_docextern) {
                $iconCls = "link";
            } elseif ($items_array[$key]["bid:collectiontype"] === "sequence") {
                $iconCls = "sequence";
            } elseif ($items_array[$key]["bid:collectiontype"] === "cluster") {
                $iconCls = "cluster";
            } elseif ($items_array[$key]["bid:collectiontype"] === "gallery") {
                $iconCls = "gallery";
            } elseif ($items_array[$key]["bid:doctype"] === "portal") {
                $qtip = "http://" . BID_URL . "/explorer/Index/" . $item->get_id();
                $lars_ref = "http://" . BID_URL . "/explorer/Index/" . $item->get_id();
                $iconCls = "portal";
            } elseif ($items_array[$key]["OBJ_TYPE"] === "container_portal_bid") {
                $qtip = "http://" . BID_URL . "/explorer/Index/" . $item->get_id();
                $lars_ref = "http://" . BID_URL . "/explorer/Index/" . $item->get_id();
                $iconCls = "portal";
            } elseif ($items_array[$key]["bid:doctype"] === "questionary") {
                $iconCls = "questionary";
            } elseif ($item instanceof steam_messageboard) {
                $iconCls = "forum";
            } elseif ($item instanceof steam_calendar) {
                $iconCls = "calendar";
            } elseif ($items_array[$key]["bid:presentation"] === "index") {
                $iconCls = "folder_closed_index";
            } elseif ($item instanceof steam_trashbin) {
                $iconCls = "trashbin";
            }
            $filePath = pathinfo($items_array[$key][OBJ_PATH]);
            if (isset($filePath["extension"]) && !$iconCls) $iconCls = $iconCls ? $iconCls : "file-" . $filePath["extension"];
            if ($item instanceof steam_docextern) {
                $qtip = $items_array[$key][DOC_EXTERN_URL];
                $lars_ref = derive_url($items_array[$key][DOC_EXTERN_URL]);
            } else {
                $qtip = '';
                $lars_ref = '';
            }
            if ($iconCls == "sequence" || $iconCls == "gallery" || $iconCls == "cluster" || $iconCls == "questionary" || $iconCls == "folder_closed_index") {
                $qtip = "http://" . BID_URL . "/explorer/Index/" . $item->get_id();
                $lars_ref = "http://" . BID_URL . "/explorer/Index/" . $item->get_id();
            }
            $arr[] = array(
                "text" => tidyDesc($items_array[$key][OBJ_DESC]) ? tidyDesc($items_array[$key][OBJ_DESC]) : $items_array[$key][OBJ_NAME],
                "origName" => $items_array[$key][OBJ_NAME],
                "OBJ_TYPE" => $items_array[$key][OBJ_TYPE],
                "id" => $item->get_id() ,
                "leaf" => $leaf,
                "mimeType" => ($items_array[$key][DOC_MIME_TYPE]) ? $items_array[$key][DOC_MIME_TYPE] : "none",
                "iconCls" => $iconCls,
                "lars_ref" => $lars_ref,
                "qtip" => $qtip,
                "allowDrop" => false
            );
        }
    } else {
        print "error->" . $current_room->get_attribute(OBJ_NAME);
    }
    echo json_encode($arr);
    $steam->buffer_flush();
    $steam->disconnect();
}

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param object $steam Parameter description (if any) ...
 * @param string $id    Parameter description (if any) ...
 *
 * @return void
 */
function getResourcesLinks($steam, $id)
{
    if ($id == "folder") {
        $current_room = $steam->get_current_steam_user()->get_attribute("LARS_RESOURCES");
    } elseif ($id == "links") {
        $current_room = $steam->get_current_steam_user()->get_attribute("USER_BOOKMARKROOM");
    } else {
        $current_room = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
    }
    if ($current_room instanceof steam_link) {
        $current_room = $current_room->get_link_object();
    }
    $arr = array();
    if ($current_room instanceof steam_container) {
        $inventory = $current_room->get_inventory("", array(
            "DOC_MIME_TYPE",
            "DOC_LAST_MODIFIED",
            "CONT_LAST_MODIFIED",
            "OBJ_LAST_CHANGED",
            "DOC_EXTERN_URL",
            "OBJ_CREATION_TIME",
            "GTD_PROJECT"
        ));

        foreach ($inventory as $item) {
            $leaf = (($item instanceof steam_container) ? false : true);
            //            switch ($item->get_attribute(OBJ_TYPE)) {
            //                case "ASSIGNEMNT_PACKAGE":
            //                    $iconCls = "package";
            //                    break;
            //            }
            if ($item instanceof steam_group) {

                continue;
            }
            $iconCls = "";
            $iconCls = (substr_compare($item->get_attribute(OBJ_TYPE) . "0000", "LARS", 0, 3) === 0) ? "folderL" : $iconCls;
            $iconCls = ($item->get_attribute(OBJ_TYPE) === "ASSIGNMENT_PACKAGE") ? "report" : $iconCls;
            $iconCls = ($item instanceof steam_link) ? "link" : $iconCls;
            $iconCls = ($item instanceof steam_docextern) ? "link" : $iconCls;
            $iconCls = ($item instanceof steam_wiki) ? "orangeIcon" : $iconCls;
            $filePath = pathinfo($item->get_attribute("OBJ_PATH"));
            if (isset($filePath["extension"]) && !$iconCls) $iconCls = $iconCls ? $iconCls : "file-" . $filePath["extension"];
            $broken_link = "";
            if ($item instanceof steam_docextern) {
                $qtip = $item->get_attribute(DOC_EXTERN_URL);
                $lars_ref = derive_url($item->get_attribute(DOC_EXTERN_URL));
            } elseif ($item instanceof steam_link) {
                $link_object = $item->get_link_object();
                if (!$link_object) {
                    $broken_link = msg('BROKEN_LINK');
                    $qtip = msg('BROKEN_LINK_QT');
                    $lars_ref = "";
                } else {
                    //                    $qtip =  "http://".$configServerIp."".$link_object->get_path(); //TODO: Fehlerhaften aufruf bei bbobsin beheben
                    $lars_ref = "http://" . BID_URL . "/explorer/Index/" . $link_object->get_id();
                }
            } else {
                $qtip = '';
                $lars_ref = '';
            }
            $arr[] = array(
                "text" => $broken_link . (tidyDesc($item->get_attribute(OBJ_DESC)) ? tidyDesc($item->get_attribute(OBJ_DESC)) : tidyDesc($item->get_attribute(OBJ_NAME))) ,
                "origName" => $item->get_attribute(OBJ_NAME) ,
                "OBJ_TYPE" => $item->get_attribute(OBJ_TYPE) ,
                "id" => $item->get_id() ,
                "leaf" => $leaf,
                "mimeType" => $item->get_attribute("DOC_MIME_TYPE") ,
                "iconCls" => $iconCls,
                "lars_ref" => $lars_ref,
                "qtip" => isset($qtip) ? $qtip : "",
                "allowDrop" => false
                //                "draggable"=>!$leaf
                //                "OBJ_KEYWORDS"=>$item->get_attribute(OBJ_NAME),
                //                "bidowl"=>$item->get_attribute("bid:subject"),
                //                "OBJ_PATH"=>$item->get_attribute(OBJ_PATH),
                //                "ddGroup"=>"TreeDD"

            );
        }
    } else {
        print "error->" . $current_room->get_attribute(OBJ_NAME);
    }
    echo json_encode($arr);
    $steam->buffer_flush();
    $steam->disconnect();
}

/**
 * Short description for function
 *
 * Long description (if any) ...
 *
 * @param object $steam Parameter description (if any) ...
 * @param string $id    Parameter description (if any) ...
 *
 * @return void
 */
function setResource($steam, $id)
{
    //    $name = utf8_decode($_POST['name']) ? utf8_decode($_POST['name']) : "Neuer Link";
    $name = $_POST['name'] ? $_POST['name'] : "neuer link";
    $rootFolder = 99; // bid-owl root folder
    if ($id == "root") { //hier wird bestimmt welcher Ordner als Wurzelknoten verwendet werden soll
        $object_to_link = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $rootFolder); //root des servers

    } elseif ($id == "home") { //hier wird bestimmt welcher Ordner als Wurzelknoten verwendet werden soll
        $object_to_link = $steam->get_current_steam_user()->get_workroom();
    } else {
        $object_to_link = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
    }
    $links_container = $steam->get_current_steam_user()->get_attribute("LARS_RESOURCES");
    $newlink = steam_factory::create_link($GLOBALS["STEAM"]->get_id(), $object_to_link);
    $newlink->set_attribute("OBJ_NAME", tidyName($name));
    $newlink->set_attribute("OBJ_DESC", tidyDesc($name));
    $newlink->move($links_container);
    echo json_encode(array(
        'success' => true
    ));
}
