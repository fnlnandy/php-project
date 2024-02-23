<?php 
define("gLogFileName", "log.txt");

class DebugUtil {
    /**
     * 
     */
    public static function LogIntoFile(string $fileName, int $line, string $message = "")
    {
        $file = fopen(gLogFileName, "a");
        $toWrite = "";

        if (!$file) {
            return;
        }

        $toWrite = "In file ".$fileName.", on line ".strval($line).":\n".$message."\n";
        fwrite($file, $toWrite);
        fclose($file);
    }
}
?>