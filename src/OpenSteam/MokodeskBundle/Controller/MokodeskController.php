<?php

namespace OpenSteam\MokodeskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    OpenSteam\MokodeskBundle\Backend\MokodeskSteam;

require_once dirname(__FILE__) . '/../etc/default.def.php';

class MokodeskController extends Controller
{
    public function indexAction(Request $request)
    {
        session_name("bidowl_session");
        session_start();
        if ($request->getMethod() == 'POST') {
            $loginName = isset($_POST['username']) ? ($_POST['username']) : "";
            $loginPwd = isset($_POST['password']) ? ($_POST['password']) : "";
        } else {
            if (isset($_SESSION["login_name"])) {
                $loginName = isset($_SESSION["login_name"]) ?  $_SESSION['login_name'] : "";
                $loginPwd = isset($_SESSION["login_pwd"]) ? $_SESSION['login_pwd'] : "";
            } else {
                $loginName = "";
                $loginPwd = "";
            }
        }
        $steam = MokodeskSteam::connect(STEAM_SERVER, STEAM_PORT, $loginName, $loginPwd);
        if (!$steam || !$steam->get_login_status()) {
            session_destroy();

            return $this->render('OpenSteamMokodeskBundle:Mokodesk:login.html.twig', array("version" => MOKODESK_VERSION));
        } else {
            $_SESSION['user'] = $loginName;
            $_SESSION['pass'] = $loginPwd;
            $_SESSION["login_name"] = $loginName;
            $_SESSION["login_pwd"] = $loginPwd;
            if (!isset($_SESSION['lang'])) {
                $steam_user = $steam->get_current_steam_user();
                if (!isset($_POST['lang']) || $_POST['lang'] === "last") {
                    $lang = $steam_user->get_attribute("LARS_LANGUAGE");
                    if ($lang !== 0) {
                        $language_selected = $lang;
                    } else {
                        $language_selected = "de";
                    }
                } else {
                    $language_selected = $_POST['lang'];
                }
                $_SESSION['lang'] = $language_selected;
                $_SESSION['language'] = substr($_SESSION['lang'], 0, 2);
                $steam_user->set_attribute("LARS_LANGUAGE", $language_selected);
            }
            session_write_close();
            $included_js = array(
                        "last" => "",
                        "de" => "mokodeskDe_formal",
                        "de_lars" => "mokodeskDe",
                        "fr" => "mokodeskFr",
                        "en" => "mokodeskEn_formal",
                        "en_lars" => "mokodeskEn",
                        //"debug" => "LarsSchreibtisch.js",
                        );
            if ($request->getMethod() == 'POST') {
                return new RedirectResponse('/');
            } else {
                return $this->render('OpenSteamMokodeskBundle:Mokodesk:mokodesk.html.twig', array("mainjs" => $included_js[$_SESSION['lang']], "version" => MOKODESK_VERSION));
            }
        }
    }

    public function previewAction(Request $request)
    {
/*        session_name("bidowl_session");
        session_start();
        if (isset($_SESSION["login_name"])) {
            $loginName = isset($_SESSION["login_name"]) ?  $_SESSION['login_name'] : "";
            $loginPwd = isset($_SESSION["login_pwd"]) ? $_SESSION['login_pwd'] : "";
        } else {
            $loginName = "";
            $loginPwd = "";
        }
        $steam = MokodeskSteam::connect(STEAM_SERVER, STEAM_PORT, $loginName, $loginPwd);
        if (!$steam || !$steam->get_login_status()) {
            session_destroy();

            return new RedirectResponse('/');
        } else {*/

            return $this->render('OpenSteamMokodeskBundle:Mokodesk:mokodeskPreview.html.twig', array("version" => MOKODESK_VERSION));
//        }
    }

    public function loginAction(Request $request)
    {
        include_once dirname(__FILE__) . '/../Backend/mokodeskLogin.php';
        exit;
    }

    public function logoutAction(Request $request)
    {
        session_name("bidowl_session");
        session_start();
        session_destroy();

        return new RedirectResponse('/');
    }

    public function dataAction(Request $request)
    {
        include_once dirname(__FILE__) . '/../Backend/mokodeskData.php';
        exit;
    }

    public function folderAction(Request $request)
    {
        include_once dirname(__FILE__) . '/../Backend/mokodeskFolder.php';
        exit;
    }

    public function updateAction(Request $request)
    {
        include_once dirname(__FILE__) . '/../Backend/mokodeskUpdate.php';
        exit;
    }

    public function uploadAction(Request $request)
    {
        include_once dirname(__FILE__) . '/../Backend/mokodeskUploadFile.php';
        exit;
    }

    public function editAction(Request $request)
    {
        include_once dirname(__FILE__) . '/../Backend/mokodeskEdit.php';
        exit;
    }

    public function getpathAction($path)
    {
        session_name("bidowl_session");
        session_start();
        $loginName = (isset($_SESSION['user'])) ? ($_SESSION['user']) : "";
        $loginPwd = (isset($_SESSION['pass'])) ? ($_SESSION['pass']) : "";
        session_write_close();
        $steam = MokodeskSteam::connect(STEAM_SERVER, STEAM_PORT, $loginName, $loginPwd);

        if (!$steam || !$steam->get_login_status()) {
            exit();
        }

        $object = \steam_factory::path_to_object($GLOBALS["STEAM"]->get_id(), $path);
        if ($object instanceof \steam_document) {
            $object->download();
        }
        exit;
    }

    public function getAction($id)
    {
        session_name("bidowl_session");
        session_start();
        $loginName = (isset($_SESSION['user'])) ? ($_SESSION['user']) : "";
        $loginPwd = (isset($_SESSION['pass'])) ? ($_SESSION['pass']) : "";
        session_write_close();
        $steam = MokodeskSteam::connect(STEAM_SERVER, STEAM_PORT, $loginName, $loginPwd);

        if (!$steam || !$steam->get_login_status()) {
            exit();
        }

        $object = \steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
        if ($object instanceof \steam_document) {
            $object->download();
        }
        exit;
    }

    public function thumbnailAction($id, $width, $height)
    {
        session_name("bidowl_session");
        session_start();
        $loginName = (isset($_SESSION['user'])) ? ($_SESSION['user']) : "";
        $loginPwd = (isset($_SESSION['pass'])) ? ($_SESSION['pass']) : "";
        session_write_close();
        $steam = MokodeskSteam::connect(STEAM_SERVER, STEAM_PORT, $loginName, $loginPwd);

        if (!$steam || !$steam->get_login_status()) {
            exit();
        }

        $object = \steam_factory::get_object($GLOBALS["STEAM"]->get_id(), $id);
        if ($object instanceof \steam_document) {
            $object->download(DOWNLOAD_IMAGE, array($width, $height));
        }
        exit;
    }
}
