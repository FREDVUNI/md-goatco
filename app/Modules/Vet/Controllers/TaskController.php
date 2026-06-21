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
        $vetId = $this->currentUserId();

        // Get today's tasks — assigned to this vet OR unassigned
        $tasks = $this->scheduleTable
            ->select('vet_schedules.*')
            ->where('DATE(scheduled_at)', date('Y-m-d'))
            ->where('status !=', 'cancelled')
            ->groupStart()
                ->where('assigned_vet_id', $vetId)
                ->orWhere('assigned_vet_id IS NULL')
            ->groupEnd()
            ->orderBy('scheduled_at', 'ASC')
            ->get()->getResultArray();

        // Also get upcoming tasks (next 7 days)
        $upcoming = $this->scheduleTable
            ->select('vet_schedules.*')
            ->where('DATE(scheduled_at) >', date('Y-m-d'))
            ->where('DATE(scheduled_at) <=', date('Y-m-d', strtotime('+7 days')))
            ->where('status !=', 'cancelled')
            ->groupStart()
                ->where('assigned_vet_id', $vetId)
                ->orWhere('assigned_vet_id IS NULL')
            ->groupEnd()
            ->orderBy('scheduled_at', 'ASC')
            ->get()->getResultArray();

        return $this->dashboardView('vet/tasks', [
            'pageTitle' => "Today's Tasks",
            'tasks'     => $tasks,
            'upcoming'  => $upcoming,
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
