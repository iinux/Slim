<script>
    window.onload = function () {
        document.getElementById('searchBtn').onclick = function () {
            window.location = '/dns-result?q=' + btoa(document.getElementById('searchKey').value);
            return false;
        }
    }
</script>