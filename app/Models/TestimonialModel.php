<?php
declare(strict_types=1);
namespace App\Models;
use CodeIgniter\Model;

class TestimonialModel extends Model
{
    protected $table         = 'testimonials';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['quote', 'author_name', 'author_role', 'avatar_url', 'rating', 'sort_order', 'is_active'];

    /** Live homepage feed — active testimonials in their curated order. */
    public function getActive(): array
    {
        return $this->where('is_active', 1)->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
    }

    /** Admin listing — everything, active or not, in curated order. */
    public function getAllOrdered(): array
    {
        return $this->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
    }

    /** Next sort_order slot for a newly-created testimonial (appends to the end). */
    public function nextSortOrder(): int
    {
        $max = $this->selectMax('sort_order')->first();
        return (int) ($max['sort_order'] ?? 0) + 1;
    }

    /** Swap sort_order with the testimonial immediately before/after this one. */
    public function moveOne(int $id, string $direction): void
    {
        $current = $this->find($id);
        if (! $current) return;

        $neighbor = $direction === 'up'
            ? $this->where('sort_order <', $current['sort_order'])->orderBy('sort_order', 'DESC')->first()
            : $this->where('sort_order >', $current['sort_order'])->orderBy('sort_order', 'ASC')->first();
        if (! $neighbor) return;

        $this->update($current['id'], ['sort_order' => $neighbor['sort_order']]);
        $this->update($neighbor['id'], ['sort_order' => $current['sort_order']]);
    }
}
