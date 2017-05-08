<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use App\Model;
use Nette\Utils\Random; 
use Nette\Utils\Html; 
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Application\UI\ITemplateFactory;

class SignUpFormFactory
{
	use Nette\SmartObject;
	
	const PASSWORD_MIN_LENGTH = 7;

	/** @var FormFactory */
	private $factory;

	/** @var Model\UserManager */
	private $userManager;
	private $templateFactory;

	public function __construct(FormFactory $factory, Model\UserManager $userManager, ITemplateFactory $templateFactory)
	{
		$this->templateFactory = $templateFactory;
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
			->setAttribute('style','width:150%');
		$form->addPassword('confirm_password')
			->setRequired('Prosím znovu zadajte heslo pre overenie.')
			->addRule($form::MIN_LENGTH, NULL, self::PASSWORD_MIN_LENGTH)	
			->setAttribute('placeholder','Potvrdenie hesla')
			->setAttribute('class','w3-input w3-border')
			->setAttribute('style','width:150%');

		$form->addSubmit('send', 'Registrovať')
		    ->setAttribute('class','w3-button w3-black w3-section')
			->setAttribute('style','width:150%');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			if($values->password != $values->confirm_password)
			{
				$form['confirm_password']->addError('Heslá sa nezhodujú');
			}
			else
			{
				try {
					$token = Random::generate(20); 
					$this->userManager->add($values->username,$values->email, $values->password,$token);
			
					$template = $this->templateFactory->createTemplate()
        				    ->setFile(__DIR__ . '/../../presenters/templates/Sign/email.latte');
		            
        			$link = 'https://sec6net.fit.vutbr.cz/xlipja01/www/sign/verify?token='.$token;
					$mail = new Message;
					$mail->setFrom('Print&Scan <xlipja01@stud.fit.vutbr.cz>')
					    ->addTo($form->values['email'])
					    ->setSubject('Aktivácia účtu')
					    ->setBody("Dobrý deň,\n\npre overenie Vašeho účtu kliknite na nasledujúci odkaz\n".$link);
					$mailer = new SendmailMailer;
					$mailer->send($mail);
		          

				} catch (Model\DuplicateNameException $e) {
					$form['username']->addError('Užívateľské meno už existuje.');
					return;
				}
				$onSuccess();

			}
			
		};

		return $form;
	}

}
