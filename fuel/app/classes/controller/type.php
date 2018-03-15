<?php
class Controller_Type extends Controller_Base
{
	public function post_configType()
	{
		$typeRest = new Model_Type();
		$typeRest->name = 'Restaurantes';
		$typeExh = new Model_Type();
		$typeExh->name = 'Exhibiciones';
		
		$typeRest->save();
		$typeExh->save();
		$json = $this->response(array(
                    'code' => 201,
                    'message' => 'Type Creados'
                ));
		
	}
}
?>