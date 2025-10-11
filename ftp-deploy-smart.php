<?php
/**
 * ftp-deploy-smart.php
 * Versi: 2.1 - Smart Resume + Delta Upload + Progress Bar
 * ------------------------------------------
 * Upload otomatis ke InfinityFree (atau FTP lain)
 * hanya mengirim file yang berubah dan bisa dilanjutkan.
 */

$ftp_config = [
    'host'        => 'ftpupload.net',          // Server FTP (InfinityFree default)
    'user'        => 'if0_39734364',           // Ganti dengan username FTP kamu
    'pass'        => 'A321s22s3POS',           // Ganti dengan password FTP kamu
    'remote_path' => '/htdocs/laravel-pos',    // Path tujuan di hosting
    'local_path'  => __DIR__,                  // Folder lokal project
];

// File state upload sebelumnya
$stateFile = __DIR__ . '/deploy_state.json';

// Abaikan folder / file tertentu
$ignore = [
    '.git', 'node_modules', 'vendor/bin', 'storage/logs',
    'storage/framework/cache', 'storage/framework/sessions',
    'ftp-deploy-smart.php', 'deploy_state.json'
];

// -----------------------------------------------------
// Helper functions
// -----------------------------------------------------
function isIgnored($path, $ignore) {
    foreach ($ignore as $pattern) {
        if (stripos($path, $pattern) !== false) return true;
    }
    return false;
}

function listLocalFiles($dir, $ignore) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $files = [];
    foreach ($rii as $file) {
        if ($file->isDir()) continue;
        $path = str_replace('\\', '/', $file->getPathname());
        if (isIgnored($path, $ignore)) continue;
        $files[] = $path;
    }
    return $files;
}

function ftpEnsureDir($conn_id, $dir) {
    $parts = explode('/', trim($dir, '/'));
    $path = '';
    foreach ($parts as $part) {
        $path .= '/' . $part;
        @ftp_mkdir($conn_id, $path);
    }
}

function humanSize($bytes) {
    $units = ['B','KB','MB','GB','TB'];
    for ($i=0; $bytes>=1024 && $i<count($units)-1; $i++) $bytes /= 1024;
    return round($bytes, 2).' '.$units[$i];
}

// -----------------------------------------------------
// Mulai koneksi
// -----------------------------------------------------
echo "🔌 Connecting to FTP server...\n";
$conn_id = ftp_connect($ftp_config['host']);
if (!$conn_id) exit("❌ Gagal connect ke FTP server.\n");

$login = ftp_login($conn_id, $ftp_config['user'], $ftp_config['pass']);
ftp_pasv($conn_id, true);
if (!$login) exit("❌ Gagal login ke akun FTP.\n");

echo "✅ Connected as {$ftp_config['user']}\n";

// -----------------------------------------------------
// Baca file state sebelumnya
// -----------------------------------------------------
$lastState = file_exists($stateFile) ? json_decode(file_get_contents($stateFile), true) : [];
$localFiles = listLocalFiles($ftp_config['local_path'], $ignore);
$totalFiles = count($localFiles);
if ($totalFiles === 0) exit("⚠️ Tidak ada file untuk diupload.\n");

$uploadedCount = 0;
$startTime = microtime(true);

// -----------------------------------------------------
// Upload loop
// -----------------------------------------------------
foreach ($localFiles as $filePath) {
    $relPath = str_replace($ftp_config['local_path'], '', $filePath);
    $relPath = ltrim(str_replace('\\', '/', $relPath), '/');
    $remotePath = "{$ftp_config['remote_path']}/{$relPath}";
    $remoteDir = dirname($remotePath);

    // Skip file jika belum berubah
    $fileHash = md5_file($filePath);
    if (isset($lastState[$relPath]) && $lastState[$relPath] === $fileHash) {
        $uploadedCount++;
        $progress = round(($uploadedCount / $totalFiles) * 100, 1);
        echo "\r⏭  [$progress%] Skip unchanged: $relPath           ";
        continue;
    }

    ftpEnsureDir($conn_id, $remoteDir);
    $upload = ftp_put($conn_id, $remotePath, $filePath, FTP_BINARY);

    if ($upload) {
        $lastState[$relPath] = $fileHash;
        $uploadedCount++;
        $progress = round(($uploadedCount / $totalFiles) * 100, 1);
        $elapsed = microtime(true) - $startTime;
        $avgTime = $elapsed / max($uploadedCount, 1);
        $eta = ($totalFiles - $uploadedCount) * $avgTime;
        echo "\r📤 [$progress%] Uploaded: $relPath (" . humanSize(filesize($filePath)) . ") | ETA: " . round($eta,1) . "s      ";
    } else {
        echo "\n❌ Gagal upload: $relPath\n";
    }
}

// -----------------------------------------------------
// Simpan state upload terakhir
// -----------------------------------------------------
file_put_contents($stateFile, json_encode($lastState, JSON_PRETTY_PRINT));

ftp_close($conn_id);

$elapsed = round(microtime(true) - $startTime, 1);
echo "\n\n✅ Deploy selesai dalam {$elapsed}s ({$uploadedCount}/{$totalFiles} files)\n";
