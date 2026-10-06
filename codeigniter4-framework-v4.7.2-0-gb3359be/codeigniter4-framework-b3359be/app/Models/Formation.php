<?php 

namespace App\Models;

use CodeIngiter\Model;

class FormationModel extends Models 
{
    protected $table ='formations';

    protected $allowedFields =['id','titre','contenu'];


}