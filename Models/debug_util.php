<?php 
define("gLogFileName", "log.txt");

/**
 * Container for debug purposes functions
 */
class DebugUtil {
    /**
     * 
     */
    public static function WriteMessageIntoFile(string $message)
    {
        $file = fopen(gLogFileName, "a");
        $message .= "\n";
        if (!$file)
            return;

        fwrite($file, $message);
        fclose($file);
    }
    /**
     * Opens a log.txt file in the relative directory of the file from where
     * this function is called, prints a message, $fileName and $line can be replaced
     * with macro defines __FILE__ and __LINE__
     */
    public static function LogIntoFile(string $fileName, int $line, string|null $message = "")
    {
        $message = "[LOG:{$fileName}:{$line}]: {$message}";
        DebugUtil::WriteMessageIntoFile($message);
    }
}
?>