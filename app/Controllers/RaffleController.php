<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RaffleModel;
use App\Entities\Raffle;
use CodeIgniter\HTTP\ResponseInterface;

class RaffleController extends BaseController
{
    protected RaffleModel $raffleModel;

    public function __construct()
    {
        $this->raffleModel = new RaffleModel();
    }

    /**
     * Lista todas as rifas
     */
    public function index()
    {
        $data = [
            'raffles' => $this->raffleModel->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('raffles/index', $data);
    }

    /**
     * Exibe o formulário para criar uma nova rifa
     */
    public function new()
    {
        return view('raffles/new');
    }

    /**
     * Cria uma nova rifa
     */
    public function create()
    {
        $rules = [
            'name'        => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[5000]',
            'price'       => 'required|decimal|greater_than[0]',
            'quantity'    => 'required|integer|greater_than[0]',
            'draw_date'   => 'permit_empty|valid_date',
            'image'       => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]',
        ];

        $messages = [
            'name' => [
                'required'   => 'O nome da rifa é obrigatório.',
                'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
                'max_length' => 'O nome não pode ter mais de 255 caracteres.',
            ],
            'description' => [
                'max_length' => 'A descrição não pode ter mais de 5000 caracteres.',
            ],
            'price' => [
                'required'     => 'O preço é obrigatório.',
                'decimal'      => 'O preço deve ser um valor decimal válido.',
                'greater_than' => 'O preço deve ser maior que zero.',
            ],
            'quantity' => [
                'required'     => 'A quantidade de números é obrigatória.',
                'integer'      => 'A quantidade deve ser um número inteiro.',
                'greater_than' => 'A quantidade deve ser maior que zero.',
            ],
            'draw_date' => [
                'valid_date' => 'A data do sorteio deve ser uma data válida.',
            ],
            'image' => [
                'uploaded'  => 'Selecione uma imagem válida.',
                'max_size'  => 'A imagem não pode ter mais de 2MB.',
                'is_image'  => 'O arquivo deve ser uma imagem.',
                'mime_in'   => 'O arquivo deve ser uma imagem JPG, PNG ou GIF.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Processa o upload da imagem
        $imageName = null;
        $image = $this->request->getFile('image');
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $imageName);
        }

        // Cria a entidade
        $raffle = new Raffle([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'quantity'    => $this->request->getPost('quantity'),
            'draw_date'   => $this->request->getPost('draw_date') ?: null,
            'image'       => $imageName,
            'status'      => 'active',
        ]);

        if ($this->raffleModel->save($raffle)) {
            return redirect()->to('raffles')->with('success', 'Rifa criada com sucesso!');
        }

        return redirect()->back()->withInput()->with('error', 'Erro ao criar a rifa. Tente novamente.');
    }

    /**
     * Exibe os detalhes de uma rifa
     */
    public function show($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('raffles')->with('error', 'Rifa não encontrada.');
        }

        $data = [
            'raffle' => $raffle,
        ];

        return view('raffles/show', $data);
    }

    /**
     * Exibe o formulário para editar uma rifa
     */
    public function edit($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('raffles')->with('error', 'Rifa não encontrada.');
        }

        $data = [
            'raffle' => $raffle,
        ];

        return view('raffles/edit', $data);
    }

    /**
     * Atualiza uma rifa
     */
    public function update($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('raffles')->with('error', 'Rifa não encontrada.');
        }

        $rules = [
            'name'        => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[5000]',
            'price'       => 'required|decimal|greater_than[0]',
            'quantity'    => 'required|integer|greater_than[0]',
            'draw_date'   => 'permit_empty|valid_date',
            'status'      => 'required|in_list[active,finished,cancelled]',
        ];

        // Adiciona regra de imagem apenas se uma nova imagem foi enviada
        $image = $this->request->getFile('image');
        if ($image && $image->isValid()) {
            $rules['image'] = 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Processa o upload da imagem
        $imageName = $raffle->image;
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Remove a imagem antiga
            if ($raffle->image && file_exists(FCPATH . 'uploads/' . $raffle->image)) {
                unlink(FCPATH . 'uploads/' . $raffle->image);
            }
            
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $imageName);
        }

        // Atualiza a entidade
        $raffle->fill([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'quantity'    => $this->request->getPost('quantity'),
            'draw_date'   => $this->request->getPost('draw_date') ?: null,
            'image'       => $imageName,
            'status'      => $this->request->getPost('status'),
        ]);

        if ($this->raffleModel->save($raffle)) {
            return redirect()->to('raffles/' . $id)->with('success', 'Rifa atualizada com sucesso!');
        }

        return redirect()->back()->withInput()->with('error', 'Erro ao atualizar a rifa. Tente novamente.');
    }

    /**
     * Exclui uma rifa (soft delete)
     */
    public function delete($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('raffles')->with('error', 'Rifa não encontrada.');
        }

        // Remove a imagem se existir
        if ($raffle->image && file_exists(FCPATH . 'uploads/' . $raffle->image)) {
            unlink(FCPATH . 'uploads/' . $raffle->image);
        }

        if ($this->raffleModel->delete($id)) {
            return redirect()->to('raffles')->with('success', 'Rifa excluída com sucesso!');
        }

        return redirect()->to('raffles')->with('error', 'Erro ao excluir a rifa. Tente novamente.');
    }
}
