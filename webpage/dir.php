var files = <?php $out = array();
foreach (glob('files/sampled_files/*.json') as $filename) {
    $p = pathinfo($filename);
    $out[] = $p['filename'];
}
echo json_encode($out); ?>;