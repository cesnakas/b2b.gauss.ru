<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	'GROUPS' => array(),
	'PARAMETERS' => array(
		'NUM_RESULTS' => array(
			'NAME' => GetMessage('FRLM_PARAM_NUM_RESULTS'),
			'TYPE' => 'STRING',
			'DEFAULT' => '10',
		),
	)
);
?>