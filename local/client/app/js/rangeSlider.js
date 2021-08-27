import noUiSlider from 'nouislider';

let rangeSlider = {
    run() {
        let self = this;
        let rangeSliders = document.querySelectorAll('[data-range]');
        
        if(!rangeSliders.length)
            return;
        
        //Счетчик - все ли компоненты инициализированы
        let isInit = 0;
        
        for (let i = 0; i < rangeSliders.length; i++) {
            let slider = rangeSliders[i];
            let rangeSlider = slider.querySelector('[data-range-slider]');
            let rangeInputs = [slider.querySelector('[data-range-input="left"]'), slider.querySelector('[data-range-input="right"]')];
            
            //Начальные данные
            let rangeStartMin,
                rangeStartMax,
                rangeMin,
                rangeMax;
            
            //Если data-type-slider != float - округляем до целых
            $(slider).data('type-slider') != 'float' ? getValues(parseInt) : getValues(parseFloat);
            
            function getValues(rounding) {
                rangeStartMin = rounding(slider.getAttribute('data-start-min'));
                rangeStartMax = rounding(slider.getAttribute('data-start-max'));
                rangeMin = rounding(slider.getAttribute('data-range-min'));
                rangeMax = rounding(slider.getAttribute('data-range-max'));
            }
            
            const uiSlider = noUiSlider.create(rangeSlider, {
                start: [rangeStartMin, rangeStartMax],
                step: 1,
                connect: true,
                tooltips: true,
                range: {
                    'min': rangeMin,
                    'max': rangeMax
                }
            });
    
            const decimal = parseInt(slider.getAttribute('data-decimal')) || 0;
            const units = slider.getAttribute('data-units');
            
            uiSlider.on('update', (val, i) => updateText(val, i));
            
            function updateText(val, i) {
                const $el = $($(uiSlider.target).find('.noUi-tooltip')[i]);
                
                const newVal = decimal > 0 ? -2 + decimal : -3;
    
                const newText = $el.text().slice(0, newVal) + ' ' + units;
    
                $el.text(newText);
            }
            
            
            //Передаём jq обертку изменяемого "слайдера", новое значение, и объект min или max
            let rounding = ($target, values, handle)=> {
                //Если округляем до целых то в меньшую / большую стороны
                if($target.data('type-slider') != 'float') {
                    if(handle == 1) {
                        return Math.ceil(values[handle]);
                    } else {
                        return Math.floor(values[handle]);
                    }
                }
                //Иначе возвращаем значние
                else {
                    return values[handle];
                }
            };
            
            //Изменения значений
            rangeSlider.noUiSlider.on('update', function( values, handle ) {
                //Обертка слайдера
                let $target = $(this.target).parent();
                
                //Если data-value соответствующего инпута не пустое - подставить в value
                if($($target.find('.b-range-slider__input')[handle]).data('value')) {
                    rangeInputs[handle].value = rounding($target, values, handle);
                }
                //Если счетчик x2 от общего количества блоков - инициализация прошла и все дальнейшие изменения записываем уже в value
                else if(isInit === rangeSliders.length * 2) {
                    rangeInputs[handle].value = rounding($target, values, handle);
                }
                //Иначе все min max значения записываем в placeholder
                else {
                    rangeInputs[handle].placeholder = rounding($target, values, handle);
                    
                    isInit++;
                }
                
                
            });
            
            //Ввод через инпут
            /*rangeInputs.forEach((input, handle) => {
                input.addEventListener('blur', function() {
                    self.setSliderHandle(rangeSlider, handle, this.value);
                });
            });*/
    
            //Отпустили ползунок
            rangeSlider.noUiSlider.on('end', function( values, handle ) {
                let $target = $(this.target);
                values.forEach((value, index) => {
                    value = rounding($target, values, index);
                    rangeInputs[index].value = value;
                });
                
                $(rangeInputs[handle]).keyup();
        
                // $('[data-filter-value-code][type=text]').trigger('change')
                // smartFilter.reload(rangeInputs[0]);
            });
        }

        $('.b-range-slider__inputs input').keydown(function (event){
            if (!(!event.shiftKey //Disallow: any Shift+digit combination
                && !(event.keyCode < 48 || event.keyCode > 57) //Disallow: everything but digits
                || !(event.keyCode < 96 || event.keyCode > 105) //Allow: numeric pad digits
                || event.keyCode == 46 // Allow: delete
                || event.keyCode == 8  // Allow: backspace
                || event.keyCode == 9  // Allow: tab
                || event.keyCode == 27 // Allow: escape
                || event.keyCode == 13 // Allow: enter
                || (event.keyCode == 65 && (event.ctrlKey === true || event.metaKey === true)) // Allow: Ctrl+A
                || (event.keyCode == 67 && (event.ctrlKey === true || event.metaKey === true)) // Allow: Ctrl+C
                //Uncommenting the next line allows Ctrl+V usage, but requires additional code from you to disallow pasting non-numeric symbols
                //|| (event.keyCode == 86 && (event.ctrlKey === true || event.metaKey === true)) // Allow: Ctrl+Vpasting
                || (event.keyCode >= 35 && event.keyCode <= 39) // Allow: Home, End
            )) {
                event.preventDefault();
            }
        });
    },
    
    setSliderHandle(rangeSlider, i, value) {
        let r = [null,null];
        r[i] = value;
        rangeSlider.noUiSlider.set(r);
    }
};

module.exports = rangeSlider;