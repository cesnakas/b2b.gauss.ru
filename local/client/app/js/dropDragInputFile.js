let dropDragInputFile = {
    run() {
        this.initDropDrag();
        //this.PastImageBuffer();
    },

    initDropDrag() {
        let dropArea = document.getElementsByClassName('b-form__file')[0];
        let __self = this;

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false)
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false)
        });

        function highlight(e) {
            dropArea.classList.add('highlight')
        }

        function unhighlight(e) {
            dropArea.classList.remove('highlight')
        }

        dropArea.addEventListener('drop', handleDrop, false);

        function pasteHandler(e) {

            // если поддерживается event.clipboardData (Chrome)
            if (e.clipboardData) {
                var items = e.clipboardData.items;
                if (items) {
                    for (var i = 0; i < items.length; i++) {
                        if (items[i].type.indexOf("image") !== -1) {
                            var blob = items[i].getAsFile();
                            var URLObj = window.URL || window.webkitURL;
                            var source = URLObj.createObjectURL(blob);
                            __self.createImage(blob);
                        }
                    }
                }
                // для Firefox проверяем элемент с атрибутом contenteditable
            } else {
                setTimeout(checkInput, 1);
            }
        }

        // добавляем обработчик событию
        window.addEventListener("paste", pasteHandler);

        function handleDrop(e) {
            let dt = e.dataTransfer;
            let files = dt.files;
            let inputFile = $('.b-form__file input:first');
            inputFile[0].files = files;
            inputFile.closest('.b-form__upload').find('label span').html(files[0].name);
            inputFile.closest('.b-form__upload').find('> span').hide();
        }
    },

    createImage(blob) {
        var formData = new FormData();
        formData.append('img', blob);
        $.ajax({
            type:'POST', // Тип запроса
            url: '/local/include/ajax/savePicture.php', // Скрипт обработчика
            data: formData, // Данные которые мы передаем
            contentType:false,
            processData:false,
            cache:false,
            success:function(data){
                $('.b-form__file *').hide();
                $('.ctrlvimage').remove();
                $('[name="CTRLVFILE"]').val(data);
                $('.b-form__file').append('<img class="ctrlvimage" src="' + data + '">');
            },
        });
    },

    PastImageBuffer() {
        var __self = this;
        if (!window.Clipboard) {
            var pasteCatcher = document.createElement("div");

            // Firefox вставляет все изображения в элементы с contenteditable
            pasteCatcher.setAttribute("contenteditable", "");

            pasteCatcher.style.display = "none";
            document.body.appendChild(pasteCatcher);

            // элемент должен быть в фокусе
            pasteCatcher.focus();
            document.addEventListener("click", function() { pasteCatcher.focus(); });
        }

        function checkInput() {
            var child = pasteCatcher.childNodes[0];
            pasteCatcher.innerHTML = "";
            if (child) {
                if (child.tagName === "IMG") {
                    createImage(child.src);
                }
            }
        }
    }
};

module.exports = dropDragInputFile;



