let inputMask = {
    run() {
        let doc = document;
        let phoneMask = ['+', '7', ' ', '(', /[1-69]/, /\d/, /\d/, ')', ' ', /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/];
        let timeMask = [/[1-9]/, /\d/, ':', /[1-9]/, /\d/];
        let innMask = [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/];
        let okpoMask = [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/];
        let checkingAccMask = [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/];
        let bikMask = [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/];
        let railwayCodeMask = [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/];
        let number15Mask = [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/];
        let phoneElement = doc.querySelectorAll('[data-mask="phone"]');
        let timeElement = doc.querySelectorAll('[data-mask="time"]');
        let innElement = doc.querySelectorAll('[data-mask="inn"]');
        let okpoElement = doc.querySelectorAll('[data-mask="okpo"]');
        let checkingAccElement = doc.querySelectorAll('[data-mask="checking-acc"]');
        let bikElement = doc.querySelectorAll('[data-mask="bik"]');
        let railwayCodeElement = doc.querySelectorAll('[data-mask="railway-code"]');
        let number15Element = doc.querySelectorAll('[data-mask="number15"]');

        for (let i = 0; i < phoneElement.length; i++) {
            phoneElement[i].onfocus = function () {
                this.placeholder = '+7 (955) 555-5555'
            };

            phoneElement[i].onblur = function () {
                if (this.value.indexOf('_') > -1) {
                    this.classList.add('error-mask');
                } else {
                    this.classList.remove('error-mask');
                }
            };

            vanillaTextMask.maskInput({
                inputElement: phoneElement[i],
                mask: phoneMask,
                showMask: false,
            });
        }

        for (let i = 0; i < timeElement.length; i++) {
            timeElement[i].onfocus = function () {
                this.placeholder = '__:__'
            };

            vanillaTextMask.maskInput({
                inputElement: timeElement[i],
                mask: timeMask,
                showMask: false,
                guide: false,
            });
        }

        for (let i = 0; i < innElement.length; i++) {
            vanillaTextMask.maskInput({
                inputElement: innElement[i],
                mask: innMask,
                showMask: false,
                guide: false,
            });
        }

        for (let i = 0; i < okpoElement.length; i++) {
            vanillaTextMask.maskInput({
                inputElement: okpoElement[i],
                mask: okpoMask,
                showMask: false,
                guide: false,
            });
        }

        for (let i = 0; i < checkingAccElement.length; i++) {
            vanillaTextMask.maskInput({
                inputElement: checkingAccElement[i],
                mask: checkingAccMask,
                showMask: false,
                guide: false,
            });
        }

        for (let i = 0; i < bikElement.length; i++) {
            vanillaTextMask.maskInput({
                inputElement: bikElement[i],
                mask: bikMask,
                showMask: false,
                guide: false,
            });
        }

        for (let i = 0; i < railwayCodeElement.length; i++) {
            vanillaTextMask.maskInput({
                inputElement: railwayCodeElement[i],
                mask: railwayCodeMask,
                showMask: false,
                guide: false,
            });
        }

        for (let i = 0; i < number15Element.length; i++) {
            number15Element[i].onblur = function () {
                if (/^0+/.test(this.value)) {
                    this.classList.add('error-mask');
                } else {
                    this.classList.remove('error-mask');
                }
            };

            vanillaTextMask.maskInput({
                inputElement: number15Element[i],
                mask: number15Mask,
                showMask: false,
                guide: false,
            });
        }
    },
};

module.exports = inputMask;
