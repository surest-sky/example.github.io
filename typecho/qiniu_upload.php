<!-- 这是js文件， 放在 根目录下的 tools 目录下即可 -->
<script>
    window.onload = function () {
        const DOMAIN = 'http://cdn.surest.cn/'  // cdn 域名
        const URL = "http://surest.cn/admin/auth/qiniu.php" // 例如 将 http://surest.cn/admin 修改为 你的博客后台访问地址
        const EDITOR = $('#text');
        const myField = document.getElementById('text')
        bindDom()
        function bindDom() {
            var file = null;
            var base64 = null
            EDITOR.unbind('paste')
            EDITOR.bind('paste', function () {
                var items = (event.clipboardData || window.clipboardData).items;

                var items = (event.clipboardData || window.clipboardData).items;
                if (items && items.length) {
                    // 搜索剪切板items
                    for (var i = 0; i < items.length; i++) {
                        if (items[i].type.indexOf('image') !== -1) {
                            console.log(items[i]);
                            file = items[i].getAsFile();
                            break;
                        }
                    }
                } else {
                    console.log("当前浏览器不支持")
                    return;
                }
                if (!file) {
                    console.log("粘贴内容非图片")
                    return;
                }
                filetoDataURL(file)
            })
        }

        //  将File对象的图片转化为base64
        function filetoDataURL(file) {
            var reader = new FileReader();
            reader.onloadend = function (e) {
                base64 = e.target.result
                upload(base64)
            };
            reader.readAsDataURL(file);
        };

        function upload(base64) {
            // 获取 token
            let token = null

            $.ajax({
                method: 'post',
                url: URL,
                async: false, // 这里要设置一个同步请求，等待获取token后再进行， 不然会token 401
                success: function (data) {
                    token = JSON.parse(data).token
                }
            })

            base64 = base64.split('base64,')[1]

            $.ajax({
                url: 'https://upload-z2.qiniup.com/putb64/-1', // 储存空间地址
                method: 'post',
                headers: {
                    "Content-Type": "application/octet-stream",
                    "Authorization": "UpToken " + token
                },
                data: base64,
                success: function (data) {
                    let url = DOMAIN + data.key
                    let md_img = `![图片描述...](${url})`
                    insertAtCursor(`${md_img}`)
                }
            })
        }

        // 插入文章
        function insertAtCursor(myValue) {
            //IE support
            if (document.selection) {
                myField.focus();
                sel = document.selection.createRange();
                sel.text = myValue;
                sel.select();
            }
            //MOZILLA/NETSCAPE support
            else if (myField.selectionStart || myField.selectionStart == '0') {
                var startPos = myField.selectionStart;
                var endPos = myField.selectionEnd;
                // save scrollTop before insert
                var restoreTop = myField.scrollTop;
                myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
                if (restoreTop > 0) {
                    // restore previous scrollTop
                    myField.scrollTop = restoreTop;
                }
                myField.focus();
                myField.selectionStart = startPos + myValue.length;
                myField.selectionEnd = startPos + myValue.length;
            } else {
                myField.value += myValue;
                myField.focus();
            }
        }
    }

</script>