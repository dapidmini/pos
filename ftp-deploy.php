<?php
/**
 * ftp-deploy.php
 * Smart FTP auto uploader for InfinityFree or any FTP host.
 * Hanya upload file baru atau yang berubah.
 */

$ftp_config = [
    'host' => 'ftpupload.net',           // host FTP InfinityFree
    'user' => 'if0_39734364',            // username FTP
    'pass' => 'A321s22s3POS',            // password FTP
    'remote_path' => '/htdocs/laravel-pos', // folder tujuan di server
    'local_path' => __DIR__,             // folder project lokal
];

// --- koneksi FTP ---
echo "🔌 Connecting to FTP server...\n";
$conn_id = ftp_connect($ftp_config['host']);
if (!$conn_id) {
    exit("❌ Tidak bisa connect ke host: {$ftp_config['host']}\n");
}
$login = ftp_login($conn_id, $ftp_config['user'], $ftp_config['pass']);
if (!$login) {
    ftp_close($conn_id);
    exit("❌ Gagal login ke FTP sebagai {$ftp_config['user']}\n");
}
ftp_pasv($conn_id, true);
echo "✅ Connected and logged in as {$ftp_config['user']}\n";

// --- daftar yang diabaikan ---
$ignore = [
    '.git', '.gitignore', 'node_modules', 'storage/logs',
    'storage/framework/cache', 'storage/framework/sessions',
    'ftp-deploy.php', // jangan upload script deploy ini
];

// --- fungsi bantu ---
function should_ignore($path, $ignore)
{
    foreach ($ignore as $ignored) {
        if (stripos($path, $ignored) !== false) {
            return true;
        }
    }
    return false;
}

// ambil daftar file di server (rekursif)
function ftp_list_recursive($conn_id, $dir)
{
    $list = [];
    $files = @ftp_nlist($conn_id, $dir);
    if (!$files) return $list;

    foreach ($files as $file) {
        $basename = basename($file);
        if ($basename === '.' || $basename === '..') continue;

        $size = ftp_size($conn_id, $file);
        if ($size == -1) {
            // directory
            $list = array_merge($list, ftp_list_recursive($conn_id, $file));
        } else {
            $list[$file] = $size;
        }
    }
    return $list;
}

// upload hanya file baru / berubah
function ftp_upload_delta($conn_id, $local_dir, $remote_dir, $ignore, $server_files)
{
    $items = scandir($local_dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') continue;

        $local_path  = "$local_dir/$item";
        $remote_path = "$remote_dir/$item";

        if (should_ignore($local_path, $ignore)) continue;

        if (is_dir($local_path)) {
            @ftp_mkdir($conn_id, $remote_path);
            ftp_upload_delta($conn_id, $local_path, $remote_path, $ignore, $server_files);
        } else {
            $local_size = filesize($local_path);
            $remote_size = $server_files[$remote_path] ?? -1;

            if ($remote_size != $local_size) {
                $upload = ftp_put($conn_id, $remote_path, $local_path, FTP_BINARY);
                if ($upload) {
                    echo "📤 Uploaded: $remote_path (" . round($local_size / 1024, 1) . " KB)\n";
                } else {
                    echo "❌ Gagal upload: $remote_path\n";
                }
            }
        }
    }
}

// --- jalankan proses ---
echo "🚀 Preparing to sync {$ftp_config['local_path']} → {$ftp_config['remote_path']}\n";
$start = microtime(true);

// ambil daftar file di server untuk delta check
echo "📂 Membaca daftar file server...\n";
$server_files = ftp_list_recursive($conn_id, $ftp_config['remote_path']);
echo "ℹ️ Total file di server: " . count($server_files) . "\n";

// upload delta
ftp_upload_delta($conn_id, $ftp_config['local_path'], $ftp_config['remote_path'], $ignore, $server_files);

// selesai
$duration = round(microtime(true) - $start, 2);
ftp_close($conn_id);
echo "\n✅ Deploy selesai dalam {$duration} detik.\n";
