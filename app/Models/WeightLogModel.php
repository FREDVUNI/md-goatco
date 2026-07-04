<?php
declare(strict_types=1);
namespace App\Models;
use CodeIgniter\Model;

class WeightLogModel extends Model
{
    protected $table         = 'weight_logs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = ['goat_id','weight_kg','logged_at','logged_by'];

    public function getForGoat(int $goatId): array
    {
        return $this->where('goat_id',$goatId)->orderBy('logged_at','DESC')->findAll();
    }
}
