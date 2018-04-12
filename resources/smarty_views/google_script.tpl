<script>
    function hideTds() {
        var tds = document.querySelectorAll('td');
        for (var i = 0; i < 10; i++) {
//            console.log(tds[i]);
            tds[i].style.display = 'none';
        }
    }

    hideTds();
    document.querySelector('#searchKey').onkeyup = function() {
        var tds = document.querySelectorAll('td');
        for (var i = 0; i < 10; i++) {
            tds[i].innerHTML = '';
        }
        hideTds();
        if (this.value.trim() === '') {
            return;
        }

        var s = document.createElement('script');
        if (window.location.protocol == 'http:') {
            s.src = 'http://unionsug.baidu.com/su?wd=' + encodeURI(this.value.trim()) + '&p=3&cb=fn';
        } else {
            s.src = 'https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd=' + encodeURI(this.value.trim()) + '&cb=fn';
        }
        document.body.appendChild(s);
    };
    function fn(data) {
        var tds = document.querySelectorAll('td');
        data.s.forEach(function(item, index) {
            tds[index].style.display = '';
            tds[index].innerHTML = item;
        });
        // delete scripts
        var s = document.querySelectorAll('script');
        for (var i = 1, len = s.length; i < len; i++) {
            document.body.removeChild(s[i]);
        }
    }
    document.querySelector('tbody').onclick = function(e) {
        var wd = e.target.innerHTML;
        var searchKeyInput = document.getElementById('searchKey');
        searchKeyInput.value = e.target.innerHTML;
        hideTds();
//        window.open('https://www.baidu.com/s?word=' + encodeURI(wd));
    };
    document.onclick = function(e) {
        console.log(e);
        var searchKeyInput = document.getElementById('searchKey');
        if (e.target != searchKeyInput) {
            hideTds();
        }
    }
</script>
