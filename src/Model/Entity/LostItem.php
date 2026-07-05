<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LostItem Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $item_name
 * @property string|null $category
 * @property string|null $description
 * @property string|null $location
 * @property \Cake\I18n\FrozenDate|null $date_lost
 * @property string|null $status
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 *
 * @property \App\Model\Entity\User $user
 */
class LostItem extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
    'user_id' => true,
    'item_name' => true,
    'image' => true,
    'category' => true,
    'description' => true,
    'private_details' => true,
    'location' => true,
    'date_lost' => true,
    'status' => true,
    'created_at' => true,
    'updated_at' => true,
];
}
