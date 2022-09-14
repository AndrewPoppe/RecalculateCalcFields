<?php


$proj_name = REDCap::getProjectTitle();

$records = \Records::getRecordList($project_id);
$cnt = 1;

\System::increaseMemory(1024 * 32);
\System::increaseMaxExecTime(60 * 60);

if ($_POST['hideme'] != "hideme") {
?>
    <table>
        <tr>
            <td>
                <div class='projhdr'><i class='fas fa-calculator'></i> Recalculate Calc Fields</div>
            </td>
        </tr>
        <tr>
            <td class='header'>Update All Calculated Fields</td>
        </tr>
        <tr>
            <td class='labelrc col-12'>Project Name: <?= $proj_name ?></td>
        </tr>
        <tr>
            <td class='labelrc col-12'>This routine will update all of the calculated fields in the project.</br>This is accomplished without the the use of an intermediate review screen (as in Rule H).</br>The records are sequentially processed, limiting the memory overhead that often results in Rule H not completing for some larger projects.
        </tr>
        </td>
        <tr>
            <td class='labelrc col-12'></br><b>Records in project: </b><?= count($records) ?>
        </tr>
        </td>
        <?php if (count($records) > 100) { ?>
            <tr>
                <td class="yellow">The time required is dependent on several factors, including:<ul>
                        <li>Number of records in the project.</li>
                        <li>Number / complexity of computed fields in the project.</li>
                        <li>Server specifications.</li>
                    </ul>As a general guide, allow 1-2 minutes per 1000 records.</br></br></br></td>
            </tr>
        <?php } ?>
        <tr>
            <td class='greenhighlight'>
                <form Name="form1" Method="POST" ACTION="<?= $module->getUrl("recalculate.php") ?>"><input type="submit" id="send" style="background-color: #ff9966" name="submit" value="Update Calculations"><input type="hidden" name="hideme" value="hideme"></form>
                <b>NOTE:</b> This page will refresh when calculation updates are complete.
        </tr>
        </td>
    </table>
<?php
}
if ($_POST['hideme'] == "hideme") {
    $cnt = 0;
?>
    <table>
        <tr>
            <td>
                <div class='projhdr'><i class='fas fa-calculator'></i> Recalculate Calc Fields</div>
            </td>
        </tr>
        <tr>
            <td class='header'>Update All Calculated Fields</td>
        </tr>
        <tr>
            <td class='labelrc col-12'>Project Name: <?= $proj_name ?></td>
        </tr>
        <tr>
            <td class='yellow'>Update Complete.</td>
        </tr>
        <tr>
            <td class='greenhighlight'><b>NOTE:</b> Calculations are sometimes dependent on other calculations (which may, in turn, be dependent on other calculations).</br>For this reason, it is strongly recommended that calculations are updated repeatedly, until the number of updated calcuations is '0'.</td>
        </tr>
        <tr>
            <td class='greenhighlight'>
                <form Name="form1" Method="POST" ACTION="" onsubmit="return checkForm(this);"><input type="submit" id="send" style="background-color: #ff9966" name="submit" value="Update Calculations"><input type="hidden" name="hideme" value="hideme"></form>
            </td>
        </tr>
    </table>
<?php
    $recordN = 0;
    $NRecords = count($records);
    foreach ($records as $record) {
        $module->log('Working on record', ['record' => $record, 'count' => $recordN++ . " / " . $NRecords]);

        $updates = \Calculate::saveCalcFields($record);
        if ($updates == 1) {
            $cnt++;
        }
    }
    echo "Updated Records: " . $cnt . "</br>";
}

?>
<script type="text/javascript">
    $(document).ready(function() {
        var reccnt = <?php echo $cnt; ?>;
        if (reccnt == 0) {
            document.getElementById('send').hidden = true;
        }
        $('#send').on('click', function() {
            $(this).hide();
            $('#form1').submit();
        });
    });
</script>