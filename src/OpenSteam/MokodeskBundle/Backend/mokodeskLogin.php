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

    session_name("bidowl_session");
    session_start();
    unset($_SESSION['usersOn']);
    unset($_SESSION['usersOff']);

    $loginName = isset($_SESSION["login_name"]) ?  $_SESSION['login_name'] : '';
    $loginPwd = isset($_SESSION["login_pwd"]) ? $_SESSION['login_pwd'] : '';
    $LANG = ($_SESSION['language']) ? ($_SESSION['language']) : "de";
    $steam = OpenSteam\MokodeskBundle\Backend\MokodeskSteam::connect(STEAM_SERVER,
                                    STEAM_PORT,
                                    $loginName,
                                    $loginPwd);
    if ($_REQUEST['version'] != MOKODESK_VERSION) {
        error_log("lars_login fail: wrong version");
        print (json_encode(array("success" => false, "name"=>msg('REFRESH_BROWSER_1'). MOKODESK_VERSION .msg('REFRESH_BROWSER_2'), "version"=>true)));
        exit;
    } elseif (!$steam || !$steam->get_login_status() ) {
        print (json_encode(array("success" => false, "name"=>msg('LOGIN_FAILED'), "version"=>true)));
        exit;
    } else {
        $steam_user = $steam->get_current_steam_user();
        $steam_workroom = $steam_user->get_workroom(1);
        $attributes = $steam_user->get_attribute_names(1);
        $result = $steam->buffer_flush();
        $steam_workroom = $result[$steam_workroom];
        $attributes = $result[$attributes];

        /*
         * Check for Mokodesk Permission
         * this has to be done before creating the folders ;)
         */
        $allowedGroup = steam_factory::get_group($GLOBALS["STEAM"]->get_id(), MOKODESK_ALLOWED_GROUP_NAME);
        if (!($allowedGroup instanceof steam_group)) {
            print (json_encode(array("success" => false, "name"=>"Config error MOKODESK_ALLOWED_GROUP_NAME is missing.")));
            exit;
        }
        $allowed = false;
        if ($steam_user->get_name() === "root") {
            $allowed = true;
        } else {
            $allowed = $allowedGroup->is_member($steam_user);
        }
        if (!$allowed) {
            print (json_encode(array("success" => false, "name"=>msg('LOGIN_FAILED') . "<br>" . msg('NO_MOKODESK_PERMISSION'), "version"=>true)));
            exit;
        }

        $chatGroup = steam_factory::get_group(
/**
 * Description for $GLOBALS
 * @global object $GLOBALS["STEAM"]
 * @name $STEAM
 */
$GLOBALS["STEAM"]->get_id(), MOKODESK_ALLOWED_CHATGROUP_NAME);
        $teacherGroup = steam_factory::get_group(
/**
 * Description for $GLOBALS
 * @global object $GLOBALS["STEAM"]
 * @name $STEAM
 */
$GLOBALS["STEAM"]->get_id(), MOKODESK_TEACHER_GROUP_NAME);

        if (!($chatGroup instanceof steam_group)) {
            print (json_encode(array("success" => false, "name"=>"Config error MOKODESK_ALLOWED_CHATGROUP_NAME={$MOKODESK_ALLOWED_CHATGROUP_NAME} is missing.", "version"=>true)));
            exit;
        }
        if (!($teacherGroup instanceof steam_group)) {
            print (json_encode(array("success" => false, "name"=>"Config error MOKODESK_TEACHER_GROUP_NAME={$MOKODESK_TEACHER_GROUP_NAME} is missing.", "version"=>true)));
            exit;
        }
        $desktop_attributes_array = array();
        $desktop_attributes_array["LARS_DESKTOP"] = $steam_user->get_attribute("LARS_DESKTOP", 1);
        $desktop_attributes_array["LARS_ARCHIV"] = $steam_user->get_attribute("LARS_ARCHIV", 1);
        $desktop_attributes_array["LARS_RESOURCES"] = $steam_user->get_attribute("LARS_RESOURCES", 1);
//		$desktop_attributes_array["MOKO_OWN_SITE"] = $steam_user->get_attribute("MOKO_OWN_SITE", 1);
        $desktop_attributes_array["LARS_SCHUELER"] = $steam_user->get_attribute("LARS_SCHUELER", 1);
        $desktop_attributes_array["LARS_ABO"] = $steam_user->get_attribute("LARS_ABO", 1);
        $desktop_attributes_array["USER_CURRENT_LOGIN_LARS"] = $steam_user->get_attribute("USER_CURRENT_LOGIN_LARS", 1);
        $desktop_attributes_array["USER_TRASHBIN"] = $steam_user->get_attribute("USER_TRASHBIN", 1);
        $desktop_attributes_array["LARS_IMAGE_HEIGHT"] = $steam_user->get_attribute("LARS_IMAGE_HEIGHT", 1);
        $desktop_attributes_array["LARS_EAST_COLLAPSED"] = $steam_user->get_attribute("LARS_EAST_COLLAPSED", 1);
        $desktop_attributes_array["LARS_TITLE"] = $steam_user->get_attribute("LARS_TITLE", 1);
        $desktop_attributes_array["MOKO_SUBSCRIPTION_CHECK"] = $steam_user->get_attribute("MOKO_SUBSCRIPTION_CHECK", 1);

        $result = $steam->buffer_flush();

        $desktop_attributes_array["LARS_DESKTOP"] = $result[$desktop_attributes_array["LARS_DESKTOP"]];
        $desktop_attributes_array["LARS_ARCHIV"] = $result[$desktop_attributes_array["LARS_ARCHIV"]];
        $desktop_attributes_array["LARS_RESOURCES"] = $result[$desktop_attributes_array["LARS_RESOURCES"]];
//		$desktop_attributes_array["MOKO_OWN_SITE"] = $result[$desktop_attributes_array["MOKO_OWN_SITE"]];
        $desktop_attributes_array["LARS_SCHUELER"] = $result[$desktop_attributes_array["LARS_SCHUELER"]];
        $desktop_attributes_array["LARS_ABO"] = $result[$desktop_attributes_array["LARS_ABO"]];
        $desktop_attributes_array["USER_CURRENT_LOGIN_LARS"] = $result[$desktop_attributes_array["USER_CURRENT_LOGIN_LARS"]];
        $desktop_attributes_array["USER_TRASHBIN"] = $result[$desktop_attributes_array["USER_TRASHBIN"]];
        $desktop_attributes_array["LARS_IMAGE_HEIGHT"] = $result[$desktop_attributes_array["LARS_IMAGE_HEIGHT"]];
        $desktop_attributes_array["LARS_EAST_COLLAPSED"] = $result[$desktop_attributes_array["LARS_EAST_COLLAPSED"]];
        $desktop_attributes_array["LARS_TITLE"] = $result[$desktop_attributes_array["LARS_TITLE"]];
        $desktop_attributes_array["MOKO_SUBSCRIPTION_CHECK"] = $result[$desktop_attributes_array["MOKO_SUBSCRIPTION_CHECK"]];

            //First time creation of the users Lars room
            if (!($desktop_attributes_array["LARS_DESKTOP"] instanceof steam_container)) {
                $new_lars_room = steam_factory::create_room(
/**
 * Description for $GLOBALS
 * @global object $GLOBALS["STEAM"]
 * @name $STEAM
 */
$GLOBALS["STEAM"]->get_id(), "MokoDesk", $steam_workroom, "MokoDesk");
                $new_lars_room->set_attribute("OBJ_TYPE", "LARS_DESKTOP");
//				if (!($lehrerGruppe->is_member($steam_user))) {
//					$new_lars_room->set_sanction_all($lehrerGruppe);
//				}
                $steam_user->set_attribute("LARS_DESKTOP", $new_lars_room);
                $desktop_attributes_array["LARS_DESKTOP"] = $new_lars_room;
            }

            $desktop_attributes_array["LARS_DESKTOP_DISCUSSION"] = $desktop_attributes_array["LARS_DESKTOP"]->get_attribute("FOLDER_DISCUSSION");
            if (!($desktop_attributes_array["LARS_DESKTOP_DISCUSSION"] instanceof steam_container)) {
                $new_lars_room = steam_factory::create_room(
/**
 * Description for $GLOBALS
 * @global object $GLOBALS["STEAM"]
 * @name $STEAM
 */
$GLOBALS["STEAM"]->get_id(), msg('NAME_MESSAGES_FOLDER'), $desktop_room, msg('NAME_MESSAGES_FOLDER_DESC'));
                $new_lars_room->set_attribute("OBJ_TYPE", "LARS_MESSAGES");
                $desktop_attributes_array["LARS_DESKTOP"]->set_attribute("FOLDER_DISCUSSION", $new_lars_room);
                $desktop_attributes_array["LARS_DESKTOP_DISCUSSION"] = $new_lars_room;
            }
            if (!($desktop_attributes_array["LARS_ARCHIV"] instanceof steam_container)) {
                $larsDesktop = $desktop_attributes_array["LARS_DESKTOP"];
                $new_lars_archiv = steam_factory::create_room(
/**
 * Description for $GLOBALS
 * @global object $GLOBALS["STEAM"]
 * @name $STEAM
 */
$GLOBALS["STEAM"]->get_id(), msg('NAME_ARCHIV_FOLDER'), $larsDesktop, msg('NAME_ARCHIV_FOLDER_DESC'));
                $new_lars_archiv->set_attribute("OBJ_TYPE", "LARS_ARCHIV");
                $steam_user->set_attribute("LARS_ARCHIV", $new_lars_archiv);
                $larsDesktop->set_attribute("LARS_ARCHIV", $new_lars_archiv);
                $new_lars_archiv->set_attribute("LARS_HIDDEN", true);
                $desktop_attributes_array["LARS_ARCHIV"] = $new_lars_archiv;
            }
            if (!($desktop_attributes_array["LARS_RESOURCES"] instanceof steam_container)) {
                $larsDesktop = $desktop_attributes_array["LARS_DESKTOP"];
                $new_lars_resource = steam_factory::create_room(
/**
 * Description for $GLOBALS
 * @global object $GLOBALS["STEAM"]
 * @name $STEAM
 */
$GLOBALS["STEAM"]->get_id(), msg('NAME_RESOURCE_FOLDER'), $larsDesktop, msg('NAME_RESOURCE_FOLDER_DESC'));
                $new_lars_resource->set_attribute("OBJ_TYPE", "LARS_RESOURCE");
                $steam_user->set_attribute("LARS_RESOURCES", $new_lars_resource);
                $new_lars_resource->set_attribute("LARS_HIDDEN", true);
                $desktop_attributes_array["LARS_RESOURCES"] = $new_lars_resource;
            }
//			if (!($desktop_attributes_array[MOKO_OWN_SITE] instanceof steam_document)) {
//				$larsDesktop = $desktop_attributes_array["LARS_DESKTOP"];
//				$new_lars_resource = steam_factory::create_room($steam, msg('NAME_OWN_SITE'), $larsDesktop, msg('NAME_OWN_SITE_DESC'));
//				$object_to_copy1 = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), 487677 );
//				$object_to_copy2 = steam_factory::get_object($GLOBALS["STEAM"]->get_id(), 487678 );
//				$object_copy1 = steam_factory::create_copy( $steam, $object_to_copy1 );
//				$object_copy2 = steam_factory::create_copy( $steam, $object_to_copy2 );
//				$object_copy1->move($new_lars_resource);
//				$object_copy2->move($new_lars_resource);
//				$new_lars_resource->set_attribute("OBJ_TYPE", "MOKO_OWN_SITE");
//				$steam_user->set_attribute("MOKO_OWN_SITE", $object_copy1);
//				$new_lars_resource->set_attribute("bid:hidden", 1);
//				$new_lars_resource->set_attribute("LARS_HIDDEN", true);
//				$desktop_attributes_array[MOKO_OWN_SITE] = $object_copy1;
//
//			}
            if (!($desktop_attributes_array["LARS_SCHUELER"] instanceof steam_container)) {
                $larsDesktop = $desktop_attributes_array["LARS_DESKTOP"];
                $new_lars_resource = steam_factory::create_room(
/**
 * Description for $GLOBALS
 * @global object $GLOBALS["STEAM"]
 * @name $STEAM
 */
$GLOBALS["STEAM"]->get_id(), msg('NAME_OTHER_PERSON_DESKS'), $larsDesktop, msg('NAME_OTHER_PERSON_DESKS_DESC'));
                $new_lars_resource->set_attribute("OBJ_TYPE", "LARS_SCHUELER");
                $steam_user->set_attribute("LARS_SCHUELER", $new_lars_resource);
                $new_lars_resource->set_attribute("LARS_HIDDEN", true);
                $new_lars_resource->set_attribute("bid:hidden", 1);
                $desktop_attributes_array["LARS_SCHUELER"] = $new_lars_resource;
            }
            if (!($desktop_attributes_array["MOKO_SUBSCRIPTION_CHECK"] instanceof steam_container)) {
                $larsDesktop = $desktop_attributes_array["LARS_DESKTOP"];
                $new_lars_resource = steam_factory::create_room(
/**
 * Description for $GLOBALS
 * @global object $GLOBALS["STEAM"]
 * @name $STEAM
 */
$GLOBALS["STEAM"]->get_id(), "Abonnement neuer Dokumnente", $larsDesktop, "Abonnement neuer Dokumnente");
                $new_lars_resource->set_attribute("OBJ_TYPE", "MOKO_SUBSCRIPTION_CHECK");
                $steam_user->set_attribute("MOKO_SUBSCRIPTION_CHECK", $new_lars_resource);
                $new_lars_resource->set_attribute("LARS_HIDDEN", true);
                $new_lars_resource->set_attribute("bid:hidden", 1);
                $desktop_attributes_array["MOKO_SUBSCRIPTION_CHECK"] = $new_lars_resource;
            }
            if (!($desktop_attributes_array["LARS_ABO"] instanceof steam_container)) {
                $larsDesktop = $desktop_attributes_array["LARS_DESKTOP"];
                $new_lars_resource = steam_factory::create_room(
/**
 * Description for $GLOBALS
 * @global object $GLOBALS["STEAM"]
 * @name $STEAM
 */
$GLOBALS["STEAM"]->get_id(), msg('NAME_OTHER_DESKS'), $larsDesktop, msg('NAME_OTHER_DESKS_DESC'));
                $new_lars_resource->set_attribute("OBJ_TYPE", "LARS_ABO");
                $steam_user->set_attribute("LARS_ABO", $new_lars_resource);
                $new_lars_resource->set_attribute("LARS_HIDDEN", true);
                $new_lars_resource->set_attribute("bid:hidden", 1);
                $desktop_attributes_array["LARS_AB"] = $new_lars_resource;
            }
            $steam_user->set_attribute("USER_LAST_LOGIN_LARS", $desktop_attributes_array["USER_CURRENT_LOGIN_LARS"], 1);
            $steam_user->set_attribute("USER_CURRENT_LOGIN_LARS", time(), 1);

        $message = "";
//		$steam_user->get_attribute("LARS_ARCHIV")->set_attribute("bid:hidden", 1);
//		$steam_user->get_attribute("LARS_RESOURCES")->set_attribute("bid:hidden", 1);
//		$steam_user->get_attribute("LARS_SCHUELER")->set_attribute("bid:hidden", 1);
////		$steam_user->get_attribute("LARS_LINKS")->set_attribute("bid:hidden", 1);
//		$steam_user->get_attribute("LARS_ABO")->set_attribute("bid:hidden", 1);
//		$steam_user->get_attribute("LARS_DESKTOP")->get_attribute("FOLDER_DISCUSSION")->set_attribute("bid:hidden", 0);
//		$steam_user->get_attribute("LARS_DESKTOP")->get_attribute("FOLDER_DISCUSSION")->set_attribute("LARS_HIDDEN", 1);
//		$steam->get_server_module("groups");
//		$groupEveryone = steam_factory::groupname_to_object($steam,"mokochat");
        $groupEveryone = steam_factory::get_object(
/**
 * Description for $GLOBALS
 * @global object $GLOBALS["STEAM"]
 * @name $STEAM
 */
$GLOBALS["STEAM"]->get_id(),59);//TODO: Keine feste ID!
        $discussion_user = $desktop_attributes_array["LARS_DESKTOP_DISCUSSION"];
        $desktop_attributes_array["LARS_DESKTOP_DISCUSSION"]->set_attribute("OBJ_TYPE", "LARS_MESSAGES", 1);
        $discussion_user->sanction(0x00000019, $groupEveryone);	//insert and write and read

//		$chatGroup = steam_factory::get_object($GLOBALS["STEAM"]->get_id(),553498);
//		$teacherGroup = steam_factory::get_object($GLOBALS["STEAM"]->get_id(),553498); //TODO
        $larsBin = $desktop_attributes_array["USER_TRASHBIN"]->get_id();
        $larsDesktop = $desktop_attributes_array["LARS_DESKTOP"]->get_id();
        $larsArchiv = $desktop_attributes_array["LARS_ARCHIV"]->get_id();
//		$larsOwnSite = $desktop_attributes_array[MOKO_OWN_SITE]->get_id();
        $imageHeight = $steam_user->get_attribute("LARS_IMAGE_HEIGHT", 1);
        $eastCollapsed = $steam_user->get_attribute("LARS_EAST_COLLAPSED", 1);
        $videoChat = $chatGroup->is_member($steam_user, 1);
        $isTeacher = $teacherGroup->is_member($steam_user, 1);
        $title = $steam_user->get_attribute("LARS_TITLE", 1);

        $result = $steam->buffer_flush();

        $imageHeight = $result[$imageHeight];
        $eastCollapsed = $result[$eastCollapsed];
        $videoChat = $result[$videoChat];
        $isTeacher = $result[$isTeacher];
        $title = $result[$title];

        if (!$title) {
            $title = msg('NAME_EMPTY_TITLE_DESK');
        }
        if (!$imageHeight) {
            $imageHeight = 120;
        }
//		$lastLoginDays = date('z', time()) - date('z', $desktop_attributes_array["USER_CURRENT_LOGIN_LARS"]);
//		$lastLoginDays = $lastLoginDays > 1 ? "vor ".$lastLoginDays." Tagen" : "war gestern";
//		$lastLoginDays = (date('Y-m-d', time()) ==  date('Y-m-d', $desktop_attributes_array["USER_CURRENT_LOGIN_LARS"])) ? "heute ".date('H:i', $desktop_attributes_array["USER_CURRENT_LOGIN_LARS"])." Uhr" : $lastLoginDays;
        print (json_encode(array(
            "success" => true,
//			"type" => $type,
            "name" => $message,
            "title" => $title,
            "imageHeight" => $imageHeight,
            "larsDesktop" => $larsDesktop,
            "larsArchiv" => $larsArchiv,
            "eastCollapsed" => $eastCollapsed,
            "larsBin" => $larsBin,
//			larsOwnSite => $larsOwnSite,
            "vc" => $videoChat,
            "isTeacher" => $isTeacher,
            "user" => $loginName,
            "pass" => $loginPwd, //used for video chat
            "lastLoginUnix" => $desktop_attributes_array["USER_CURRENT_LOGIN_LARS"],
            "loginTime" => date('H:i', time()),
            "loginTimeLast" => date('d.m.Y H:i', $desktop_attributes_array["USER_CURRENT_LOGIN_LARS"])
            )));
        $_SESSION['user'] = $loginName;
        $_SESSION['pass'] = $loginPwd;
//		$_SESSION['group'] = $type;
    }
$steam->disconnect();
