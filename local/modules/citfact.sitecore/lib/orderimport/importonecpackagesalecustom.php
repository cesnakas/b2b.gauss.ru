<?

namespace Citfact\SiteCore\OrderImport;

use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Sale\Cashbox\Cashbox1C;
use Bitrix\Sale\Cashbox\Internals\CashboxCheckTable;
use Bitrix\Sale\Exchange\Entity\OrderImport;
use Bitrix\Sale\Exchange\Entity\PaymentImport;
use Bitrix\Sale\Exchange\Entity\UserProfileImport;
use Bitrix\Sale\Exchange\EntityCollisionType;
use Bitrix\Sale\Exchange\EntityType;
use Bitrix\Sale\Exchange\ImportOneCPackage;
use Bitrix\Sale\Exchange\ManagerImport;
use Bitrix\Sale\Exchange\OneC\CollisionOrder;
use Bitrix\Sale\Exchange\OneC\DocumentType;
use Bitrix\Sale\Exchange\OneC\OrderDocument;
use Bitrix\Sale\Exchange\OneC\PaymentDocument;
use Bitrix\Sale\Exchange\OneC\ShipmentDocument;
use Bitrix\Sale\Internals\OrderPropsTable;

class ImportOneCPackageSaleCustom extends ImportOneCPackage
{
    /** @var OrderPropsTable $orderPropsTable */
    private $orderPropsTable;

    private $orderProperties = [];
    private $orderPropertiesByCodeConfig = [];

    public function __construct()
    {
        $this->orderPropsTable = new OrderPropsTable();
    }

    public function parse(array $rawFields)
    {
        $result = new Result();
        $list = array();

        foreach ($rawFields as $raw) {
            $documentTypeId = $this->resolveDocumentTypeId($raw);

            $document = $this->documentFactoryCreate($documentTypeId);

            if ($document instanceof OrderDocument) {
                $document = new OrderDocumentCustom();
                $traits = $document::getAllTraits($raw);
                $this->orderProperties = $this->getOrderPropertyValues($traits);
                $this->orderPropertiesByCodeConfig = $this->getOrderPropertyValuesByCodeConfig($traits);
            }
            $fields = $document::prepareFieldsData($raw);
            $document->setFields($fields);

            $list[] = $document;
        }

        $result->setData($list);

        return $result;
    }

    /**
     * @param $traits
     * @return array
     */
    private function getOrderPropertyValues($traits)
    {
        $result = [];
        $traitNames = [];
        foreach ($traits as $name => $item) {
            $traitNames[] = $name;
        }
        $properties = $this->getOrderPropertiesData($traitNames);
        foreach ($properties as $item) {
            $result[$item['ID']] = $traits[$item['NAME']];
        }
        return $result;
    }

    /**
     * @param $traits
     * @return array
     */
    private function getOrderPropertyValuesByCodeConfig($traits)
    {
        $result = [];
        $traitNames = [];
        foreach ($traits as $name => $item) {
            $traitNames[] = $name;
        }

        $config = [];
        $r = \CSaleExport::GetList(array(), array("PERSON_TYPE_ID" => []));
        while($ar = $r->Fetch())
        {
            $config[$ar["PERSON_TYPE_ID"]] = unserialize($ar["VARS"]);
        }

        $properties = $this->getOrderPropertiesData($traitNames);
        foreach ($properties as $item) {
            foreach ($config as $pTypeid => $vars) {
                foreach ($vars as $code => $var) {
                    if ($var['NAME'] == $item['NAME']) {
                        $result[$code] = $traits[$item['NAME']];
                    }
                }
            }
        }

        return $result;
    }

    public function getOrderPropertiesData($names)
    {
        $result = [];
        $res = $this->orderPropsTable->getList([
            'select' => ['ID', 'NAME'],
            'filter' => [[
                '=ACTIVE' => 'Y',
                '=NAME' => $names,
            ]],
        ]);
        while ($item = $res->fetch()) {
            $result[] = $item;
        }
        return $result;
    }


    /**
     * @param array $rawData
     * @return Result|\Bitrix\Sale\Result
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotSupportedException
     */
    public function process(array $rawData)
    {
        /** @var Result $r */
        $r = $this->parse($rawData);
        if (!$r->isSuccess())
            return $r;

        $documents = $r->getData();
        $r = $this->convert($documents);
        if (!$r->isSuccess())
            return $r;

        $entityItems = $r->getData();

        foreach ($entityItems as &$item) {
            /**@var OrderImport $item */
            if ($item instanceof OrderImport) {
                /**
                 * устарело и не работает
                 * или работает. Непонятно
                 */
                $traits = $item->getField('TRAITS');
                if ($this->orderProperties) {
                    $traits['ORDER_PROP'] = $this->orderProperties;
                }
                $item->setField('TRAITS', $traits);

            } elseif ($item instanceof UserProfileImport) {
                /**
                 * это работает
                 * в ImportOneCPackage::import - $itemParent = UserProfileImport $item
                 */
                if (!empty($this->orderPropertiesByCodeConfig)) {
                    $item->setField('ORDER_PROPS', $this->orderPropertiesByCodeConfig);
                }
            }
        }
        unset($item);

        $r = $this->import($entityItems);

        $this->logger($entityItems);

        return $r;
    }

    protected function convert(array $documents)
    {
        $documentOrder = $this->getDocumentByTypeId(DocumentType::ORDER, $documents);

        if ($documentOrder instanceof OrderDocument) {
            //region Presset - create Shipment if Service in the Order by information from 1C
            $documentShipment = $this->getDocumentByTypeId(DocumentType::SHIPMENT, $documents);
            if ($documentShipment == null) {
                $fieldsOrder = $documentOrder->getFieldValues();
                $items = $this->getProductsItems($fieldsOrder);

                if ($this->deliveryServiceExists($items)) {
                    $shipment['ID_1C'] = $documentOrder->getField('ID_1C');
                    $shipment['VERSION_1C'] = $documentOrder->getField('VERSION_1C');
                    $shipment['ITEMS'] = $items;
                    $shipment['REK_VALUES']['1C_TRACKING_NUMBER'] = $this->getDefaultTrackingNumber($documentOrder);

                    $documentShipment = new ShipmentDocument();
                    $documentShipment->setFields($shipment);
                    $documents[] = $documentShipment;
                }
            }
            //endregion

            foreach ($documents as $document) {
                if ($document instanceof PaymentDocument) {
                    $paymentFields = $document->getFieldValues();
                    $paymentFields['REK_VALUES']['PAY_SYSTEM_ID_DEFAULT'] = $this->getDefaultPaySystem($documentOrder);
                    $document->setFields($paymentFields);
                }

                if ($document instanceof ShipmentDocument) {
                    $shimpentFields = $document->getFieldValues();
                    $shimpentFields['REK_VALUES']['DELIVERY_SYSTEM_ID_DEFAULT'] = $this->getDefaultDeliverySystem($documentOrder);
                    $document->setFields($shimpentFields);
                }
            }
        } else {
            $settingsShipment = ManagerImport::getSettingsByType(static::getShipmentEntityTypeId());

            if ($settingsShipment->canCreateOrder(static::getShipmentEntityTypeId()) == 'Y') {
                $documentShipment = $this->getDocumentByTypeId(DocumentType::SHIPMENT, $documents);
                if ($documentShipment !== null) {
                    $order['ID_1C'] = $documentShipment->getField('ID_1C');
                    $order['VERSION_1C'] = $documentShipment->getField('VERSION_1C');
                    $order['AMOUNT'] = $documentShipment->getField('AMOUNT');
                    $order['ITEMS'] = $documentShipment->getField('ITEMS');
                    $order['TAXES'] = $documentShipment->getField('TAXES');
                    $order['AGENT'] = $documentShipment->getField('AGENT');

                    $documentOrder = new OrderDocument();
                    $documentOrder->setFields($order);
                    $documents[] = $documentOrder;
                }
            }
        }

        return parent::convert($documents);
    }

    /**
     * @param OrderDocument $document
     * @return null|string
     */
    protected function getDefaultTrackingNumber(OrderDocument $document)
    {
        $fields = $document->getFieldValues();
        return isset($fields['REK_VALUES']['1C_TRACKING_NUMBER']) ? $fields['REK_VALUES']['1C_TRACKING_NUMBER'] : null;
    }

    /**
     * @param OrderDocument $document
     * @return null|int
     */
    protected function getDefaultPaySystem(OrderDocument $document)
    {
        $fields = $document->getFieldValues();
        return isset($fields['REK_VALUES']['PAY_SYSTEM_ID']) ? $fields['REK_VALUES']['PAY_SYSTEM_ID'] : null;
    }

    /**
     * @param OrderDocument $document
     * @return null|int
     */
    protected function getDefaultDeliverySystem(OrderDocument $document)
    {
        $fields = $document->getFieldValues();
        return isset($fields['REK_VALUES']['DELIVERY_SYSTEM_ID']) ? $fields['REK_VALUES']['DELIVERY_SYSTEM_ID'] : null;
    }

    protected function UpdateCashBoxChecks(OrderImport $orderImport, array $items)
    {
        $result = new Result();
        $bCheckUpdated = false;

        $order = $orderImport->getEntity();

        foreach ($items as $item) {
            /** @var PaymentImport $item */

            if ($item->getOwnerTypeId() == static::getPaymentCashEntityTypeId() ||
                $item->getOwnerTypeId() == static::getPaymentCashLessEntityTypeId() ||
                $item->getOwnerTypeId() == static::getPaymentCardEntityTypeId()
            ) {
                /** @var  $params */
                $params = $item->getFieldValues();
                static::load($item, $params['TRAITS'], $order);

                if ($item->getEntityId() > 0) {
                    $entity = $item->getEntity();

                    if (isset($params['CASH_BOX_CHECKS'])) {
                        $fields = $params['CASH_BOX_CHECKS'];

                        if ($fields['ID'] > 0) {
                            $res = CashboxCheckTable::getById($fields['ID']);
                            if ($data = $res->fetch()) {
                                if ($data['STATUS'] <> 'Y') {
                                    $applyResult = Cashbox1C::applyCheckResult($params['CASH_BOX_CHECKS']);
                                    $bCheckUpdated = $applyResult->isSuccess();
                                }
                            } else {
                                $item->setCollisions(EntityCollisionType::PaymentCashBoxCheckNotFound, $entity);
                            }
                        }
                    }
                }
            }
        }

        /** @var CollisionOrder $collision */
        $collision = $orderImport->getCurrentCollision(EntityType::ORDER);
        $collisionTypes = $collision->getCollision($orderImport);

        if (count($collisionTypes) > 0 && $bCheckUpdated) {
            return $result;
        } else {
            $result->addError(new Error('', 'CASH_BOX_CHECK_IGNORE'));
        }

        return $result;
    }
}
