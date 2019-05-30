To add an Image as background by selecting from a module. The new extension is created under starter_module name.

I added in: admin/view/template/extension/module/starter_module.twig

```
<div class="form-group">
		<label class="col-sm-2 control-label" for="input-headbg">Header Image</label>
		<div class="col-sm-10">
		<a href="" id="thumb-headbg" data-toggle="image" class="img-thumbnail">
		<img src="{{ headbg }}" alt="" title="" data-placeholder="{{ placeholder }}" />
		</a>
		<input type="hidden" name="config_headbg" value="{{ config_headbg }}" id="input-headbg" />
	</div>
</div>

```

In admin/controller/extension/module/starter_module.php

Before this code: (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

```
//Top page
$this->load->model('tool/image');
$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

```

After this code: (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

```
   	$this->model_setting_setting->editSetting('config_headbg', $this->request->post);

```
In the body section add this

```
// Module Image

if (isset($this->request->post['config_headbg'])) {
	$data['config_headbg'] = $this->request->post['config_headbg'];
} else {
	$data['config_headbg'] = $this->config->get('config_headbg');
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

```

In catalog/controller/common/header.php

```
if (is_file(DIR_IMAGE . $this->config->get('config_headbg'))) {
	$data['headbg'] = $server . 'image/' . $this->config->get('config_headbg');
} else {
	$data['headbg'] = '';
}

```

In catalog/view/theme/default/template/common/header.twig

```

{{ headbg }}

```
