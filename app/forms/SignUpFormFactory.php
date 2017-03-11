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
		$form->addText('username', 'Zadajte užívateľské meno:')
			->setRequired('Prosím zadajte užívateľské meno.');

		$form->addEmail('email','Zadajte email:')
			->setRequired('Prosím zadajte email.');
		$form->addPassword('password', 'Zadajte heslo:')
			->setOption('description', sprintf('najmenej %d znakov', self::PASSWORD_MIN_LENGTH))
			->setRequired('Prosím zadajte heslo.')
			->addRule($form::MIN_LENGTH, NULL, self::PASSWORD_MIN_LENGTH);

		$form->addSubmit('send', 'Registrovať');

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
