<?php $firstLevel = false; ?>
<? foreach ($arResult['ITEMS'] as $idManager => $manager) {?>
    <div class="account-table__wrap" data-toggle-wrap>
        <!-- Верхний уровень -->
        <? if ($arResult['CURRENT_MANAGER'] != $manager['ID']) {?>
            <?php $this->__component->getTemplateRow($idManager, $manager, true);?>
            <? $firstLevel = true;?>
        <? } ?>
        <? if($arResult['CURRENT_MANAGER'] == $manager['ID'] && !empty($manager['ITEMS'])) {?>
            <div class="account-table__items">
                <div class="account-table__wrap account-table__clients" data-toggle-wrap>
                    <?php $this->__component->getTemplateRowSaldo($manager['SALDO']);?>
                    <?php $this->__component->showKontragent($manager['ITEMS']);?>
                </div>
            </div>
        <? } ?>
        <!-- Выпадающий список 1 уровня -->
        <div class="account-table__items" <?= $arResult['CURRENT_MANAGER'] == $manager['ID'] ? '' :  'data-toggle-list'?>>
            <? if(isset($manager['ITEMS']) && !empty($manager['ITEMS']) && ($arResult['CURRENT_MANAGER'] != $manager['ID'])) {?>
                <div class="account-table__wrap account-table__clients" data-toggle-wrap>
                    <?php $this->__component->getTemplateRowSaldo($manager['SALDO']);?>
                    <?php $this->__component->showKontragent($manager['ITEMS']);?>
                </div>
            <? } ?>
            <? if (isset($manager['SUB'])) {?>
                <? foreach ($manager['SUB'] as $idSubManager => $subManager) {?>
                    <div class="account-table__wrap" data-toggle-wrap>
                        <?php $this->__component->getTemplateRow($idSubManager, $subManager, $firstLevel ? false : true);?>
                        <!-- Выпадающий список 2 уровня -->
                        <? if (isset($subManager['SUB'])) {?>
                            <div class="account-table__items" data-toggle-list>
                                <? if (isset($subManager['ITEMS']) && !empty($subManager['ITEMS'])) {?>
                                    <div class="account-table__wrap account-table__clients" data-toggle-wrap>
                                        <?php $this->__component->getTemplateRowSaldo($subManager['SALDO']);?>
                                        <?php $this->__component->showKontragent($subManager['ITEMS']);?>
                                    </div>
                                <? } ?>
                                <? foreach ($subManager['SUB'] as $idSubSubManager => $subSubManager) {?>
                                    <div class="account-table__wrap" data-toggle-wrap>
                                        <?php $this->__component->getTemplateRow($idSubSubManager, $subSubManager);?>
                                        <!-- Выпадающий список 3 уровня -->
                                        <? if (isset($subSubManager['SUB'])) {?>
                                            <div class="account-table__items account-table__items--3" data-toggle-list>
                                                <? if (isset($subSubManager['ITEMS']) && !empty($subSubManager['ITEMS'])) {?>
                                                    <div class="account-table__wrap account-table__clients" data-toggle-wrap>
                                                        <?php $this->__component->getTemplateRowSaldo($subSubManager['SALDO']);?>
                                                        <?php $this->__component->showKontragent($subSubManager['ITEMS']);?>
                                                    </div>
                                                <? } ?>
                                                <? foreach ($subSubManager['SUB'] as $idSubSubSubManager => $subSubSubManager) {?>
                                                    <div class="account-table__wrap" data-toggle-wrap>
                                                        <?php $this->__component->getTemplateRow($idSubSubSubManager, $subSubSubManager);?>
                                                        <? if (isset($subSubSubManager['ITEMS']) && !empty($subSubSubManager['ITEMS'])) {?>
                                                            <div class="account-table__items account-table__clients" data-toggle-list>
                                                                <?php $this->__component->getTemplateRowSaldo($subSubSubManager['SALDO']);?>
                                                                <?php $this->__component->showKontragent($subSubSubManager['ITEMS']);?>
                                                            </div>
                                                        <? } ?>
                                                    </div>
                                                <? } ?>
                                            </div>
                                        <? } ?>
                                        <? if (isset($subSubManager['ITEMS']) && !empty($subSubManager['ITEMS']) && !isset($subSubManager['SUB'])) {?>
                                            <div class="account-table__items" data-toggle-list>
                                                <div class="account-table__wrap account-table__clients" data-toggle-wrap>
                                                    <?php $this->__component->getTemplateRowSaldo($subSubManager['SALDO']);?>
                                                    <?php $this->__component->showKontragent($subSubManager['ITEMS']);?>
                                                </div>
                                            </div>
                                        <? } ?>
                                    </div>
                                <? } ?>
                            </div>
                        <? } ?>
                        <? if (isset($subManager['ITEMS']) && !empty($subManager['ITEMS']) && !isset($subManager['SUB'])) {?>
                            <div class="account-table__items" data-toggle-list>
                                <div class="account-table__wrap account-table__clients" data-toggle-wrap>
                                    <?php $this->__component->getTemplateRowSaldo($subManager['SALDO']);?>
                                    <?php $this->__component->showKontragent($subManager['ITEMS']);?>
                                </div>
                            </div>
                        <? } ?>
                    </div>
                 <? } ?>
            <? } ?>
        </div>
    </div>
<? } ?>
<? if(empty($arResult['ITEMS'])): ?>
    <div class="account-table__wrap" data-toggle-wrap>
        <div class="account-table__row">
            <span>Нет данных</span>
        </div>
    </div>
<? endif; ?>