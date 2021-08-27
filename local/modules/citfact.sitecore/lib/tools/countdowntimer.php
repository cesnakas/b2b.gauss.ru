<?php


namespace Citfact\SiteCore\Tools;

use \Bitrix\Main\Type\DateTime;

/**
 * Class CountdownTimer
 * В этом классе реализованы проверки на выходные и праздничные дни, а также функция
 * обратного таймера.
 * @package Citfact\SiteCore\Tools
 *
 */
class CountdownTimer

{
    public $holidays = [];

    public function __construct(){
        $this->holidays=$this->getHolidays();
    }

 public function getHolidays(){
     $calendar = simplexml_load_file('http://xmlcalendar.ru/data/ru/'.date('Y').'/calendar.xml');
     $calendar = $calendar->days->day;

//все праздники за текущий год
     $arHolidays= [];
     foreach( $calendar as $day ){
         $d = (array)$day->attributes()->d;

         $d = $d[0];
         $d = substr($d, 3, 2).'.'.substr($d, 0, 2).'.'.date('Y');
         //не считая короткие дни
         if( $day->attributes()->t == 1 ) $arHolidays[] = $d;
     }
     return $arHolidays;
     }


   public  function isWeekend($date){
        $isWeekend = false;
        // если день недели для даты выходной, то это выходной день
        if($date->format('D') == 'Sat' || $date->format('D') == 'Sun') {
            $isWeekend = true;
        }
        return $isWeekend;
    }

// функция возвращает true, если день праздничный (ЗАГЛУШКА)
    public function isHoliday($date) {
        $dateIterator;
        $isHoliday = false;

        // сравним ДЕНЬ (именно день, а не дату) для выходных дней
        // если ДЕНЬ совпал, то день выходной.
        // если сравнивать DateTime, то никогда не совпадет,
        // если есть расхождение даже на секунду
        foreach($this->holidays as $holiday){
            // преобразуем строку из массива праздников в DateTime для сравнения
            $dateIterator = new \DateTime($holiday);
            //echo 'array -- '.$dateIterator->format('d.m.Y').' date -- '.$date->format('d.m.Y')."\n";
            if ($dateIterator->format('d.m.Y') == $date->format('d.m.Y')) {
                $isHoliday = true;
                //echo 'Holiday found'.$isHoliday."\n";
                break;
            }
        }
        return $isHoliday;
    }

    public function calcTicketHours($dateTicket){
        $dateBegin = new \DateTime($dateTicket); // если дата $dateTicket в виде строки
        $dateEnd = clone $dateBegin;
        $dateEnd->add(new \DateInterval('P3D')); // добавим три дня, чтобы получить конечную дату
        $date = clone $dateBegin; // наш итератор
        while ($date <= $dateEnd) {
            //echo 'Holiday check for date '.$date->format('d.m.Y')."<br>";
            if ($this->isHoliday($date)==true || $this->isWeekend($date)==true) {
                // увеличим дату окончания на 1 день, если это выходной ИЛИ праздник
                $dateEnd->add(new \DateInterval('P1D'));
            }
            $date->modify('+1 day'); // перейдем на следующий день (= итерируем)
        }
        $interval = $dateBegin->diff($dateEnd); // рассчитаем количество дней заявки с учетом выходных и праздничных

        $dateCurr = time(); //получаем текущую дату
        //Вычисляем, сколько секунд осталось до завершения
        // и переводим в часы, а если меньше ноля возвращаем 0
        if($dateEnd->getTimestamp() >= $dateCurr){
            $result = floor((($dateEnd->getTimestamp()) - $dateCurr)/(60*60));
       } else {
            $result = 0;
        }
        return $result;
    }

    //Меняем спряжение слова в зависимости от часов
    public  function getHourConjugation($date){
     $hours = $this->calcTicketHours($date);
     if(substr($hours,-1) == 1){
         $hour = ' час';
     }  elseif(substr($hours,-1) >1 && substr($hours,-1)< 5 ){
         $hour = ' часа';
     } elseif(substr($hours,-2)  && substr($hours,-2)== 1 ) {
         $hour = ' часов';
     } else{
         $hour = ' часов';
     }
     return $hour;
    }
}

