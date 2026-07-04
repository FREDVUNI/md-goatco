<?php

declare(strict_types=1);

namespace App\Modules\Vet\Controllers;

use App\Controllers\BaseController;

class TaskController extends BaseController
{
    private \CodeIgniter\Database\BaseBuilder $scheduleTable;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->scheduleTable = $db->table('vet_schedules');
    }

    /**
     * Show today's tasks assigned to the logged-in vet.
     */
    public function index(): string
    {
        $vetId  = $this->currentUserId();
        $search = $this->searchTerm();

        $todayBuilder = \Config\Database::connect()->table('vet_schedules')
            ->select('vet_schedules.*')
            ->where('DATE(scheduled_at)', date('Y-m-d'))
            ->where('status !=', 'cancelled')
            ->groupStart()->where('assigned_vet_id', $vetId)->orWhere('assigned_vet_id IS NULL')->groupEnd()
            ->orderBy('scheduled_at', 'ASC');

        $upcomingBuilder = \Config\Database::connect()->table('vet_schedules')
            ->select('vet_schedules.*')
            ->where('DATE(scheduled_at) >', date('Y-m-d'))
            ->where('DATE(scheduled_at) <=', date('Y-m-d', strtotime('+7 days')))
            ->where('status !=', 'cancelled')
            ->groupStart()->where('assigned_vet_id', $vetId)->orWhere('assigned_vet_id IS NULL')->groupEnd()
            ->orderBy('scheduled_at', 'ASC');

        if ($search) {
            $todayBuilder->groupStart()->like('task',$search)->orLike('description',$search)->orLike('animals_desc',$search)->groupEnd();
            $upcomingBuilder->groupStart()->like('task',$search)->orLike('description',$search)->orLike('animals_desc',$search)->groupEnd();
        }

        [$tasks, ] = $this->paginateBuilder($todayBuilder, 10, 'today');
        [$upcoming, $pager] = $this->paginateBuilder($upcomingBuilder, 10, 'upcoming');

        return $this->dashboardView('vet/tasks', [
            'pageTitle' => "Today's Tasks",
            'tasks'     => $tasks,
            'upcoming'  => $upcoming,
            'pager'     => $pager,
            'search'    => $search,
            'today'     => date('l, j F Y'),
        ]);
    }

    /**
     * Mark a task as completed.
     * POST /vet/tasks/:id/complete
     */
    public function complete(int $id)
    {
        $task = $this->scheduleTable->where('id', $id)->get()->getRowArray();

        if (! $task) {
            return redirect()->to('/vet/tasks')->with('error', 'Task not found.');
        }

        $this->scheduleTable->where('id', $id)->update([
            'status'       => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/vet/tasks')->with('success', '"' . $task['task'] . '" marked as completed.');
    }
}
