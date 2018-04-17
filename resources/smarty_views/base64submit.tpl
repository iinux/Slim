<script>
    window.onload = function () {
        function processForm(e) {
            if (e.preventDefault) e.preventDefault();

            window.location = '/dns-result?q=' + btoa(document.getElementById('searchKey').value);

            return false;
        }

        document.getElementById('searchBtn').onclick = processForm;

        var form = document.getElementById('searchForm');
        if (form.attachEvent) {
            form.attachEvent("submit", processForm);
        } else {
            form.addEventListener("submit", processForm);
        }
    }
</script>