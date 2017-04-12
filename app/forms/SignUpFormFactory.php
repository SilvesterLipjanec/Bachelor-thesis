<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use App\Model;


class SignUpFormFactory
{
	use Nette\SmartObject;

	const PASSWORD_MIN_LENGTH = 7;

	/** @var FormFactory */
	private $factory;

	/** @var Model\UserManager */
	private $userManager;


	public function __construct(FormFactory $factory, Model\UserManager $userManager)
	{
		$this->factory = $factory;
		$this->userManager = $userManager;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		$form->addText('username')
			->setRequired('Prosím zadajte užívateľské meno.')
			->setAttribute('placeholder','Meno')
			->setAttribute('class','w3-input w3-border w3-margin-top')
			->setAttribute('style','width:150%');

		$form->addEmail('email')
			->setRequired('Prosím zadajte email.')
			->setAttribute('placeholder','Email')
			->setAttribute('class','w3-input w3-border')
			->setAttribute('style','width:150%');
		$form->addPassword('password')
			->setOption('description', sprintf('najmenej %d znakov', self::PASSWORD_MIN_LENGTH))
			->setRequired('Prosím zadajte heslo.')
			->addRule($form::MIN_LENGTH, NULL, self::PASSWORD_MIN_LENGTH)	
			->setAttribute('placeholder','Heslo')
			->setAttribute('class','w3-input w3-border')
			->setAttribute('style','width:150%');;

		$form->addSubmit('send', 'Registrovať')
		    ->setAttribute('class','w3-button w3-black w3-section')
			->setAttribute('style','width:150%');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->userManager->add($values->username,$values->email, $values->password);
			} catch (Model\DuplicateNameException $e) {
				$form['username']->addError('Užívateľské meno už existuje.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}

}
