<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;

class ItemsController extends AppController
{
    public function index()
    {
        $this->loadModel('LostItems');
        $this->loadModel('FoundItems');

        $lostQuery = $this->LostItems->find()
            ->contain(['Users'])
            ->orderDesc('date_lost')
            ->limit(200);

        $foundQuery = $this->FoundItems->find()
            ->contain(['Users'])
            ->orderDesc('date_found')
            ->limit(200);

        $lostItems = $lostQuery->all()->toArray();
        $foundItems = $foundQuery->all()->toArray();

        $items = [];

        foreach ($lostItems as $li) {
            $reporter = $li->has('user') ? $li->user->name : 'No User';
            $items[] = [
                'id' => $li->id,
                'type' => 'lost',
                'item_name' => $li->item_name,
                'category' => $li->category,
                'location' => $li->location,
                'status' => $li->status,
                'reporter' => $reporter,
                'image' => $li->image,
                'date' => $li->date_lost,
            ];
        }

        foreach ($foundItems as $fi) {
            $reporter = $fi->has('user') ? $fi->user->name : 'No User';
            $items[] = [
                'id' => $fi->id,
                'type' => 'found',
                'item_name' => $fi->item_name,
                'category' => $fi->category,
                'location' => $fi->location,
                'status' => $fi->status,
                'reporter' => $reporter,
                'image' => $fi->image,
                'date' => $fi->date_found,
            ];
        }

        // Simple sort by date descending where possible
        usort($items, function ($a, $b) {
            $ad = strtotime((string)$a['date']);
            $bd = strtotime((string)$b['date']);
            return $bd <=> $ad;
        });

        $this->set(compact('items'));
    }
}
