<?php

namespace App\Presenters;

use Nette;
use App\Forms;
use App\Model;



class SignPresenter extends BasePresenter
{
	/** @var Forms\SignInFormFactory @inject */
	public $signInFactory;

	/** @var Forms\SignUpFormFactory @inject */
	public $signUpFactory;
	private $database;
	public function __construct(Model\DatabaseRepository $database)
	{
		$this->database = $database;
	}
	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		return $this->signInFactory->create(function () {
			$this->redirect('Homepage:');
		});
	}


	/**
	 * Sign-up form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignUpForm()
	{
		return $this->signUpFactory->create(function () {
			$this->redirect('Sign:email');

			
		});
	}


	public function actionOut()
	{
		$this->getUser()->logout();
	}
	public function actionVerify($token)
	{
		$user = $this->database->findUserByToken($token);
		if(isset($user))
		{
			$row = $this->database->acitvateAccount($token);
			if($row != 0)
			{
				$this->flashMessage('Účet sa nepodarilo aktivovať.');
				$this->redirect('Homepage:');
			}
			else
			{
				$this->flashMessage('Váš účet bol aktivovaný. Teraz sa môžete prihlásiť.');
				$this->redirect('Sign:in');							
			}
		}
		else
		{
			$this->flashMessage('Účet sa nepodarilo aktivovať.');
			$this->redirect('Homepage:');
		}
	}

}
