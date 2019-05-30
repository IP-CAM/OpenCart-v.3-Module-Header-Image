<?php
class ControllerExtensionModuleStarterModule extends Controller {

    //private $error = array();
  
    public function index() {

        $this->load->language('extension/module/starter_module');

		$this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/module');  
        
        $this->load->model('setting/setting');
        
		$this->load->model('setting/extension');
        
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
	    $this->model_setting_setting->editSetting('config_new', $this->request->post);
	    $this->model_setting_setting->editSetting('config_boxed', $this->request->post);
	    // Module Image
	    $this->model_setting_setting->editSetting('config_headbg', $this->request->post);
        // Module Image
        
		if (!isset($this->request->get['module_id'])) {
			$this->model_setting_module->addModule('starter_module', $this->request->post);
		} else {
			$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
		}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}        
        
        if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
        }

        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/starter_module', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/starter_module', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/starter_module', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/starter_module', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
        
        if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		
		
		// Module Image

		if (isset($this->request->post['config_headbg'])) {
			$data['headbg'] = $this->request->post['config_headbg'];
		} else {
			$data['headbg'] = $this->config->get('config_headbg');
		}
		$this->load->model('tool/image');
		
		if (isset($this->request->post['config_headbg']) && is_file(DIR_IMAGE . $this->request->post['config_headbg'])) {
			$data['headbg'] = $this->model_tool_image->resize($this->request->post['config_headbg'], 100, 100);
		} elseif ($this->config->get('config_headbg') && is_file(DIR_IMAGE . $this->config->get('config_headbg'))) {
			$data['headbg'] = $this->model_tool_image->resize($this->config->get('config_headbg'), 100, 100);
		} else {
			$data['headbg'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		// Module Image
	
		// Hozzaadott Kod
		
		if (isset($this->request->post['config_boxed'])) {
			$data['config_boxed'] = $this->request->post['config_boxed'];
		} else {
			$data['config_boxed'] = $this->config->get('config_boxed');
		}
		if (isset($this->request->post['config_new'])) {
			$data['config_new'] = $this->request->post['config_new'];
		} elseif (!empty($module_info)) {
			$data['config_new'] = $module_info['config_new'];
		} else {
			$data['config_new'] = '';
		}
		
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('extension/module/starter_module', $data));
      
    }

    protected function validate() {

        if (!$this->user->hasPermission('modify', 'extension/module/starter_module')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		return !$this->error;	

    }
	
}