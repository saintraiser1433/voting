<?php
/**
 * Output running time / countdown for an election.
 * Expects: $date_start, $date_end (MySQL datetime strings or null), optional $label (e.g. "Voting").
 * Use in admin dashboard, results, and voter pages (per mode).
 */
$label = isset($label) ? $label : 'Voting';
$start_ts = !empty($date_start) ? strtotime($date_start) : null;
$end_ts = !empty($date_end) ? strtotime($date_end) : null;
$id = 'running-time-' . (isset($running_time_id) ? $running_time_id : substr(md5(uniqid()), 0, 8));
?>
<div class="election-running-time card border-info mb-2" id="<?php echo htmlspecialchars($id); ?>">
    <div class="card-body py-2">
        <strong class="text-info"><?php echo htmlspecialchars($label); ?> period:</strong>
        <span class="running-time-text" data-start="<?php echo $start_ts ? $start_ts : ''; ?>" data-end="<?php echo $end_ts ? $end_ts : ''; ?>">
            <?php
            if (!$start_ts && !$end_ts) {
                echo 'No start/end set';
            } else {
                $now = time();
                if ($start_ts && $now < $start_ts) {
                    $diff = $start_ts - $now;
                    $d = (int)floor($diff / 86400);
                    $h = (int)floor(($diff % 86400) / 3600);
                    $m = (int)floor(($diff % 3600) / 60);
                    $s = $diff % 60;
                    echo 'Starts in ' . $d . 'd ' . $h . 'h ' . $m . 'm ' . $s . 's';
                } elseif ($end_ts && $now > $end_ts) {
                    echo 'Ended';
                } elseif ($end_ts && $now <= $end_ts) {
                    $diff = $end_ts - $now;
                    $d = (int)floor($diff / 86400);
                    $h = (int)floor(($diff % 86400) / 3600);
                    $m = (int)floor(($diff % 3600) / 60);
                    $s = $diff % 60;
                    echo 'Time left: ' . $d . 'd ' . $h . 'h ' . $m . 'm ' . $s . 's';
                } else {
                    echo 'Open (no end set)';
                }
            }
            ?>
        </span>
        <?php if ($start_ts || $end_ts) { ?>
        <small class="text-muted d-block mt-1">
            <?php if ($start_ts) echo 'Start: ' . date('M j, Y g:i A', $start_ts); ?>
            <?php if ($start_ts && $end_ts) echo ' &mdash; '; ?>
            <?php if ($end_ts) echo 'End: ' . date('M j, Y g:i A', $end_ts); ?>
        </small>
        <?php } ?>
    </div>
</div>
<script>
(function() {
    var el = document.querySelector('#<?php echo $id; ?> .running-time-text');
    if (!el) return;
    var start = el.getAttribute('data-start') ? parseInt(el.getAttribute('data-start'), 10) : null;
    var end = el.getAttribute('data-end') ? parseInt(el.getAttribute('data-end'), 10) : null;
    function fmt(diff) {
        if (diff <= 0) return '0d 0h 0m 0s';
        var d = Math.floor(diff / 86400);
        var h = Math.floor((diff % 86400) / 3600);
        var m = Math.floor((diff % 3600) / 60);
        var s = diff % 60;
        var parts = [];
        if (d) parts.push(d + 'd');
        parts.push(h + 'h', m + 'm', s + 's');
        return parts.join(' ');
    }
    function update() {
        var now = Math.floor(Date.now() / 1000);
        if (start && now < start) {
            el.textContent = 'Starts in ' + fmt(start - now);
        } else if (end && now > end) {
            el.textContent = 'Ended';
        } else if (end && now <= end) {
            el.textContent = 'Time left: ' + fmt(end - now);
        } else if (!start && !end) {
            el.textContent = 'Open (no end set)';
        } else {
            el.textContent = 'Open (no end set)';
        }
    }
    update();
    setInterval(update, 1000);
})();
</script>
