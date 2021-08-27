<? if ($APPLICATION->GetCurDir() === '/catalog/') { ?>
    <div class="seo">
        <div class="title-1">
        <span>
            <?php
            $APPLICATION->IncludeFile(
                SITE_DIR . "local/include/areas/catalog/root_title.php",
                Array(),
                Array("MODE" => "html")
            ); ?>
        </span>
        </div>

        <div data-show-more>
            <?php
            if ($APPLICATION->GetCurPage() != '/index.php') {
                $APPLICATION->IncludeFile(
                    SITE_DIR . "local/include/areas/catalog/root_text.php",
                    Array(),
                    Array("MODE" => "html")
                );
            } ?>
        </div>

        <a class="link-more link-more--toggle hidden" href="javascript:void(0)" title="Читать далее" data-show-more-btn>
            <span>Читать далее</span>
            <span>Скрыть</span>
            <svg class='i-icon'>
                <use xlink:href='#icon-arrow-r'/>
            </svg>
        </a>
    </div>
<? } ?>