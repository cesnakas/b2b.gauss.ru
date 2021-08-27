import createAutoCorrectedDatePipe from 'text-mask-addons/dist/createAutoCorrectedDatePipe';

let inputMask = {
    run() {
        let phoneMask = ['+', '7', ' ', '(', /[1-9]/, /\d/, /\d/, ')', ' ', /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/];
        let dateMask = [/[0-3]/, /\d/, '.', /[0-1]/, /\d/, '.', /[0-2]/, /\d/, /\d/, /\d/];
        let timeMask = [/[0-2]/, /\d/, ':', /[0-6]/, /\d/];
        let myInput = document.querySelectorAll('[data-mask]');
        let myPhone = document.querySelectorAll('[data-mask="phone"]');
        let timeElement = document.querySelectorAll('[data-mask="time"]');
        let dateElement = document.querySelectorAll('[data-mask="date"]');
        const autoCorrectedDatePipe = createAutoCorrectedDatePipe('dd.mm.yyyy');
        const autoCorrectedTimePipe = createAutoCorrectedDatePipe('HH.MM.SS');

        function validate(e) {
            let self = this;
            let currPhone = self.querySelector('[data-mask="phone"]');
            let currPhoneValue = currPhone.value;
            let currPhoneValidateValue = currPhoneValue.search('_');
            
            if (currPhoneValidateValue == (-1) && (currPhoneValue.length != 0)) {
                currPhone.classList.remove('error');
                if (currMail.nextElementSibling != null) {
                    currPhone.nextElementSibling.classList.remove('active');
                }
            } else {
                e.preventDefault();
                currPhone.classList.add('error');
                if (currMail.nextElementSibling != null) {
                    currPhone.nextElementSibling.classList.add('active');
                }
            };
            
        }
        
        for (let i = 0; i < myPhone.length; i++) {
            myPhone[i].onfocus = function () {
                this.placeholder = '+7 (___) ___-____'
            };
            
            let maskedInputController = vanillaTextMask.maskInput({
                inputElement: myPhone[i],
                mask: phoneMask,
                showMask: false
            });
        };

        for (let i = 0; i < timeElement.length; i++) {
            timeElement[i].onfocus = function () {
                this.placeholder = '__:__'
            };

            vanillaTextMask.maskInput({
                inputElement: timeElement[i],
                mask: timeMask,
                keepCharPositions: true,
                showMask: false,
                pipe: autoCorrectedTimePipe,
                guide: false,
            });
        }
        
        for (let i = 0; i < myInput.length; i++) {
            let currPlaceholder = myInput[i].placeholder;
            
            myInput[i].onblur = function () {
                this.placeholder = currPlaceholder;
            }
        }

        for (let i = 0; i < dateElement.length; i++) {
            vanillaTextMask.maskInput({
                inputElement: dateElement[i],
                mask: dateMask,
                keepCharPositions: true,
                showMask: false,
                pipe: autoCorrectedDatePipe,
                guide: false,
            });
        }

        $('[data-number]').on('input', function() {
            this.value = this.value
              .replace(/[^\d]/g, '');// numbers and decimals only

        });
    }
};

module.exports = inputMask;
