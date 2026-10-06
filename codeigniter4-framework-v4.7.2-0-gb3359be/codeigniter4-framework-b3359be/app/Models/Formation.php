<?php 

namespace App\Models;

use CodeIngiter\Model;

class FormationModel extends Models 
{
    protected $table ='formations';

    protected $primaryKey = 'id';

    protected $allowedFields =['id','titre','description','niveau'];
    
}