<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Controller\Users;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Validation\Validation;

class UserViewController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow('view');

        return parent::beforeFilter($event);
    }

    /**
     * User Index action
     *
     * @param string $id uuid|me
     * @return void
     */
    public function view($id)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            if ($id === 'me') {
                $id = $this->User->id(); // me returns the currently logged-in user
            } else {
                throw new BadRequestException(__('The user id is not valid.'));
            }
        }

        // Retrieve the user
        $this->loadModel('Users');
        $user = $this->Users->find('view', ['id' => $id, 'role' => $this->User->role() ])->first();
        if (empty($user)) {
            throw new NotFoundException(__('The user does not exist.'));
        }
        $this->success($user);
    }
}