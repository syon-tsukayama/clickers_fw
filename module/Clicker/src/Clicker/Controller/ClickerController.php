<?php
namespace Clicker\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Clicker\Model\Question;
use Clicker\Form\QuestionForm;

class ClickerController extends AbstractActionController
{
    protected $questionTable;

    public function indexAction()
    {
        return new ViewModel(
            array(
                'questions' => $this->getQuestionTable()->fetchAll()
                )
            );
    }

    public function addAction()
    {
        $form = new QuestionForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $question = new Question();
            $form->setInputFilter($question->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $question->exchangeArray($form->getData());
                $this->getQuestionTable()->saveQuestion($question);

                // Redirect to list of albums
                return $this->redirect()->toRoute('clicker');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id)
        {
            return $this->redirect()->toRoute('clicker', array('action' => 'add'));
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try
        {
            $question = $this->getQuestionTable()->getQuestion($id);
        }
        catch (\Exception $ex)
        {
            return $this->redirect()->toRoute('clicker', array('action' => 'index'));
        }

        $form  = new QuestionForm();
        $form->bind($question);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $form->setInputFilter($question->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $this->getQuestionTable()->saveQuestion($question);

                // Redirect to list of albums
                return $this->redirect()->toRoute('clicker');
            }
        }

        return array('id' => $id, 'form' => $form);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id)
        {
            return $this->redirect()->toRoute('clicker');
        }

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes')
            {
                $id = (int) $request->getPost('id');
                $this->getQuestionTable()->deleteQuestion($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('clicker');
        }

        return array(
            'id'       => $id,
            'question' => $this->getQuestionTable()->getQuestion($id)
        );
    }

    public function getQuestionTable()
    {
        if (!$this->questionTable)
        {
            $sm = $this->getServiceLocator();
            $this->questionTable = $sm->get('Clicker\Model\QuestionTable');
        }
        return $this->questionTable;
    }
}
