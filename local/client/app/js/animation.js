let animation = {
    run() {
        this.animateCounter = 0;
        
        //Анимации только для десктопа
        if($('[data-animation]').length) {
            this.init();
            this.splitText();
        }
    },
    init() {
        let $item = $('[data-animation]:not([data-animation-id])');
        
        //Всем блокам с анимациями присвоили дата атрибуты по порядковым номерам
        for(let i = 0;i < $item.length;i++) {
            $($item[i]).attr('data-animation-id', this.animateCounter);
        
            this.animateCounter++;
        }
    
        $item.each((i, item) => animation.animate($(item)));
    },
    animate($this) {
        let animationAttr = $this.data('animation');
        let animationAttrId = $this.data('animation-id');
        let $itemPosition = $this.offset().top;
    
        //Отдельные лиснеры для каждого блока с анимацией от id
        $(window).on('scroll.animation' + animationAttrId, function () {
            let scrollTop = $(window).scrollTop() + $(window).height();
    
            //Если низ экрана более 1000px от верха блока, убираем анимацию
            if(scrollTop - $itemPosition > 1000) {
                removeListener();
            } else if (scrollTop + 100 > $itemPosition) {
                //Иначе подставляем анимацию, в зависимости от дата-атрибута
                $this.addClass(animationAttr);
                removeListener();
            }
        });
        
        function removeListener() {
            $(window).off('scroll.animation' + animationAttrId);
        }
    },
    
    splitText() {
        const media = (function () {
            const windowWidth = window.innerWidth;

            if(windowWidth > 1279)
                return 0;
            else if(windowWidth > 1023)
                return 1;
            else if(windowWidth > 767)
                return 2;
            else
                return 3
        })();
        
        $('[data-split]').each((n,item) => {
            let $item = $(item); /* блок с текстом */
            let spText = $item.text().split(' '); /* разбиваем на слова */
            
            let animation = media === 3 ? $item.data('split-animation-m') : $item.data('split-animation'); /* забираем нужную анимацию от родителя */
    
            $item.text(''); /* удаляем текст */
    
            let textBlock = ''; /* одна строка текста */
            let count = 0; /* счетчик длины строки  */
            let i = 0; /* счетчик слов */
            let stringWidth = $item.data('split')[media]; /* максимальное количество символов в строке */

            while(i < spText.length) { /* пока не переберутся все слова */
                count += spText[i].length + 1; /* увеличить счетчик строки от ширины слова + пробел */
    
                if(i === spText.length - 1){ /* если слово последнее */
                    
                    if(count > stringWidth) /* если не помещается - закрыть предыдущую строку */
                        createString(textBlock);
                    
                    textBlock += spText[i]; /* обнулить строку */
                    createString(textBlock);
        
                    break;
                } else if(count > stringWidth) { /* если не помещается  */
                    textBlock.substr(0, textBlock.length - 1); /* обрезается последний пробел */
    
                    createString(textBlock);
                }  else {
                    textBlock += spText[i] + ' ';
                    i++
                }
            }
            
            /* создание строки */
            function createString() {
                /* анимация на основе атрибута родителя */
                $item.append(`<span class="animated" data-animation="${animation}">${textBlock}</span>`);
                textBlock = '';
                count = 0;
            }
    
            /* инициализация анимаци */
            this.init();
        })
    }
};

module.exports = animation;