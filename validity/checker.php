<?php
echo "<script>
if (
    !localStorage.getItem('username') ||
    !localStorage.getItem('password') ||
    localStorage.getItem('username') === '' ||
    localStorage.getItem('password') === ''
) {
    window.location.href = '/';
}
</script>";
?>
