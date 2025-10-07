<?php
namespace App\Services;

class TempCleaner
{
    public static function run()
    {
        $tempPath = storage_path('app/temp');
        $lastCleanupFile = storage_path('app/last_cleanup.txt');
        $now = time();

        $lastCleanup = file_exists($lastCleanupFile) ? (int)file_get_contents($lastCleanupFile) : 0;

        if ($now - $lastCleanup >= 24 * 60 * 60) {
            self::cleanupOldFiles($tempPath, 24);
            file_put_contents($lastCleanupFile, $now);
        }
    }

    private static function cleanupOldFiles($path, $hours)
    {
        if (!is_dir($path)) return;

        $threshold = time() - ($hours * 60 * 60);
        foreach (glob($path . '/*') as $file) {
            if (is_file($file) && filemtime($file) < $threshold) {
                @unlink($file);
            }
        }
    }
}
