<?php
/**
 * user_pwd_reset_form.php
 *
 * Reset screen of a password's user
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2019 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";
  $returnLocation = "../admin/user_list.php";
  $isMd5 = true;

  /**
   * Checking for get vars. Go back to $returnLocation if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["id_user"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  /**
   * Retrieving get vars
   */
  $idUser = intval($_GET["id_user"]);

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../model/Query/User.php");

    /**
     * Search database
     */
    $userQ = new Query_User();
    if ( !$userQ->select($idUser) )
    {
      $userQ->close();

      FlashMsg::add(_("That user does not exist."), OPEN_MSG_ERROR);
      header("Location: " . $returnLocation);
      exit();
    }

    $user = $userQ->fetch();
    if ($user)
    {
      $formVar["id_user"] = $idUser;
      $formVar["login"] = $user->getLogin();
      $formVar["pwd"] = $formVar["pwd2"] = "";
      //$formVar["pwd"] = $formVar["pwd2"] = $user->getPwd(); // no because it's encoded
      //AppError::debug($user->getPwd());
    }
    else
    {
      AppError::fetch($userQ, false);
    }
    $userQ->freeResult();
    $userQ->close();
    unset($userQ);
    unset($user);
  }

  /**
   * Show page
   */
  require_once("../lib/ResetFormError.php");
  $resetPageLevel = ob_get_level();
  ob_start();
  try
  {
    $title = _("Reset User Password");
    $focusFormField = "pwd";
    require_once("../layout/header.php");

    /**
     * Breadcrumb
     */
    $links = array(
      _("Admin") => "../admin/index.php",
      _("Users") => $returnLocation,
      $title => ""
    );
    echo HTML::breadcrumb($links, "icon icon_user");
    unset($links);

    echo Form::errorMsg();

    /**
     * Edit form
     * @todo use user_fields.php with some controlling var to display adecuated fields
     */
    echo HTML::start('form',
      array(
        'id' => 'userPwd',
        'method' => 'post',
        'action' => '../admin/user_pwd_reset.php'
      )
    );

    echo Form::hidden("id_user", $formVar["id_user"]);
    echo Form::hidden("login", $formVar["login"]);

    echo Form::hidden("md5");
    echo Form::hidden("md5_confirm");

    $tbody = array();

    $row = _("Login") . ": ";
    $row .= HTML::tag('strong', $formVar["login"]);

    $tbody[] = $row;

    $row = Form::label("pwd", _("Password") . ":");
    $row .= Form::password("pwd",
      isset($formVar["pwd"]) ? $formVar["pwd"] : null,
      array(
        'size' => 20,
        'error' => isset($formError["pwd"]) ? $formError["pwd"] : null
      )
    );

    $tbody[] = $row;

    $row = Form::label("pwd2", _("Re-enter Password") . ":");
    $row .= Form::password("pwd2",
      isset($formVar["pwd2"]) ? $formVar["pwd2"] : null,
      array(
        'size' => 20,
        'error' => isset($formError["pwd2"]) ? $formError["pwd2"] : null
      )
    );

    $tbody[] = $row;

    $tfoot = array(
      Form::button("change", _("Submit"))
    );

    echo Form::fieldset($title, $tbody, $tfoot);

    echo HTML::end('form');

    echo HTML::para(HTML::link(_("Return"), $returnLocation));

    Form::unsetSession();
    require_once("../layout/footer.php");
    ob_end_flush();
  }
  catch (Throwable $error)
  {
    ResetFormError::discardOutput($resetPageLevel);
    $resetErrorId = ResetFormError::report($error);
    header("Content-Type: text/html; charset=UTF-8");
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8">'
      . '<title>Reset User Password</title></head><body>'
      . ResetFormError::body($resetErrorId) . '</body></html>';
  }
