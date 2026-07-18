<?php
declare(strict_types=1);
namespace App\Models;
use CodeIgniter\Model;

class ContactMessageModel extends Model
{
    protected $table         = 'contact_messages';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['name','email','phone','subject','message','status','responded_by','responded_at'];

    public function countNew(): int { return $this->where('status','new')->countAllResults(); }

    public function getListQuery(?string $search = null): \CodeIgniter\Database\BaseBuilder
    {
        $this->orderBy('created_at','DESC');
        if ($search) {
            $this->groupStart()->like('name',$search)->orLike('email',$search)->orLike('phone',$search)->orLike('subject',$search)->groupEnd();
        }
        return $this->builder();
    }

    public function markResponded(int $id, int $respondedBy): void
    {
        $this->update($id, ['status'=>'responded','responded_by'=>$respondedBy,'responded_at'=>date('Y-m-d H:i:s')]);
    }
}
