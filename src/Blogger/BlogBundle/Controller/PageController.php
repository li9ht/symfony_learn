<?php 

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//inport new namespace
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;

class PageController extends Controller{

	public function indexAction(){
		return $this->render('BloggerBlogBundle:Page:index.html.twig');
	}

	public function aboutAction(){
		return $this->render('BloggerBlogBundle:Page:about.html.twig');
	}

	public function contactAction(){
		//return $this->render('BloggerBlogBundle:Page:contact.html.twig');
		
		$enquiry = new Enquiry();
		$form = $this->createForm(new EnquiryType(),$enquiry);

		$request = $this->getRequest();
		if($request->getMethod() == 'POST'){
			$form->bind($request);

			if($form->isValid()){

				$message = \Swift_Message::newInstance()
					->setSubject('Contact enquiry from symblog')
					->setFrom('me@localhost')
					->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
					->setBody($this->render('BloggerBlogBundle:Page:contactEmail.txt.twig',array('enquiry'=> $enquiry)));
				$this->get('Mailer')->send($message);	
				
				$this->get('session')->getFlashBag()->add('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

	            return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));

			}

		}

		return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
			'form' => $form->createView()
		));


	}
}