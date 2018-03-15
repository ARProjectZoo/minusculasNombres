<?php
class Controller_Animals extends Controller_Base
{
 
   public function post_create()
 {
 
 $authenticated = $this->authenticate();
    $arrayAuthenticated = json_decode($authenticated, true);
    if($arrayAuthenticated['authenticated'])
    {
     $decodedToken = $this->decode($arrayAuthenticated['data']);
     if ($decodedToken->id == $this->id_admin)
     {
        try 
        {//photo
            if (!isset($_FILES['photo']) || empty($_FILES['photo'])) 
            {
            	return $this->respuesta(400,'La photo esta vacia', '');
            }
            //name
            if(!isset($_POST['name']) || empty($_POST['name']))
            {
            	return $this->respuesta(400,'El nombre esta vacio', '');
            }
			//description
            if( !isset($_POST['description']) || empty($_POST['description']))
            {
                return $this->respuesta(400,'La description esta vacio', '');
      
            }
            //coordenadas
            if(!isset($_POST['x']) || empty($_POST['x']))
            {
            	return $this->respuesta(400, 'Coordenadas X vacias', '');
            }
            if(!isset($_POST['y']) || empty($_POST['y']))
            {
                return $this->respuesta(400, 'Coordenadas Y vacias', '');
            }

			$config = array(
          	  'path' => DOCROOT . 'assets/img',
          	  'randomize' => true,
          	  'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
            );
            Upload::process($config);
            $photoToSave = "";
            if (Upload::is_valid())
            {
                Upload::save();
                foreach(Upload::get_files() as $file)
                {
                	$photoToSave = 'http://localhost:8888/CAMBIOAPI/public/assets/img/'. $file['saved_as'];
                }
            }
            foreach (Upload::get_errors() as $file)
            {
                          return $this->respuesta(500, 'Error en el servidor', '');
              }
                $newStory = $this->newStory($_POST, $photoToSave, $decodedToken);
                $json = $this->saveStory($newStory);
                return $json;
        }
		catch (Exception $e)
          {
            return $this->respuesta(500, $e->getMessage(), '');
          }
   		}
        else 
        {
                return $this->respuesta(400, 'No eres el admin', '');
        }
    }     
}
 //borrar
public function post_delete()
    {
	    $authenticated = $this->authenticate();
	    $arrayAuthenticated = json_decode($authenticated, true);
	     
	    if($arrayAuthenticated['authenticated'])
	    {
	      	$decodedToken = JWT::decode($arrayAuthenticated["data"], MY_KEY, array('HS256'));
	       	if(!empty($_POST['id']))
	       	{
	           $story = Model_Stories::find($_POST['id']);
	           if(isset($story))
	           {
	            if($decodedToken->id == $story->id_user){
	             $story->delete(); 
	     
	             $json = $this->response(array(
	                 'code' => 200,
	                 'message' => 'animal borrado',
	                 'data' => ''
	             ));
	             return $json;
	            }
	            else
	            {
	             $json = $this->response(array(
	                 'code' => 401,
	                 'message' => 'No puede borrar un animal que no es tuyo',
	                 'data' => ''
	              ));
	              return $json;
	            }
	           }
	           else
	           {
	            $json = $this->response(array(
	                 'code' => 401,
	                 'message' => 'Animal no valido',
	                 'data' => ''
	              ));
	              return $json;
	            }
	          }
	          else
	          {
	            $json = $this->response(array(
	                'code' => 400,
	                'message' => 'El id no puede estar vacio',
	                'data' => ''
	       ));
	       return $json;
	       }
	         }
	         else
	         {
	           $json = $this->response(array(
	               'code' => 400,
	               'message' => 'Falta el autorizacion',
	               'data' => ''
	            ));
	            return $json;
	        }
    }






    

    //visualizacion
    public function get_download()
    {

        $authenticated = $this->authenticate();
        $arrayAuthenticated = json_decode($authenticated, true);
          if($arrayAuthenticated['authenticated'])
          {
          $decodedToken = $this->decode($arrayAuthenticated['data']);
              if ($decodedToken->id != 1)
              {

              $animals = Model_Animals::find('all');
                if(!empty($animals)){
                  foreach ($animals as $key => $animal) {
                    $arrayAnimals[] = $animal;
                  }
                  //$data = Arr::reindex($elements);
                  $json = $this->response(array(
                    'code' => 200,
                    'message' => 'mostrando lista de animales del usuario', 
                    'data' => $arrayAnimals
                    )); 
                    return $json; 
                }
                else
                {
                  $json = $this->response(array(
                    'code' => 202,
                    'message' => 'Aun no tienes ningun elemento',
                    'data' => ''
                    ));
                    return $json;
                  }
              }
              else
              {
                return $this->respuesta(400, 'Eres el admin', '');
              }
          }
    
    }



      private function newAnimal($input)
      {
      $animal = Model_Animals();
      $animal->name = $input['name'];
      $animal->description = $input['description'];
      $animal->photo = "";
      $animal->x = $input['x'];
      $animal->y = $input['y'];
      $animal->id_continent = $input['id_continent'];
      $animal->id_user = $this->id_admin;
      return $animal;
     }


     
     private function saveAnimal($animal)
     {
         $animalExists = Model_Animals::find('all', 
                array('where' => array(
           array('name', '=', $animal->name),
                      )
           
               ));
        if(empty($animalExists))
        {
          $animaltoSave = $animal;
          $animaltoSave->save();
          $arrayData = array();
          $arrayData['name'] = $animal->name;
          return $this->respuesta(201, 'Animal creado', $arrayData);
        }
         else
        {
          return $this->respuesta(204, 'Animal ya creado', '');
        }
      }
}
?>