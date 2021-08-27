##  \Citfact\DataCache
#### Методы для кеширования данных. 


### IBlock

       // Список инфоблоков array('CODE' => 'ID') 
       DataCache\IBlockData\IBlockID()
       
       // Элементы инфоблока
       // $iblockId - id инфоблока
       // $filter - массив фильтра для CIBlockElement::GetList()
       // $select - массив выборки для CIBlockElement::GetList()
       DataCache\IBlockData\IBlockElements($iblockId, $filter = [], $select = [])
       
       //  ID свойства iblock array('CODE' => 'ID')
       // $iBlock - id инфоблока
       DataCache\IBlockData\IBlockProperties($iBlock)

       
       // ID свойства типа список array('XML_ID' => 'ID')
       // $iBlock - id инфоблока
       // $code - символьный код свойства
       DataCache\IBlockData\IBlockPropertyEnum($iBlock, $code)
       
       
       // Значения свойства типа список array('XML_ID' => 'VALUE')
       // $iBlock - id инфоблока
       // $code - символьный код свойства
       DataCache\IBlockData\IBlockPropertyEnumNames($iBlock, $code)
       
       
       
### HLBlock    
   
       // Список hlblock array('NAME' => 'ID')
       DataCache\HLBlockData\HLBlockId()
       
       
       // ID пользовательского свойства hlblock типа список array('XML_ID' => 'ID')
       // $HLBlockID - id hlblock
       // $fieldName - символьный код свойства
       DataCache\HLBlockData\DictionaryEnum($HLBlockID, $fieldName)
       
       
       // Значения пользовательского свойства hlblock типа список 
       // $HLBlockID - id hlblock
       // $fieldName - символьный код свойства
       DataCache\HLBlockData\DictionaryEnumNames($HLBlockID, $fieldName)
       array('XML_ID' => array(
          'ID' => $value['ID'],
          'VALUE' => $value['VALUE'],
          'XML_ID' => $value['XML_ID'],
      ))
      
      
            
### Price 

       // Список типов цен array('XML_ID' => 'ID')
       DataCache\PriceData\PriceId()
     
      
      
### User

       // Список групп пользователей array('STRING_ID' => 'ID')
       DataCache\UserData\UserGroupID()
       
       
           
### Site 

       // Список доменов сайтов array('LID' => 'SERVER_NAME')
       DataCache\SiteData\SiteDomains()
              
       
           
### Payment 

       // Список платежных систем array('CODE' => 'ID')
       DataCache\PaymentData\PaymentId()
       
       
       
       
### Методы:

        // возвращает все данные
        getAllData()
        
        // возвращает одно значнеие по коду
        getByCode('code')
        
        
        
### Пример

        cmodule::includemodule('citfact.datacache');
        
        $ib = new \Citfact\DataCache\IBlockData\IBlockID();
        var_dump($ib->getByCode('catalog'));
        var_dump($ib->getAllData());