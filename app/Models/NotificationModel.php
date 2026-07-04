<?php
declare(strict_types=1);
namespace App\Models;
use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table         = 'notifications';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['user_id','title','body','type','is_read','link'];

    public function getForUser(int $userId, int $limit = 10): array
    {
        return $this->where('user_id',$userId)->orderBy('created_at','DESC')->limit($limit)->findAll();
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->where('user_id',$userId)->where('is_read',0)->countAllResults();
    }

    public function markRead(int $id): void { $this->update($id,['is_read'=>1]); }

    public function notifyAllAdmins(string $title, string $body, string $type = 'info'): void
    {
        $users = (new UserModel())->getByRole('super_admin');
        foreach ($users as $u) {
            $this->insert(['user_id'=>$u['id'],'title'=>$title,'body'=>$body,'type'=>$type,'is_read'=>0]);
        }
    }
}
