<?php
/**
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 */
class ResetFormError
{
  public static function discardOutput($level)
  {
    while (ob_get_level() > $level)
    {
      ob_end_clean();
    }
  }
  public static function handleFailure(Throwable $error)
  {
    http_response_code(500);
    unset($_SESSION['form']['var'], $_SESSION['form']['error']);
    try
    {
      $id = bin2hex(random_bytes(8));
    }
    catch (Throwable $ignored)
    {
      $id = str_replace('.', '', uniqid('reset-', true));
    }
    $log = gmdate('Y-m-d\TH:i:s\Z') . ' [reset-form:' . $id . "]\n"
      . 'Error: ' . self::symbol(get_class($error))
      . " while displaying the reset-password form (message redacted)\n"
      . 'File: ' . self::location($error->getFile(), $error->getLine()) . "\n"
      . "Stack trace:\n";
    foreach ($error->getTrace() as $index => $frame)
    {
      $log .= '#' . $index . ' '
        . self::location(isset($frame['file']) ? $frame['file'] : '',
            isset($frame['line']) ? $frame['line'] : 0)
        . ' ' . self::symbol(isset($frame['class']) ? $frame['class'] : '')
        . (isset($frame['class']) ? '::' : '')
        . self::symbol(isset($frame['function']) ? $frame['function'] : '') . "\n";
    }
    $logLevel = ob_get_level();
    ob_start();
    try
    {
      @error_log($log);
    }
    catch (Throwable $ignored)
    {
    }
    finally
    {
      self::discardOutput($logLevel);
    }
    return $id;
  }
  private static function symbol($value)
  {
    return preg_match('/^[a-zA-Z0-9_\\\\:{}]*$/D', $value) ? $value : '[anonymous]';
  }
  private static function location($file, $line)
  {
    $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    $relative = strpos($file, $root) === 0 ? substr($file, strlen($root)) : '[external]';
    if (!preg_match('~^[a-zA-Z0-9_./-]+\\.php$~D', $relative)
        || strpos($relative, '..') !== false)
    {
      $relative = '[external]';
    }
    return $relative . ':' . (int) $line;
  }
  public static function body($id)
  {
    return '<h1>Unable to load the reset-password form.</h1>'
      . '<p>Please try again. If the problem continues, contact your administrator.</p>'
      . '<p>Error ID: ' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '</p>'
      . '<p><a href="../admin/user_list.php">Users</a></p>';
  }
}
