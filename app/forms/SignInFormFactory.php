<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
use App\Model\DatabaseRepository;

class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;
	private $database;

	public function __construct(FormFactory $factory, User $user, DatabaseRepository $database)
	{
		$this->factory = $factory;
		$this->user = $user;
		$this->database = $database;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();

		$form->addText('username')
			->setRequired('Prosím zadaj užívateľské meno.')
			->setAttribute('placeholder','Meno')
			->setAttribute('class','w3-input w3-border w3-margin-top')
			->setAttribute('style','width:100%');

		$form->addPassword('password')
			->setRequired('Prosím zadajte vaše heslo.')
			->setAttribute('placeholder','Heslo')
			->setAttribute('class','w3-input w3-border')
			->setAttribute('style','width:100%');

		$form->addCheckbox('remember', 'Zapamätať prihlásenie')
			->setAttribute('class','w3-check w3-section');

		$form->addSubmit('send', 'Prihlásiť')
			->setAttribute('class','w3-button w3-black w3-section')
			->setAttribute('style','width:100%');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
				$user = $this->database->findUserByName($values->username);
				if($user['token'] != NULL)
				{
					throw new AccountNotActiveException;					
				}
				else
				{
					try { 
						$this->user->login($values->username, $values->password);
					}
					catch (Nette\Security\AuthenticationException $e ) {
						if($e->getCode() == Nette\Security\IAuthenticator::IDENTITY_NOT_FOUND)
							$form['username']->addError($e->getMessage());
						else
							$form['password']->addError($e->getMessage());
						return;
					}
				}
			} catch (AccountNotActiveException $e) {
				$form['username']->addError('Účet zatiaľ nebol aktivovaný');
				return;
			}
			$onSuccess();
		};

		return $form;
	}

}
class AccountNotActiveException extends \Exception
{}
