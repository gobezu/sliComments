<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class sliCommentsControllerComments extends JController
{
	public function display()
	{
		if (JRequest::getCmd('option') != 'com_content' && JRequest::getCmd('view') != 'article') {
			JError::raiseError(403, 'Direct access to this component is unauthorized.');
		}
		return parent::display();
	}
	public function post()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user = JFactory::getUser();
		$data = JRequest::get('post');
		if (!$user->authorise('post', 'com_slicomments')){
			$this->setMessage(JText::_('COM_COMMENTS_NO_AUTH'), 'error');
		}
		else
		{
			$model = $this->getModel();
			$data['status'] = $user->authorise('auto_publish', 'com_slicomments') ? 1 : 0;
			$data = $model->filter($data);
			if (!$model->validate($data)) {
				$this->setMessage($model->getError(), 'error');
			} elseif (!$model->save($data)) {
				$this->setMessage($model->getError(), 'error');
			}
		}

		$this->setRedirect(base64_decode($data['return']));
	}

	public function delete()
	{
		// Check for request forgeries.
		JRequest::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		try {
			$return = JRequest::getVar('return', null, 'get');
			$user = JFactory::getUser();
			$model = $this->getModel();
			$table = $model->getTable();
			$id = JRequest::getInt('id', null, 'get');
	
			if (!$id) {
				throw new Exception(JText::_('COM_COMMENTS_ERROR_INVALID_ID'));
			}
			if (!$table->load($id)) {
				throw new Exception(JText::_('COM_COMMENTS_ERROR_COMMENT_DONT_EXIST'));
			}
			if (!(
				$user->authorise('edit', 'com_slicomments') || 
				($user->authorise('edit.own', 'com_slicomments') && $table->user_id == $user->id)
			)) {
				throw new Exception(JText::_('COM_COMMENTS_NO_AUTH'));
			}
			if (!$model->delete($id)) {
				throw new Exception($model->getError()->getMessage());
			}
		} catch (Exception $e) {
			$this->setMessage($e->getMessage(), 'error');
		}

		$this->setRedirect(base64_decode($return));
	}

	public function getModel()
	{
		static $model;
		if ($model == null)
		{
			$model = JModel::getInstance('Comments', 'sliCommentsModel');
			// Task is a reserved state
			$model->setState('task', $this->task);

			// Let's get the application object and set menu information if it's available
			$app	= JFactory::getApplication();
			$menu	= $app->getMenu();

			if (is_object($menu)) {
				if ($item = $menu->getActive()) {
					$params	= $menu->getParams($item->id);
					// Set default state data
					$model->setState('parameters.menu', $params);
				}
			}
		}
		return $model;
	}
	public function vote()
	{
		if (!JFactory::getUser()->authorise('vote', 'com_slicomments')){
			$this->setMessage(JText::_('COM_COMMENTS_NO_AUTH'), 'error');
		}
		else
		{
			$model = $this->getModel();
			$vote = JRequest::getInt('v');
			$comment_id = JRequest::getInt('id');
			if ($model->vote($comment_id, $vote)) {
				$this->setMessage(JText::_('COM_COMMENTS_SUCCESS_RATE'));
			} else {
				$this->setMessage($model->getError(), 'error');
			}
		}

		$this->setRedirect(base64_decode(JRequest::getVar('return', JRoute::_('index.php'), 'GET', 'ALNUM')));
	}
}