<?php
class Controller_Continent extends Controller_Base
{
	public function post_configContinent()
	{
		$typeAfrica = new Model_Continent();
		$typeAfrica->type = 'Africa';
		$typeAsia = new Model_Continent();
		$typeAsia->type = 'Asia';
		$typeAN = new Model_Continent();
		$typeAN->type = 'America del Norte';
		$typeAS = new Model_Continent();
		$typeAS->type = 'America del Sur';
		$typeACyC = new Model_Continent();
		$typeACyC->type = 'America central y Caribe';
		$typeEU = new Model_Continent();
		$typeEU->type = 'Europa';
		$typeOceania = new Model_Continent();
		$typeOceania->type = 'Oceania';

		
		$typeAfrica->save();
		$typeAsia->save();
		$typeAN->save();
		$typeAS->save();
		$typeACyC->save();
		$typeEU->save();
		$typeOceania->save();
		$json = $this->response(array(
                    'code' => 201,
                    'message' => 'Type Creados'
                ));
		
	}
}
?>