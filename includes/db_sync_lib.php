<?php
/**
 * Pull-style DB sync: remote (e.g. ngrok) exports JSON; local truncates + imports.
 * Never exports the `admin` table.
 */

if (!function_exists('db_sync_ensure_app_settings')) {
    function db_sync_ensure_app_settings(mysqli $conn): void
    {
        $res = $conn->query("SHOW TABLES LIKE 'app_settings'");
        if (!$res || $res->num_rows === 0) {
            $conn->query("CREATE TABLE IF NOT EXISTS `app_settings` (
                `setting_key` VARCHAR(100) NOT NULL PRIMARY KEY,
                `setting_value` TEXT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
    }
}

if (!function_exists('db_sync_get_setting')) {
    function db_sync_get_setting(mysqli $conn, string $key, string $default = ''): string
    {
        db_sync_ensure_app_settings($conn);
        $k = $conn->real_escape_string($key);
        $r = $conn->query("SELECT setting_value FROM app_settings WHERE setting_key='$k' LIMIT 1");
        if ($r && $r->num_rows > 0) {
            $row = $r->fetch_assoc();
            return trim((string)($row['setting_value'] ?? ''));
        }
        return $default;
    }
}

if (!function_exists('db_sync_is_localhost')) {
    function db_sync_is_localhost(): bool
    {
        $h = strtolower($_SERVER['HTTP_HOST'] ?? '');
        if ($h === 'localhost' || $h === '127.0.0.1' || $h === '[::1]' || $h === '::1') {
            return true;
        }
        if (preg_match('/^\d{1,3}(\.\d{1,3}){3}$/', $h)) {
            $p = array_map('intval', explode('.', $h));
            if ($p[0] === 127 || $p[0] === 10) {
                return true;
            }
            if ($p[0] === 172 && $p[1] >= 16 && $p[1] <= 31) {
                return true;
            }
            if ($p[0] === 192 && $p[1] === 168) {
                return true;
            }
            if ($p[0] === 169 && $p[1] === 254) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('db_sync_excluded_tables')) {
    function db_sync_excluded_tables(): array
    {
        return ['admin'];
    }
}

if (!function_exists('db_sync_preferred_order')) {
    function db_sync_preferred_order(): array
    {
        return [
            'acad_tbl',
            'departments',
            'partylist',
            'position',
            'dept_position',
            'voters',
            'election_title',
            'candidate',
            'dept_candidate',
            'vote',
            'department_vote',
            'app_settings',
        ];
    }
}

if (!function_exists('db_sync_table_list_ordered')) {
    function db_sync_table_list_ordered(mysqli $conn): array
    {
        $exclude = array_flip(db_sync_excluded_tables());
        $res = $conn->query('SHOW TABLES');
        if (!$res) {
            return [];
        }
        $all = [];
        while ($row = $res->fetch_row()) {
            $t = $row[0];
            if (!isset($exclude[$t])) {
                $all[] = $t;
            }
        }
        $preferred = db_sync_preferred_order();
        $ordered = [];
        foreach ($preferred as $t) {
            if (in_array($t, $all, true)) {
                $ordered[] = $t;
            }
        }
        foreach ($all as $t) {
            if (!in_array($t, $ordered, true)) {
                $ordered[] = $t;
            }
        }
        return $ordered;
    }
}

if (!function_exists('db_sync_export_payload')) {
    function db_sync_export_payload(mysqli $conn): array
    {
        $payload = [];
        foreach (db_sync_table_list_ordered($conn) as $table) {
            $rows = [];
            $t = preg_replace('/[^a-z0-9_]/i', '', $table);
            if ($t === '') {
                continue;
            }
            $q = $conn->query('SELECT * FROM `' . $t . '`');
            if ($q) {
                while ($row = $q->fetch_assoc()) {
                    $rows[] = $row;
                }
            }
            $payload[$table] = $rows;
        }
        return $payload;
    }
}

if (!function_exists('db_sync_table_columns')) {
    function db_sync_table_columns(mysqli $conn, string $table): array
    {
        $cols = [];
        $t = preg_replace('/[^a-z0-9_]/i', '', $table);
        if ($t === '') {
            return [];
        }
        $res = $conn->query('SHOW COLUMNS FROM `' . $t . '`');
        if (!$res) {
            return [];
        }
        while ($row = $res->fetch_assoc()) {
            $cols[] = $row['Field'];
        }
        return $cols;
    }
}

if (!function_exists('db_sync_apply_payload')) {
    /**
     * @param array<string, list<array<string, mixed>>> $tablesData
     * @return array{ok: bool, message: string, tables?: int, rows?: int}
     */
    function db_sync_apply_payload(mysqli $conn, array $tablesData): array
    {
        $conn->query('SET FOREIGN_KEY_CHECKS=0');
        $conn->query('SET UNIQUE_CHECKS=0');
        $importedTables = 0;
        $importedRows = 0;

        $order = db_sync_table_list_ordered($conn);
        foreach ($order as $table) {
            if (!isset($tablesData[$table]) || !is_array($tablesData[$table])) {
                continue;
            }
            $localCols = db_sync_table_columns($conn, $table);
            if ($localCols === []) {
                continue;
            }
            $t = preg_replace('/[^a-z0-9_]/i', '', $table);
            if ($t === '') {
                continue;
            }

            if (!$conn->query('DELETE FROM `' . $t . '`')) {
                return ['ok' => false, 'message' => 'Could not clear table ' . $t . ': ' . $conn->error];
            }

            $colList = '`' . implode('`,`', $localCols) . '`';
            foreach ($tablesData[$table] as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $vals = [];
                foreach ($localCols as $c) {
                    if (!array_key_exists($c, $row)) {
                        $vals[] = 'NULL';
                        continue;
                    }
                    $v = $row[$c];
                    if ($v === null) {
                        $vals[] = 'NULL';
                    } else {
                        $vals[] = "'" . $conn->real_escape_string((string)$v) . "'";
                    }
                }
                $sql = 'INSERT INTO `' . $t . '` (' . $colList . ') VALUES (' . implode(',', $vals) . ')';
                if (!$conn->query($sql)) {
                    return ['ok' => false, 'message' => 'Insert failed on ' . $t . ': ' . $conn->error];
                }
                $importedRows++;
            }
            $importedTables++;
        }

        $conn->query('SET UNIQUE_CHECKS=1');
        $conn->query('SET FOREIGN_KEY_CHECKS=1');

        return [
            'ok' => true,
            'message' => "Imported $importedRows row(s) across $importedTables table(s).",
            'tables' => $importedTables,
            'rows' => $importedRows,
        ];
    }
}

if (!function_exists('db_sync_curl_post')) {
    /**
     * @return array{ok: bool, body: string, error?: string}
     */
    function db_sync_curl_post(string $url, array $postFields, int $timeout = 180, bool $verifySsl = true): array
    {
        if (!function_exists('curl_init')) {
            return ['ok' => false, 'body' => '', 'error' => 'PHP cURL extension is not enabled.'];
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if (!$verifySsl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'ngrok-skip-browser-warning: 1',
        ]);
        $body = curl_exec($ch);
        $err = curl_error($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body === false) {
            return ['ok' => false, 'body' => '', 'error' => $err ?: 'HTTP request failed'];
        }
        if ($code >= 400) {
            return ['ok' => false, 'body' => $body, 'error' => 'HTTP ' . $code . ' — ' . $url];
        }
        return ['ok' => true, 'body' => (string)$body, 'error' => ''];
    }
}

if (!function_exists('db_sync_pull_from_remote')) {
    /**
     * @return array{ok: bool, message: string, tables?: int, rows?: int}
     */
    function db_sync_pull_from_remote(mysqli $conn, string $remoteBaseUrl, string $syncKey): array
    {
        $syncKey = trim($syncKey);
        if ($syncKey === '') {
            return ['ok' => false, 'message' => 'Sync API key is required.'];
        }
        $base = rtrim($remoteBaseUrl, "/\r\n\t ");
        if ($base === '' || !preg_match('#^https?://#i', $base)) {
            return ['ok' => false, 'message' => 'Remote URL must start with http:// or https://'];
        }
        $exportOverride = trim(db_sync_get_setting($conn, 'db_sync_export_url', ''));
        if ($exportOverride !== '') {
            $exportUrl = rtrim($exportOverride, "/\r\n\t ");
            if (!preg_match('#^https?://#i', $exportUrl)) {
                return ['ok' => false, 'message' => 'Export URL override must start with http:// or https://'];
            }
        } else {
            $exportUrl = $base . '/ajax/db_sync_export.php';
        }
        $verifySsl = db_sync_get_setting($conn, 'db_sync_insecure_ssl', '0') !== '1';
        $res = db_sync_curl_post($exportUrl, ['sync_key' => $syncKey], 180, $verifySsl);
        if (!$res['ok']) {
            $err = $res['error'] ?: 'unknown';
            $msg = 'Remote request failed: ' . $err;
            if (strpos($err, 'HTTP 404') !== false) {
                $msg .= ' Deploy ajax/db_sync_export.php on the remote, or set “Export endpoint URL” to its full address. Base URL must be the folder that contains ajax/ (not …/admin).';
            }
            return ['ok' => false, 'message' => $msg];
        }
        $json = json_decode($res['body'], true);
        if (!is_array($json) || empty($json['ok'])) {
            $msg = is_array($json) && isset($json['message']) ? (string)$json['message'] : 'Invalid response from remote.';
            return ['ok' => false, 'message' => $msg];
        }
        if (empty($json['tables']) || !is_array($json['tables'])) {
            return ['ok' => false, 'message' => 'Remote returned no table data.'];
        }
        return db_sync_apply_payload($conn, $json['tables']);
    }
}
