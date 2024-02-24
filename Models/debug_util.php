<?php 
define("gLogFileName", "log.txt");

/**
 * Container for debug purposes functions
 */
class DebugUtil {
    /**
     * Opens a log.txt file in the relative directory of the file from where
     * this function is called, prints a message, $fileName and $line can be replaced
     * with macro defines __FILE__ and __LINE__
     */
    public static function LogIntoFile(string $fileName, int $line, string|null $message = "")
    {
        $file = fopen(gLogFileName, "a");
        $toWrite = "";

        if (!$file) {
            return;
        }

        $toWrite = "In file ".$fileName.", on line ".strval($line).":\n".strval($message)."\n";
        fwrite($file, $toWrite);
        fclose($file);
    }
}
?>