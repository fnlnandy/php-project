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
}
?>