<?php

declare(strict_types=1);

namespace App\Modules\Vet\Controllers;

use App\Controllers\BaseController;
use App\Models\GoatModel;
use App\Models\VetVisitModel;
use App\Models\WeightLogModel;

class AnimalController extends BaseController
{
    private GoatModel $goats;

    public function __construct()
    {
        $this->goats = new GoatModel();
    }

    /**
     * Full herd list for the vet — includes latest weight and health flag.
     */
    public function index(): string
    {
        $animals = $this->goats->getWithLatestWeight(0); // 0 = all members

        // Use getFullHerd so we get flag data too
        $animals = $this->goats->getFullHerd();

        return $this->dashboardView('vet/animals', [
            'pageTitle' => 'Animal Records',
            'animals'   => $animals,
        ]);
    }

    /**
     * Individual animal page — full health timeline.
     */
    public function show(int $id): string
    {
        $goat = $this->goats->find($id);

        if (! $goat) {
            return redirect()->to('/vet/animals')->with('error', 'Animal not found.');
        }

        $visits  = new VetVisitModel();
        $weights = new WeightLogModel();

        return $this->dashboardView('vet/animal_detail', [
            'pageTitle'      => $goat['name'] . ' (' . $goat['tag_number'] . ') — Health Record',
            'goat'           => $goat,
            'healthHistory'  => $visits->getByGoat($id),
            'weightHistory'  => $weights->getByGoat($id),
            'growthChart'    => $weights->getGrowthChart($id),
        ]);
    }

    /**
     * AJAX tag lookup for the visit log form.
     * GET /vet/animals/lookup?tag=PGF-1042
     * Returns JSON: { status: 'success', data: { id, name, breed, tag_number } }
     */
    public function lookup(): \CodeIgniter\HTTP\ResponseInterface
    {
        $tag  = $this->request->getGet('tag');
        $goat = $tag ? $this->goats->findByTag($tag) : null;

        if (! $goat) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Tag not found']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'id'         => $goat['id'],
                'name'       => $goat['name'],
                'breed'      => $goat['breed'],
                'sex'        => $goat['sex'],
                'tag_number' => $goat['tag_number'],
                'pen_id'     => $goat['pen_id'],
            ],
        ]);
    }
}
