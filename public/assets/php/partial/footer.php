<footer>
    <script src="<?php echo $URL ?>/public/assets/js/script.js"></script>
    <h6 class="text-right">
        <small>
            <span id="year"><?php echo date('Y') ?></span>
            <span>/</span>
            <span id="month"><?php echo date('m') ?></span>
            <span>/</span>
            <span id="day"><?php echo date('d') ?></span>

            <span>　</span>

            <span id="hour"><?php echo date('H') ?></span>
            <span>:</span>
            <span id="min"><?php echo date('i') ?></span>
            <span>:</span>
            <span id="sec"><?php echo date('s') ?></span>
        </small>
    </h6>
</footer>
<script type="text/javascript">
    setInterval(setDateTime, 500);

function setDateTime()
{
    var date = new Date();
    $('#year').html( date.getFullYear());
    $('#month').html(('0'+(date.getMonth() + 1)).slice(-2));
    $('#day').html(  ('0'+date.getDate()).slice(-2));
    $('#hour').html( ('0'+date.getHours()).slice(-2));
    $('#min').html(  ('0'+date.getMinutes()).slice(-2));
    $('#sec').html(  ('0'+date.getSeconds()).slice(-2));
//    console.log(Y+M+D+H+I+S);
}
</script>