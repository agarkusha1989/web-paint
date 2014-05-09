<?php

class AccountController extends WebPaint\Controller\ControllerAbstract
{
    public function signupAction()
    {
        $authentication = $this->front->getApplication()->getAuthentication();
        $viewModel      = new WebPaint\View\ViewModel();
        $validator      = new Validator\SignupFormValidator();
        $validator->setDbAdapter($this->front->getApplication()->getDbAdapter());
        $userTable      = new Model\UserTable($this->front->getApplication()->getDbAdapter());
        
        if (isset($_POST['submit']))
        {
            $data = array(
                'email' => isset($_POST['email']) ? $_POST['email'] : null,
                'password' => isset($_POST['password']) ? $_POST['password'] : null,
                'confirm' => isset($_POST['confirm']) ? $_POST['confirm'] : null,
                'username' => isset($_POST['username']) ? $_POST['username'] : null,
            );
            
            $validator->validate($data);
            if ($validator->isValid())
            {
                $data['psswd'] = md5($data['password']);
                
                $userTable->signup($data);
                
                $authentication->getAdapter()->setIdentity($data['email']);
                $authentication->getAdapter()->setCredential($data['password']);
                $authentication->authenticate();
                
                header('Location: /');
            }
            $viewModel->setVariable('messages', $validator->getMessages());
            $viewModel->setVariable('values', $data);
        }
        
        return $viewModel;
    }
    
    public function signinAction()
    {
        $authentication = $this->front->getApplication()->getAuthentication();
        $viewModel      = new WebPaint\View\ViewModel();
        $validator      = new Validator\SigninFormValidator();
        
        if (isset($_POST['submit']))
        {
            $data = array(
                'email' => isset($_POST['email']) ? $_POST['email'] : null,
                'password' => isset($_POST['password']) ? $_POST['password'] : null,
            );
            
            $validator->validate($data);
            if ($validator->isValid())
            {
                $authentication->getAdapter()->setIdentity($data['email']);
                $authentication->getAdapter()->setCredential($data['password']);
                
                if ($authentication->authenticate())
                {
                    header('Location: /');
                }
                $viewModel->setVariable('error', true);
            }
            $viewModel->setVariable('messages', $validator->getMessages());
            $viewModel->setVariable('values', $validator->getData());
        }
        
        return $viewModel;
    }
    
    public function signoutAction()
    {
        $authentication = $this->front->getApplication()->getAuthentication();
        $authentication->clearIdentity();
        
        header('Location: /');
    }
}