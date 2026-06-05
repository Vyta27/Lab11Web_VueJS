<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ArtikelModel;

class Post extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model           = new ArtikelModel();
        $data['artikel'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }

    public function create()
    {
        $model = new ArtikelModel();
        $data  = [
            'judul' => $this->request->getVar('judul'),
            'isi'   => $this->request->getVar('isi'),
            'slug'  => url_title($this->request->getVar('judul'), '-', true),
        ];
        $model->insert($data);
        return $this->respondCreated([
            'status'   => 201,
            'messages' => ['success' => 'Data berhasil ditambahkan.']
        ]);
    }

    public function show($id = null)
    {
        $model = new ArtikelModel();
        $data  = $model->where('id', $id)->first();
        if ($data) {
            return $this->respond($data);
        }
        return $this->failNotFound('Data tidak ditemukan.');
    }

    public function update($id = null)
    {
        $model = new ArtikelModel();
        $data  = [
            'judul' => $this->request->getPost('judul') ?? $this->request->getVar('judul') ?? '',
            'isi'   => $this->request->getPost('isi')   ?? $this->request->getVar('isi')   ?? '',
        ];
        $model->update($id, $data);
        return $this->respond([
            'status'   => 200,
            'messages' => ['success' => 'Data berhasil diubah.']
        ]);
    }

    public function delete($id = null)
    {
        $model = new ArtikelModel();
        $data  = $model->where('id', $id)->first();
        if ($data) {
            $model->delete($id);
            return $this->respondDeleted([
                'status'   => 200,
                'messages' => ['success' => 'Data berhasil dihapus.']
            ]);
        }
        return $this->failNotFound('Data tidak ditemukan.');
    }
}