<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Claim Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $found_item_id
 * @property string|null $claim_status
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\FoundItem $found_item
 */
class Claim extends Entity
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
    'found_item_id' => true,
    'lost_item_id' => true,
    'claim_reason' => true,
    'item_details' => true,
    'lost_location' => true,
    'lost_date' => true,
    'proof_image' => true,
    'claim_status' => true,
    'admin_notes' => true,
    'created_at' => true,
    'updated_at' => true,
    'user' => true,
    'found_item' => true,
    'lost_item' => true,
];
}
