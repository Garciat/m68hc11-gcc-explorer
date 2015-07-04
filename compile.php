<?php

function argument_error($argname = '') {
?>
<p>Argument error: <?php echo $argname; ?></p>
<p><a href="/">go back</a></p>
<?php
    exit;
}

function str_chop_lines($str, $lines) {
    return implode("\n", array_slice(explode("\n", $str), $lines));
}

$params = ['source', 'lang', 'opt'];

$lang_values = ['c', 'c++'];
$opt_values = ['0', '1', '2'];

foreach ($params as $param) {
    if (!isset($_POST[$param])) {
        argument_error($param);
    }
}

$source = $_POST['source'];
$lang = $_POST['lang'];
$opt = $_POST['opt'];

$source .= "\n";

if (!in_array($lang, $lang_values)) {
    argument_error('lang');
}

if (!in_array($opt, $opt_values)) {
    argument_error('opt');
}

$descriptorspec = array(
   0 => array("pipe", "r"),
   1 => array("pipe", "w"),
   2 => array("pipe", "w")
);

$cwd = getcwd() . '/usr/bin';

// -I../../gel/include -I../../gel/include/asm-m68hc11/arch-32k

$command = sprintf('./m6811-elf-g++ -x%s -O%s -m68hc11 -mshort -fomit-frame-pointer -msoft-reg-count=0 -ffixed-z -S -o - -', $lang, $opt);

$process = proc_open($command, $descriptorspec, $pipes, $cwd, NULL);

if (is_resource($process)) {
    fwrite($pipes[0], $source);
    fclose($pipes[0]);

    $asm = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    $ret = proc_close($process);
}

$asm = str_chop_lines($asm, 7);
//$asm = preg_replace('/^\s*[;.][^\n]+$(?:\r\n|\n)?/m', '', $asm);

?>

<style>
th {
    font-size: 14pt;
}
td {
    vertical-align: top;
}

pre {
    padding: 10px;
    background: #111;
    color: white;
}
</style>

<p><pre><?php echo htmlspecialchars($command); ?></pre></p>

<p><strong>$?</strong> <?php echo $ret; ?></p>

<hr>

<h3>stderr</h3>
<pre><?php echo htmlspecialchars($err); ?></pre>

<hr>

<table>
    <tr>
        <th width="50%">stdin</th>
        <th width="50%">stdout</th>
    </tr>
    <tr>
        <td>
            <pre><?php echo htmlspecialchars($source); ?></pre>
        </td>
        <td>
            <pre><?php echo htmlspecialchars($asm); ?></pre>
        </td>
    </tr>
</table>

<hr>

<h3>info</h3>

<p><a href="http://www.gnu.org/software/m68hc11/m68hc11_gcc.html">compiler</a></p>

<p><a href="http://www.ele.uri.edu/Courses/ele205/6811-Instructions/index.html">instruction set</a></p>
