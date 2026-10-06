<?php

namespace App\Controllers;

class FormationController extends BaseController
{
    public function index(): string
    {
        return view('index');
    }

    public function apiGetAll()
    {
        $model = new FormationModel();
        $formations = $model->findAll();

        return $this->respond($formations, 200);
    }

    public function apiGetOne($id = null){

         $model = new FormationModel();
        $formation = $model->find($id);

        if ($formation === null) {
            return $this->failNotFound('Formation non trouvée');
        }

        return $this->respond($formation, 200);

    }

    public function apiCreate(){

    $model = new FormationModel();
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        if (empty($data)) {
            return $this->fail('Aucune donnée fournie', 400);
        }

        if ($model->insert($data) === false) {
            return $this->fail($model->errors(), 400);
        }

        $insertedId = $model->getInsertID();
        $data['id'] = $insertedId;

        return $this->respondCreated($data);

    }

}
