let inputs = {
    run() {
        this.placeholder();
        this.addField();
        this.buttonClear();
    },
    
    placeholder() {
        $('[data-f-item]').each(function () {
            const $this  = $(this);
            const $field = $this.find('[data-f-field]');
            const $label = $this.find('[data-f-label]');

            if ($field.prop('disabled') === true || $field.prop('readonly')) {
                $label.addClass('disabled');
            }

            if($field.val() !== '')
                $label.addClass('active active--origin');
            else
                $label.removeClass('active active--origin');
    
            $field.on('focus', function () {
                $label.addClass('active');
            });
    
            $field.on('blur', function () {
                if($field.val() === '')
                    $label.removeClass('active active--origin');
            });
    
            /* select */
            $field.on('change', function () {
                if($field.val() === '')
                    $label.removeClass('active active--origin');
                else
                    $label.addClass('active');
            });
    
            $this.addClass('init');
        })
    },
    
    addField() {
        const area = $('.b-tabs__item');
        const divBtn = area.find('.b-form__bottom');
        const $template = $('[data-field]');
        let self = this;
    
        if(!$template.length)
            return;
    
        const $btn = $('[data-field-add]');
        $btn.on('click', () => createField());
        
        let createField = () => {

            let block = document.createElement('div');
            block.className = "b-form__item";
            block.setAttribute('data-f-item', '');
            block.setAttribute('data-field', '');
            block.setAttribute('data-new', 'Y');
            block.innerHTML = $template.html();
            
            let $block = $(block);

            $($block.find('.b-form__label')).removeClass('active');
            $($block.find('input')).val('').addClass('b-form__clear');
            $($block.find('input')).prop("disabled", false);
            $($block.find('input')).attr("data-new", 'Y');

            let ul = document.createElement('ul');
            ul.setAttribute('id', 'select-list');
            $($block.find('input')).after(ul);

            divBtn.find('button').prop("disabled", false);

            createRemoveBtn($block);

            $btn.before($(block));

            if ($($block.find('input')).attr('data-suggestion') === 'address'){

                window.Am.suggestions.setAddresses($($block.find('input')));
            }

            this.placeholder();
        };
        
        let createRemoveBtn = ($block) => {
            let btn = document.createElement('div');
            btn.className = "plus plus--cross";
            
            $block.append(btn);

            //$(btn).on('click', () => $block.remove());
            $(btn).on('click', function (e) {
                e.preventDefault();

                $block.remove()
                if (!$(".b-form__clear").attr('data-new')) {
                    divBtn.find('button').prop("disabled", true);
                }
            });
        }
    },

    buttonClear() {
        const $field = $('[data-order-feald]');
        const $wrap = $field.parent('.b-form__item');

        $field.on('input', function () {

            if($(this).val() !== '')
                $wrap.addClass('clear');
            else
                $wrap.removeClass('clear');
        });

        $('form').on('click', 'button[name=ACTION_CLEAR]', function(e) {
            $(this).closest('form').find("input[type=text], textarea").val("");

            $wrap.removeClass('clear');
            inputs.placeholder();
        });
    }
};

module.exports = inputs;
