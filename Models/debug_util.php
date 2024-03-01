<?php 
define("gLogFileName", "log.txt");

/**
 * Container for debug purposes functions
 */
class DebugUtil {
    /**
     * Write a message into the default log file
     */
    public static function WriteMessageIntoFile(string $message, bool $isRewrite = false)
    {
        $openMode = ($isRewrite ? "w" : "a");
        $file = fopen(gLogFileName, $openMode);
        $message .= "\n";

        if (!$file)
            return;

        fwrite($file, $message);
        fclose($file);
    }

    /**
     * Writes a message in the format [LOG:{$fileName}:{$line}], both can be respectively
     * replaced by __FILE__ and __LINE__
     */
    public static function LogIntoFile(string $fileName, int $line, string|null $message = "", bool $isRewrite = false)
    {
        $message = "[LOG:{$fileName}:{$line}]: {$message}";
        DebugUtil::WriteMessageIntoFile($message, $isRewrite);
    }

    /**
     * Dumps a var as a log
     */
    public static function DumpVar(string $fileName, int $line, $var)
    {
        DebugUtil::LogIntoFile($fileName, $line, var_export($var, true));
    }

    /**
     * Exports all the vars specified as a list of
     * logs
     */
    public static function ExportVars(string $fileName, int $line, ... $vars)
    {
        foreach ($vars as $var) {
            DebugUtil::LogIntoFile($fileName, $line, var_export($var, true));
        }
    }

    /**
     * 
     */
    public static function Assert($exp, string|null $throw, string $func = "", string $desc = "")
    {
        $message = (is_null($throw) ? "" : $throw);
        $template = "[ASSERT ({$func})]: Message: {$message}\nDescription: {$desc}";

        if (!$exp) {
            DebugUtil::WriteMessageIntoFile($template, false);
            exit(1);
        }
    }
}
?>