<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RaffleModel;
use App\Models\RaffleNumberModel;
use App\Models\PrizeModel;
use App\Models\WinnerModel;
use App\Entities\Raffle;
use App\Services\RaffleDrawService;

class RaffleController extends BaseController
{
    protected RaffleModel $raffleModel;
    protected RaffleNumberModel $numberModel;
    protected PrizeModel $prizeModel;

    public function __construct()
    {
        $this->raffleModel = new RaffleModel();
        $this->numberModel = new RaffleNumberModel();
        $this->prizeModel  = new PrizeModel();
    }

    /**
     * Lista todas as rifas
     */
    public function index()
    {
        $userId = session()->get('user_id');
        $role = session()->get('user_role');

        $builder = $this->raffleModel->orderBy('created_at', 'DESC');
        if ($role !== 'admin') {
            $builder->where('user_id', $userId);
        }

        $data = [
            'raffles' => $builder->findAll(),
        ];

        return view('admin/raffles/index', $data);
    }

    /**
     * Endpoint para recuperar rifas (ex: autocomplete/associação)
     * GET /admin/raffles/search?q=...&status=active&limit=20
     */
    public function search()
    {
        $term = trim((string) $this->request->getGet('q'));
        $status = $this->request->getGet('status');
        $limit = (int) ($this->request->getGet('limit') ?? 20);
        $limit = max(1, min(50, $limit));
        $page = (int) ($this->request->getGet('page') ?? 1);
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON(['items' => []]);
        }

        $builder = $this->raffleModel
            ->select('id, name, slug, status, created_at')
            ->where('user_id', $userId);

        if (is_string($status) && $status !== '') {
            $builder->where('status', $status);
        }

        if ($term !== '') {
            $builder
                ->groupStart()
                ->like('name', $term)
                ->orLike('description', $term)
                ->groupEnd();
        }

        $raffles = $builder
            ->orderBy('created_at', 'DESC')
            ->limit($limit + 1, $offset)
            ->findAll();

        $hasMore = count($raffles) > $limit;
        if ($hasMore) {
            $raffles = array_slice($raffles, 0, $limit);
        }

        $items = array_map(static function ($raffle) {
            return [
                'id'     => (int) $raffle->id,
                'text'   => (string) ($raffle->name ?? $raffle->slug ?? ('Rifa #' . $raffle->id)),
                'status' => (string) ($raffle->status ?? ''),
                'slug'   => (string) ($raffle->slug ?? ''),
            ];
        }, $raffles);

        return $this->response->setJSON([
            'items'   => $items,
            'page'    => $page,
            'hasMore' => $hasMore,
        ]);
    }

    /**
     * Formulário de nova rifa
     */
    public function new()
    {
        return view('admin/raffles/form', [
            'raffle' => null,
        ]);
    }

    /**
     * Cria uma nova rifa
     */
    public function create()
    {
        $rules = [
            'name'              => 'required|min_length[3]|max_length[255]',
            'description'       => 'permit_empty',
            'prize_description' => 'permit_empty|max_length[500]',
            'price'             => 'required|decimal|greater_than[0]',
            'quantity'          => 'required|integer|greater_than[0]|less_than_equal_to[100000]',
            'start_number'      => 'permit_empty|integer|greater_than_equal_to[0]',
            'min_per_purchase'  => 'permit_empty|integer|greater_than[0]',
            'max_per_purchase'  => 'permit_empty|integer|greater_than[0]',
            'draw_date'         => 'permit_empty|valid_date',
            'image'             => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Processa upload de imagem
        $imageName = null;
        $image = $this->request->getFile('image');
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $imageName);
        }

        $raffle = new Raffle([
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description'),
            'prize_description' => $this->request->getPost('prize_description'),
            'price'             => $this->request->getPost('price'),
            'quantity'          => $this->request->getPost('quantity'),
            'start_number'      => $this->request->getPost('start_number') ?: 0,
            'min_per_purchase'  => $this->request->getPost('min_per_purchase') ?: 1,
            'max_per_purchase'  => $this->request->getPost('max_per_purchase') ?: 100,
            'draw_date'         => $this->request->getPost('draw_date') ?: null,
            'image'             => $imageName,
            'status'            => 'active',
            'is_featured'       => $this->request->getPost('is_featured') ? 1 : 0,
            'user_id'           => session()->get('user_id'),
        ]);

        $raffleId = $this->raffleModel->insert($raffle);

        if (!$raffleId) {
            return redirect()->back()->withInput()->with('error', 'Erro ao criar rifa.');
        }

        // Gera os números automaticamente se marcado
        if ($this->request->getPost('generate_numbers')) {
            $this->numberModel->generateNumbers(
                $raffleId,
                $raffle->quantity,
                $raffle->start_number
            );
            
            $this->raffleModel->update($raffleId, ['numbers_generated' => 1]);
        }

        return redirect()->to('admin/raffles')->with('success', 'Rifa criada com sucesso!');
    }

    /**
     * Exibe detalhes de uma rifa
     */
    public function show($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!$this->canAccessRaffle($raffle)) {
            return redirect()->to('admin/raffles')->with('error', 'Você não tem permissão para acessar esta rifa.');
        }

        $data = [
            'raffle'  => $raffle,
            'numbers' => $this->numberModel->getAllByRaffle($id),
            'stats'   => $this->numberModel->getStats($id),
            'prizes'  => $this->prizeModel->getByRaffle((int) $id),
        ];

        return view('admin/raffles/show', $data);
    }

    /**
     * Formulário de edição
     */
    public function edit($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!$this->canAccessRaffle($raffle)) {
            return redirect()->to('admin/raffles')->with('error', 'Você não tem permissão para acessar esta rifa.');
        }

        return view('admin/raffles/form', [
            'raffle' => $raffle,
        ]);
    }

    /**
     * Atualiza uma rifa
     */
    public function update($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!$this->canAccessRaffle($raffle)) {
            return redirect()->to('admin/raffles')->with('error', 'Você não tem permissão para atualizar esta rifa.');
        }

        $rules = [
            'name'              => 'required|min_length[3]|max_length[255]',
            'description'       => 'permit_empty',
            'prize_description' => 'permit_empty|max_length[500]',
            'price'             => 'required|decimal|greater_than[0]',
            'min_per_purchase'  => 'permit_empty|integer|greater_than[0]',
            'max_per_purchase'  => 'permit_empty|integer|greater_than[0]',
            'draw_date'         => 'permit_empty|valid_date',
            'status'            => 'required|in_list[active,finished,cancelled]',
        ];

        $image = $this->request->getFile('image');
        if ($image && $image->isValid()) {
            $rules['image'] = 'max_size[image,2048]|is_image[image]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Processa upload de imagem
        $imageName = $raffle->image;
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Remove imagem antiga
            if ($raffle->image && file_exists(FCPATH . 'uploads/' . $raffle->image)) {
                unlink(FCPATH . 'uploads/' . $raffle->image);
            }
            
            $imageName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $imageName);
        }

        $raffle->fill([
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description'),
            'prize_description' => $this->request->getPost('prize_description'),
            'price'             => $this->request->getPost('price'),
            'min_per_purchase'  => $this->request->getPost('min_per_purchase') ?: 1,
            'max_per_purchase'  => $this->request->getPost('max_per_purchase') ?: 100,
            'draw_date'         => $this->request->getPost('draw_date') ?: null,
            'image'             => $imageName,
            'status'            => $this->request->getPost('status'),
            'is_featured'       => $this->request->getPost('is_featured') ? 1 : 0,
        ]);

        if ($this->raffleModel->save($raffle)) {
            return redirect()->to('admin/raffles/' . $id)->with('success', 'Rifa atualizada com sucesso!');
        }

        return redirect()->back()->withInput()->with('error', 'Erro ao atualizar rifa.');
    }

    /**
     * Exclui uma rifa
     */
    public function delete($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!$this->canAccessRaffle($raffle)) {
            return redirect()->to('admin/raffles')->with('error', 'Você não tem permissão para excluir esta rifa.');
        }

        // Verifica se tem números vendidos
        $soldCount = $this->numberModel->countByStatus($id, 'sold');
        if ($soldCount > 0) {
            return redirect()->to('admin/raffles')->with('error', 'Não é possível excluir uma rifa com números vendidos.');
        }

        // Remove imagem
        if ($raffle->image && file_exists(FCPATH . 'uploads/' . $raffle->image)) {
            unlink(FCPATH . 'uploads/' . $raffle->image);
        }

        if ($this->raffleModel->delete($id)) {
            return redirect()->to('admin/raffles')->with('success', 'Rifa excluída com sucesso!');
        }

        return redirect()->to('admin/raffles')->with('error', 'Erro ao excluir rifa.');
    }

    /**
     * Gera números para a rifa
     */
    public function generateNumbers($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!$this->canAccessRaffle($raffle)) {
            return redirect()->to('admin/raffles')->with('error', 'Você não tem permissão para gerar números nesta rifa.');
        }

        if ($raffle->numbers_generated) {
            return redirect()->to('admin/raffles/' . $id)->with('error', 'Os números já foram gerados para esta rifa.');
        }

        $this->numberModel->generateNumbers($id, $raffle->quantity, $raffle->start_number);
        $this->raffleModel->update($id, ['numbers_generated' => 1]);

        return redirect()->to('admin/raffles/' . $id)->with('success', 'Números gerados com sucesso!');
    }

    /**
     * Realiza o sorteio
     */
    public function draw($id = null)
    {
        $raffle = $this->raffleModel->find($id);

        if (!$raffle) {
            return redirect()->to('admin/raffles')->with('error', 'Rifa não encontrada.');
        }

        if (!$this->canAccessRaffle($raffle)) {
            return redirect()->to('admin/raffles')->with('error', 'Você não tem permissão para sortear esta rifa.');
        }

        $service = new RaffleDrawService();
        $result = $service->drawRaffle((int) $id, true, false);

        if (!$result['ok']) {
            return redirect()->to('admin/raffles/' . $id)->with('error', $result['message']);
        }

        $first = $result['winners'][0] ?? null;
        $winningNumber = $first['winning_number'] ?? null;
        $msg = $winningNumber !== null
            ? "Sorteio realizado! Número vencedor (1º prêmio): {$winningNumber}"
            : 'Sorteio realizado!';

        return redirect()->to('admin/raffles/' . $id)->with('success', $msg);
    }

    private function canAccessRaffle($raffle): bool
    {
        $role = session()->get('user_role');
        if ($role === 'admin') {
            return true;
        }

        $userId = session()->get('user_id');
        return (int) ($raffle->user_id ?? 0) === (int) $userId;
    }
}
